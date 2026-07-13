<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{
    public function placeOrder(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.phone' => 'required|string|max:20',
            'shipping_address.email' => 'nullable|email',
            'shipping_address.country' => 'required|string',
            'shipping_address.division' => 'required|string',
            'shipping_address.district' => 'required|string',
            'shipping_address.thana' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.postal_code' => 'nullable|string',

            'payment_method' => 'required|in:cash_on_delivery,bkash,nagad,stripe,paypal',
            'notes' => 'nullable|string',

            'products' => 'required|array|min:1',

            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors()->all(), 422);
        }
        $validatedData = $validator->validated();
        $user = auth()->user();

        $subtotal = 0;
        $grandTotal = 0;

        foreach ($validatedData['products'] as $product) {
            $productModel = Product::find($product['product_id']);
            if (!$productModel) {
                return ApiResponse::error('Product not found', ['product_id' => $product['product_id']], 404);
            }
            if ($productModel->total_stock < $product['quantity']) {
                return ApiResponse::error('Insufficient stock for product', ['product_id' => $product['product_id']], 400);
            }
            $subtotal += $productModel->sale_price * $product['quantity'];
            $grandTotal += $productModel->sale_price * $product['quantity'];
        }
        if ($grandTotal <= 0) {
            return ApiResponse::error('Invalid order total', ['grand_total' => $grandTotal], 400);
        }
        if ($subtotal <= 0) {
            return ApiResponse::error('Invalid order subtotal', ['subtotal' => $subtotal], 400);
        }
        $shippingCharge = 0;
        // Create a new order
        try {
            DB::beginTransaction();
            $order = Order::create([
                'order_no' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount_amount' =>  0,
                'coupon_discount' =>  0,
                'tax_amount' =>  0,
                'shipping_charge' =>  $shippingCharge,
                'grand_total' => $grandTotal + $shippingCharge,
                'payment_method' => $validatedData['payment_method'],
                'notes' => $validatedData['notes'] ?? null,
                'placed_at' => now(),
            ]);
            // order items and order address creation logic can be added here as needed
            $orderItemsData = [];
            $orderAddressData = [];
            foreach ($request->input('products', []) as $item) {
                $orderItemsData[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => Product::find($item['product_id'])->sale_price ?? 0,
                    'subtotal' => (Product::find($item['product_id'])->sale_price ?? 0) * $item['quantity'],
                    'product_name' =>   Product::find($item['product_id'])->name ?? 'Unknown Product',
                    'variant_name' => null,
                    'sku' => null,
                ];
            }
            $order->orderItems()->createMany($orderItemsData);

            if ($request->has('shipping_address')) {
                $orderAddressData = $request->input('shipping_address');
            }
            $order->orderAddress()->create($orderAddressData);

            // Update product stock
            foreach ($validatedData['products'] as $product) {
                $productModel = Product::find($product['product_id']);
                $productModel->decrement('total_stock', $product['quantity']);
                
                // Update product variant stock if variant_id is provided
                if (!empty($product['product_variant_id'])) {
                    DB::table('product_variants')
                        ->where('id', $product['product_variant_id'])
                        ->decrement('stock', $product['quantity']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to place order', ['error' => $e->getMessage()], 500);
        }

        return ApiResponse::success('Order placed successfully', ['order_id' => $order->id]);
    }
}
