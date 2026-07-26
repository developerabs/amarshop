<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\SocialLink;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(function ($socialLink) {
                return [
                    'id' => $socialLink->id,
                    'name' => $socialLink->name,
                    'url' => $socialLink->url,
                    'icon' => $socialLink->icon,
                    'sort_order' => $socialLink->sort_order,
                ];
            });

        return ApiResponse::success('Social links retrieved successfully', ['social_links' => $socialLinks]);
    }
}
