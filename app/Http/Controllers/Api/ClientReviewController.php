<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\ClientReview;

class ClientReviewController extends Controller
{
    public function index()
    {
        $clientReviews = ClientReview::where('status', true)
            ->latest()
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'title' => $review->title,
                    'description' => $review->description,
                    'rating' => (float) $review->rating,
                    'image' => $review->image ? getImageUrl($review->image) : null,
                    'video_link' => $review->video_link,
                    'created_at' => $review->created_at,
                ];
            });

        return ApiResponse::success(
            'Client reviews retrieved successfully',
            ['client_reviews' => $clientReviews]
        );
    }
}
