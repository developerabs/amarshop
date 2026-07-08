<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('status', true)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug,
                    'image' => $brand->image ? getImageUrl($brand->image) : null,
                ];
            });

        $data = [
            'brands' => $brands,
        ];

        return ApiResponse::success('Brands retrieved successfully', $data);
    }
}
