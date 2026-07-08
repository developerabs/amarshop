<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homeProducts(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->where('status', true);

        $type = $request->get('type');

        $typeMappings = [
            'flash-deals' => 'is_flash_deal',
            'featured'    => 'is_featured',
            'trending'    => 'is_trending',
            'daily-offer' => 'is_daily_offer',
        ];
        $productTypes = array_keys($typeMappings);

        if ($type && isset($typeMappings[$type])) {
            $query->where($typeMappings[$type], true);
        }

        $products = $query
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'category_name' => $product->category->name ?? null,
                    'brand_name' => $product->brand->name ?? null,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'total_stock' => $product->total_stock,
                    'description' => $product->description,
                    'meta_title' => $product->meta_title,
                    'meta_description' => $product->meta_description,
                    'discount_amount' => $product->discount_amount,
                    'discount_type' => $product->discount_type,
                    'images' => collect($product->image)->map(function ($image) {
                        return $image ? getImageUrl($image) : null;
                    }),
                ];
            });
            
        $data = [
            'product_types' => $productTypes,
            'products' => $products,
        ];

        return ApiResponse::success(
            'Products fetched successfully',
            $data
        );
    }
}
