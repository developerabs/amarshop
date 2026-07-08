<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
                        return asset('storage/' . $image);
                    }),
                ];
            });

        return ApiResponse::success(
            'Products fetched successfully',
            $products
        );
    }
    public function details($slug)
    {
        $product = Product::with([
            'category:id,name,slug',
            'brand:id,name,slug',
            'variants.variantValues'
        ])->where('slug', $slug)->first();

        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }

        $productData = [
            'id' => $product->id,
            'category' => [
                'id' => $product->category->id ?? null,
                'name' => $product->category->name ?? null,
                'slug' => $product->category->slug ?? null,
            ],
            'brand' => [
                'id' => $product->brand->id ?? null,
                'name' => $product->brand->name ?? null,
                'slug' => $product->brand->slug ?? null,
            ],
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'total_stock' => $product->total_stock,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'discount_amount' => $product->discount_amount,
            'discount_type' => $product->discount_type,
            'is_flash_deal' => $product->is_flash_deal,
            'is_featured' => $product->is_featured,
            'is_trending' => $product->is_trending,
            'is_daily_offer' => $product->is_daily_offer,
            'status' => $product->status,
            'thumbnail' => asset('storage/' . $product->thumbnail),
            'images' => collect($product->image)->map(function ($image) {
                return asset('storage/' . $image);
            }),
            'created_at' => optional($product->created_at)->toDateTimeString(),
            'updated_at' => optional($product->updated_at)->toDateTimeString(),
        ];

        $attributes = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->variantValues as $value) {
                $attributes[$value->attribute_name][] = $value->attribute_value;
            }
        }

        foreach ($attributes as $key => $values) {
            $attributes[$key] = array_values(array_unique($values));
        }

        $variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->additional_price,
                'cost' => $variant->additional_cost,
                'stock' => $variant->stock,
                'image' => $variant->image ? asset('storage/' . $variant->image) : null,
                'attributes' => $variant->variantValues
                    ->pluck('attribute_value', 'attribute_name')
            ];
        });

        $message = 'Product details fetched successfully';
        return ApiResponse::success($message, [
            'product' => $productData,
            'attributes' => $attributes,
            'variants' => $variants,
        ]);
    }
}
