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

        $categories = Category::with('children', 'children.children')->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
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
        $brands = Brand::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
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
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereIn('category_id', function ($subQuery) use ($searchTerm) {
                        $subQuery->select('id')
                            ->from('categories')
                            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                    })
                    ->orWhereIn('brand_id', function ($subQuery) use ($searchTerm) {
                        $subQuery->select('id')
                            ->from('brands')
                            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                    })
                    ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(model) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(short_description) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(meta_title) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(meta_description) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
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
                    'thumbnail' => getImageUrl($product->thumbnail),
                    'images' => collect($product->image)->map(function ($image) {
                        return getImageUrl($image);
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
                    'image' => getImageUrl($slider->image),
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
                    'image' => getImageUrl($banner->image),
                ];
            });

        return ApiResponse::success(
            'Banners fetched successfully',
            ['banners' => $banners]
        );
    }
}
