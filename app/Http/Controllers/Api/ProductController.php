<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function flashDeals()
    {
        $products = Product::take(10)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'total_stock' => $product->total_stock,
                'description' => $product->description,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
            ];
        });
        $message = 'Flash deals fetched successfully';
        return ApiResponse::success($message, $products);
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
                'image' => $variant->image,

                'attributes' => $variant->variantValues
                    ->pluck('attribute_value', 'attribute_name')
            ];
        });

        $message = 'Product details fetched successfully';
        return ApiResponse::success($message, [
            'product' => $product,
            'attributes' => $attributes,
            'variants' => $variants,
        ]);
    }
}
