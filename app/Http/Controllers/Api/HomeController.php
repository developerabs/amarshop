<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Banner;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Slider;
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
            'best-deal' => 'is_best_deal',
            'top-sale' => 'is_top_sale',
            'new-arrivals' => 'is_new_arrival',
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
                    'thumbnail' => $product->thumbnail ? getImageUrl($product->thumbnail) : null,
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
    public function searchAll(Request $request)
    {
        $searchTerm = $request->input('search');

        $categories = Category::with('children', 'children.children')->where('name', 'like', '%' . $searchTerm . '%')
            ->where('status', true)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image ? getImageUrl($category->image) : null,
                ];
            });
        $brands = Brand::where('name', 'like', '%' . $searchTerm . '%')
            ->where('status', true)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug,
                    'image' => $brand->image ? getImageUrl($brand->image) : null,
                ];
            });
        $products = Product::with(['category', 'brand'])
            ->where('status', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereIn('category_id', function ($subQuery) use ($searchTerm) {
                        $subQuery->select('id')
                            ->from('categories')
                            ->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereIn('brand_id', function ($subQuery) use ($searchTerm) {
                        $subQuery->select('id')
                            ->from('brands')
                            ->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                    ->orWhere('code', 'like', '%' . $searchTerm . '%')
                    ->orWhere('model', 'like', '%' . $searchTerm . '%')
                    ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('meta_title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('meta_description', 'like', '%' . $searchTerm . '%');
            })
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
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ];
        return ApiResponse::success(
            'Search results fetched successfully',
            $data
        );
    }
    public function sliders()
    {
        $sliders = Slider::latest()
            ->take(10)
            ->get()
            ->map(function ($slider) {
                return [
                    'id' => $slider->id,
                    'title' => $slider->title,
                    'description' => $slider->description,
                    'image' => $slider->image ? getImageUrl($slider->image) : null,
                    'button_text' => $slider->button_text,
                    'button_link' => $slider->button_link,
                ];
            });

        return ApiResponse::success(
            'Sliders fetched successfully',
            ['sliders' => $sliders]
        );
    }
    public function banners()
    {
        $banners = Banner::latest()
            ->take(10)
            ->get()
            ->map(function ($banner) {
                return [
                    'image' => $banner->image ? getImageUrl($banner->image) : null,
                ];
            });

        return ApiResponse::success(
            'Banners fetched successfully',
            ['banners' => $banners]
        );
    }
}
