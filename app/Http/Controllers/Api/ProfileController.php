<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'image' => $user->profile_image ? getImageUrl($user->profile_image) : null,
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
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->update($validatedData);

        if ($request->hasFile('profile_image')) {
            $user->profile_image = updateImage($request->file('profile_image'), 'users', $user->profile_image);
            $user->save();
        }
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_image' => $user->profile_image ? getImageUrl($user->profile_image) : null,
        ];

        $data = [
            'user' => $userData,
        ];
        return ApiResponse::success('Profile updated successfully', $data);
    }
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return ApiResponse::error('Current password is incorrect', null, 400);
        }

        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return ApiResponse::success('Password updated successfully');
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
