<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Product;
use App\Models\Admin\ShippingCharges;
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
        $totalTaxAmount = 0;
        $totalDiscountAmount = 0;

        foreach ($validatedData['products'] as $product) {
            $productModel = Product::with('variants')->find($product['product_id']);
            if (!$productModel) {
                return ApiResponse::error('Product not found', ['product_id' => $product['product_id']], 404);
            }
            if ($productModel->total_stock < $product['quantity']) {
                return ApiResponse::error('Insufficient stock for product', ['product_id' => $product['product_id']], 400);
            }
            if (!empty($product['product_variant_id'])) {
                $variantProduct = $productModel->variants()->where('id', $product['product_variant_id'])->first();
                if (!$variantProduct) {
                    return ApiResponse::error('Product variant not found', ['product_variant_id' => $product['product_variant_id']], 404);
                }
                if ($variantProduct->stock < $product['quantity']) {
                    return ApiResponse::error('Insufficient stock for product variant', ['product_variant_id' => $product['product_variant_id']], 400);
                }
                $productPrice = $variantProduct->price * $product['quantity'];
                $subtotal += $productPrice;

                if ($productModel->discount_amount > 0) {
                    if ($productModel->discount_type === 'fixed') {
                        $discountAmount = $productModel->discount_amount * $product['quantity'];
                        $productPrice -= $discountAmount; // Subtract discount from the product price
                        $totalDiscountAmount += $discountAmount;
                    } elseif ($productModel->discount_type === 'percentage') {
                        $discountAmount = ($variantProduct->price * ($productModel->discount_amount / 100)) * $product['quantity'];
                        $productPrice -= $discountAmount; // Subtract discount from the product price
                        $totalDiscountAmount += $discountAmount;
                    }
                }
                if ($productModel->tax_rate > 0) {
                    if ($productModel->tax_type == 'inclusive') {
                        $taxAmount = 0; // Tax is already included in the price
                    } elseif ($productModel->tax_type == 'exclusive') {
                        $taxAmount = $productPrice * ($productModel->tax_rate / 100);
                        $productPrice += $taxAmount; // Add tax to the product price
                        $totalTaxAmount += $taxAmount;
                    }
                }
                $grandTotal += $productPrice; // Add the final product price to the grand total
            } else {
                $productPrice = $productModel->sale_price * $product['quantity'];
                $subtotal += $productPrice;
                
                if ($productModel->discount_amount > 0) {
                    if ($productModel->discount_type === 'fixed') {
                        $discountAmount = $productModel->discount_amount * $product['quantity'];
                        $productPrice -= $discountAmount; // Subtract discount from the product price
                        $totalDiscountAmount += $discountAmount;
                    } elseif ($productModel->discount_type === 'percentage') {
                        $discountAmount = ($productModel->sale_price * ($productModel->discount_amount / 100)) * $product['quantity'];
                        $productPrice -= $discountAmount; // Subtract discount from the product price
                        $totalDiscountAmount += $discountAmount;
                    }
                }
                if ($productModel->tax_rate > 0) {
                    if ($productModel->tax_type == 'inclusive') {
                        $taxAmount = 0; // Tax is already included in the price
                    } elseif ($productModel->tax_type == 'exclusive') {
                        $taxAmount = $productPrice * ($productModel->tax_rate / 100);
                        $productPrice += $taxAmount; // Add tax to the product price
                        $totalTaxAmount += $taxAmount;
                    }
                }
                $grandTotal += $productPrice; // Add the final product price to the grand total
            }
        }    
        if ($grandTotal <= 0) {
            return ApiResponse::error('Invalid order total', ['grand_total' => $grandTotal], 400);
        }
        if ($subtotal <= 0) {
            return ApiResponse::error('Invalid order subtotal', ['subtotal' => $subtotal], 400);
        }
        if ($user == null && empty($request->guest_id)) {
            return ApiResponse::error('User not authenticated and guest ID not provided', [], 401);
        }
        $shippingCharge = 0;
        if ($request->has('shipping_id')) {
            $shippingCharge = ShippingCharges::where('id', $request->shipping_id)->value('charge');
            if ($shippingCharge === null) {
                return ApiResponse::error('Invalid shipping ID', ['shipping_id' => $request->shipping_id], 400);
            }
        }
        // Create a new order
        try {
            DB::beginTransaction();
            $order = Order::create([
                'order_no' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id ?? null,
                'guest_id' => $request->guest_id ?? null,
                'subtotal' => $subtotal,
                'discount_amount' =>  $totalDiscountAmount,
                'coupon_discount' =>  0,
                'tax_amount' =>  $totalTaxAmount,
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
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return ApiResponse::error('Product not found', ['product_id' => $item['product_id']], 404);
                }
                $variant = null;
                if (!empty($item['product_variant_id'])) {
                    $variant = DB::table('product_variants')->where('id', $item['product_variant_id'])->first();
                    if (!$variant) {
                        return ApiResponse::error('Product variant not found', ['product_variant_id' => $item['product_variant_id']], 404);
                    }
                }
                $orderItemsData[] = [
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price ?? $product->sale_price,
                    'subtotal' => ($variant->price ?? $product->sale_price) * $item['quantity'],
                    'product_name' =>   $product->name ?? 'Unknown Product',
                    'variant_name' => $variant->name ?? null,
                    'sku' => $variant->sku ?? null,
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
        $data = [
            'order' => [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'user_id' => $order->user_id,
                'guest_id' => $order->guest_id,
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'coupon_discount' => $order->coupon_discount,
                'tax_amount' => $order->tax_amount,
                'shipping_charge' => $order->shipping_charge,
                'grand_total' => $order->grand_total,
                'payment_method' => $order->payment_method,
                'notes' => $order->notes,
                'placed_at' => optional($order->placed_at)->toDateTimeString(),
                'est_delivery' => optional($order->placed_at)->addDays(7)->toDateTimeString(),
            ],
        ];
        return ApiResponse::success('Order placed successfully', $data);
    }
    public function paymentMethods()
    {
        $paymentMethods = [
            ['id' => 'cash_on_delivery', 'name' => 'Cash on Delivery'],
            ['id' => 'bkash', 'name' => 'Bkash'],
            ['id' => 'nagad', 'name' => 'Nagad'],
            ['id' => 'stripe', 'name' => 'Stripe'],
            ['id' => 'paypal', 'name' => 'PayPal'],
        ];
        return ApiResponse::success('Payment methods fetched successfully', $paymentMethods);
    }
    public function shippingCharges()
    {
        $shippingCharges = ShippingCharges::all()->map(function ($charge) {
            return [
                'id' => $charge->id,
                'name' => $charge->name,
                'charge' => $charge->charge,
            ];
        });
        $data = [
            'shipping_charges' => $shippingCharges,
        ];
        return ApiResponse::success('Shipping charges fetched successfully', $data);
    }
}
