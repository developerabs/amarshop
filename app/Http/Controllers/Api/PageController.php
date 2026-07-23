<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\MenuItem;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $menuItem = MenuItem::where('url', $slug)->orWhere('url', '/'.$slug)->first();
        if (!$menuItem) {
            return ApiResponse::error('Page not found', null, 404);
        }

        $page = Page::where('id', $menuItem->reference_id)->first();

        if (!$page) {
            return ApiResponse::error('Page not found', null, 404);
        }
        $data = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'permalink' => $page->permalink,
            'content' => $page->content,
            'banner' => $page->banner ? getImageUrl($page->banner) : null,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
        ];

        return ApiResponse::success('Page retrieved successfully', $data);
    }
}
