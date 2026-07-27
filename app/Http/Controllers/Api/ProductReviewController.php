<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $validated = $validator->validated();
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('Unauthorized', null, 401);
        }

        $productReview = ProductReview::create([
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
                'is_approved' => false,
                'approved_at' => null,
            ]
        );

        return ApiResponse::success('Review submitted successfully. It will be visible after admin approval.', [
            'review' => [
                'id' => $productReview->id,
                'product_id' => $productReview->product_id,
                'user_id' => $productReview->user_id,
                'rating' => $productReview->rating,
                'review' => $productReview->review,
                'is_approved' => $productReview->is_approved,
            ],
        ]);
    }
}
