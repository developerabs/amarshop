<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'sort_order' => $faq->sort_order,
                ];
            });

        return ApiResponse::success(
            'FAQs retrieved successfully',
            ['faqs' => $faqs]
        );
    }
}
