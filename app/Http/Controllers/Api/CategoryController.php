<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)
            ->where('level', 0)
            ->with(['children' => function ($query) {
                $query->where('status', true)
                    ->with(['children' => function ($query) {
                        $query->where('status', true);
                    }]);
            }])
            ->orderBy('id', 'desc')
            ->get()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'image' => getImageUrl($category->image),
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'icon' => $child->icon,
                            'image' => getImageUrl($child->image),
                            'children' => $child->children->map(function ($subChild) {
                                return [
                                    'id' => $subChild->id,
                                    'name' => $subChild->name,
                                    'slug' => $subChild->slug,
                                    'icon' => $subChild->icon,
                                    'image' => getImageUrl($subChild->image),
                                ];
                            }),
                        ];
                    }),
                ];
            });

        $data = [
            'categories' => $categories,
        ];

        return ApiResponse::success('Categories retrieved successfully', $data);
    }
}
