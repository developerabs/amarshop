<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::with('category')->latest()->paginate(10);
        $blog->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'category' => $item->category ? $item->category->name : null,
                'slug' => $item->slug,
                'content' => $item->content,
                'thumbnail' => $item->thumbnail ? getImageUrl($item->thumbnail) : null,
                'created_at' => $item->created_at,
            ];
        });
        $data = [
            'blogs' => $blog->items(),
        ];
        return ApiResponse::success('Blog posts retrieved successfully', $data);
    }

    public function show($slug)
    {
        $blog = Blog::with('category')->where('slug', $slug)->first();
        if (!$blog) {
            return ApiResponse::error('Blog post not found', null, 404);
        }
        $data = [
            'id' => $blog->id,
            'title' => $blog->title,
            'category' => $blog->category ? $blog->category->name : null,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'thumbnail' => $blog->thumbnail ? getImageUrl($blog->thumbnail) : null,
            'created_at' => $blog->created_at,
        ];
        return ApiResponse::success('Blog post retrieved successfully', $data);
    }
}
