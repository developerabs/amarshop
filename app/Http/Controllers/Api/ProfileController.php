<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image ? getImageUrl($user->image) : null,
            'created_at' => $user->created_at,
        ];
        $orders = Order::with('orderItems')->where('user_id', $user->id)->orderBy('created_at', 'desc');
        $recentOrders = $orders->take(5)->get();
        $recentOrders = $recentOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_no,
                'status' => $order->order_status,
                'total_amount' => $order->grand_total,
                'placed_at' => $order->placed_at,
                'total_items' => $order->orderItems->sum('quantity'),
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'sku' => $item->sku,
                    ];
                }),
            ];
        });
        
        $totalOrders = $orders->count();
        $totalSpent = $orders->sum('grand_total');
        $totalWishlistItems = Wishlist::where('user_id', $user->id)->count();

        $data = [
            'user' => $userData,
            'total_orders' => $totalOrders,
            'total_spent_amount' => $totalSpent,
            'total_wishlist_items' => $totalWishlistItems,
            'recent_orders' => $recentOrders,
        ];
        return ApiResponse::success('User profile retrieved successfully', $data);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];

        $data = [
            'user' => $userData,
        ];
        return ApiResponse::success('Profile updated successfully', $data);
    }
    public function orders(Request $request)
    {
        $user = auth()->user();
        $orders = Order::with('orderItems')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_no,
                'status' => $order->order_status,
                'total_amount' => $order->grand_total,
                'placed_at' => $order->placed_at,
                'total_items' => $order->orderItems->sum('quantity'),
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'sku' => $item->sku,
                    ];
                }),
            ];
        });
        $data = [
            'orders' => $orders,
        ];

        return ApiResponse::success('User orders retrieved successfully', $data);
    }
}
