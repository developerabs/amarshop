<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $guestId = $request->get('guest_id');
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->orWhere('guest_id', $guestId)
            ->get();
        $data = [
            'wishlist' => $wishlistItems,
        ];
        return ApiResponse::success('Wishlist items retrieved successfully', $data);
    }

    public function addToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors()->all(), 422);
        }
        $validatedData = $validator->validated();
        $user = auth()->user();
        $guestId = $request->get('guest_id');
        $productId = $validatedData['product_id'];

        Wishlist::firstOrCreate([
            'user_id' => $user->id ?? null,
            'guest_id' => $guestId ?? null,
            'product_id' => $productId,
        ]);

        return ApiResponse::success('Product added to wishlist successfully.');
    }

    public function removeFromWishlist(Request $request, $id)
    {
        $user = auth()->user();
        $guestId = $request->get('guest_id');

        $wishlistItem = Wishlist::where('product_id', $id)->where('user_id', $user->id)->orWhere('guest_id', $guestId)->first();
        if (!$wishlistItem) {
            return ApiResponse::error('Wishlist item not found', [], 404);
        }
        $wishlistItem->delete();

        return ApiResponse::success('Product removed from wishlist successfully.');
    }
}
