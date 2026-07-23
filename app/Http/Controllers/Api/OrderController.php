<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function trackOrder($orderId)
    {
        // $request->validate([
        //     'order_id' => 'required|string',
        // ]);

        // $orderId = $request->input('order_id');

        // Assuming you have an Order model and a method to find an order by its ID
        $order = Order::with('orderItems','orderAddress')->where('order_no', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $orderData = [
            'order_id' => $order->order_no,
            'user_id' => $order->user_id,
            'guest_id' => $order->guest_id,
            'subtotal' => $order->subtotal,
            'discount_amount' => $order->discount_amount,
            'coupon_discount' => $order->coupon_discount,
            'tax_amount' => $order->tax_amount,
            'shipping_charge' => $order->shipping_charge,
            'grand_total' => $order->grand_total,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'notes' => $order->notes,
            'placed_at' => $order->placed_at,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'order_items' => $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            }),
            'order_address' => $order->orderAddress,
        ];
        return ApiResponse::success('Order retrieved successfully', $orderData);
    }
}
