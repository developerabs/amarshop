<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function allProducts(Request $request)
    {
        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        // return $request->all();
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $categorySlug = $request->get('category_slug');
        $brandId = $request->get('brand_id');
        $brandSlug = $request->get('brand_slug');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $search = $request->get('search');
        $sortBy = $request->get('sort_by');
        
        $typeMappings = [
            'flash-deals' => 'is_flash_deal',
            'featured'    => 'is_featured',
            'trending'    => 'is_trending',
            'daily-offer' => 'is_daily_offer',
        ];

        $query = Product::with(['category', 'brand'])
            ->where('status', true);

        if ($type && isset($typeMappings[$type])) {
            $query->where($typeMappings[$type], true);
        }

        if ($categorySlug) {
            $category = Category::with(['children', 'children.children'])->where('slug', $categorySlug)->first();
            if ($category) {
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
                foreach ($category->children as $child) {
                    $categoryIds = array_merge($categoryIds, $child->children->pluck('id')->toArray());
                }
                $query->whereIn('category_id', $categoryIds);
            }
        }else if ($categoryId) {
            $category = Category::with(['children', 'children.children'])->where('id', $categoryId)->first();
            if ($category) {
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
                foreach ($category->children as $child) {
                    $categoryIds = array_merge($categoryIds, $child->children->pluck('id')->toArray());
                }
                $query->whereIn('category_id', $categoryIds);
            }
        }
        if ($brandSlug) {
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        } else if ($brandId) {
            $query->where('brand_id', $brandId);
        }


        if ($minPrice) {
            $query->where('sale_price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('sale_price', '<=', $maxPrice);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($sortBy) {
            $query->orderBy($sortBy);
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        $products->getCollection()->transform(function ($product) {
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
                'thumbnail' => $product->thumbnail ? getImageUrl($product->thumbnail) : null,
                'images' => collect($product->image)->map(function ($image) {
                    return $image ? getImageUrl($image) : null;
                }),
            ];
        });

        $data = [
            'products' => $products,
        ];

        return ApiResponse::success(
            'Products fetched successfully',
            $data
        );
    }
    public function getProductById($id)
    {
        $product = Product::with(['category', 'brand', 'variants', 'variants.variantValues'])->find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }

        $productData = [
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
            'thumbnail' => $product->thumbnail ? getImageUrl($product->thumbnail) : null,
            'images' => collect($product->image)->map(function ($image) {
                return $image ? getImageUrl($image) : null;
            }),
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'price' => $variant->additional_price,
                    'cost' => $variant->additional_cost,
                    'stock' => $variant->stock,
                    'image' => $variant->image ? getImageUrl($variant->image) : null,
                    'attributes' => $variant->variantValues
                        ->pluck('attribute_value', 'attribute_name')
                ];
            }),
        ];

        return ApiResponse::success(
            'Product fetched successfully',
            ['product' => $productData]
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
            'code' => $product->code,
            'model' => $product->model,
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
            'thumbnail' => $product->thumbnail ? getImageUrl($product->thumbnail) : null,
            'images' => collect($product->image)->map(function ($image) {
                return $image ? getImageUrl($image) : null;
            }),
            'details_image' => $product->desc_image ? getImageUrl($product->desc_image) : null,
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
                'image' => $variant->image ? getImageUrl($variant->image) : null,
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
    public function relatedProducts($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }
        $category = Category::with(['children', 'children.children'])->where('id', $product->category_id)->first();
        if ($category) {
            $categoryIds = [$category->id];
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
            foreach ($category->children as $child) {
                $categoryIds = array_merge($categoryIds, $child->children->pluck('id')->toArray());
            }
        }
        $relatedProducts = Product::with(['category', 'brand'])
            ->where('status', true)
            ->whereIn('category_id', $categoryIds)
            ->where('id', '!=', $product->id)
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
                    'thumbnail' => $product->thumbnail ? getImageUrl($product->thumbnail) : null,
                    'images' => collect($product->image)->map(function ($image) {
                        return $image ? getImageUrl($image) : null;
                    }),
                ];
            });
        $data = [
            'products' => $relatedProducts,
        ];
        return ApiResponse::success('Related products fetched successfully', $data);
    }
}
