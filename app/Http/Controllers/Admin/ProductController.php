<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\ProductVariantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('children', 'children.children')->where('level', 0)->orderBy('id', 'desc')->get();
        $brands = Brand::all();
        return view('admin.sections.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'total_stock' => 'required|integer|min:0',
            'variant_name' => 'nullable|array',
            'variant_name.*' => 'required|string|max:255',
            'additional_cost.*' => 'nullable|numeric|min:0',
            'additional_price.*' => 'nullable|numeric|min:0',
            'stock.*' => 'nullable|integer|min:0',
            'variant_attributes' => 'nullable|array',
            'additional_cost' => 'nullable|array',
            'additional_price' => 'nullable|array',
            'stock' => 'nullable|array',

            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',

            'has_variation' => 'nullable|boolean',
            'flash_deal' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validatedData = $validator->validated();
        
        if ($request->has('has_variation') && $request->has('variant_name') && $request->has('variant_attributes')) {
            if (count($request->variant_name) !== count($request->variant_attributes)) {
                return redirect()->back()->with('error', 'Variant names and attributes count mismatch.')->withInput();
            }
        }
        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        $slug = $count ? "{$slug}-" . ($count + 1) : $slug;

        try {
            DB::transaction(function () use ($validatedData, $request, $slug, &$product) {
                $product = Product::create([
                    'code' => 'P-' . Str::uuid(),
                    'admin_id' => auth()->id(),
                    'name' => $validatedData['name'],
                    'slug' => $slug,
                    'category_id' => $validatedData['category'],
                    'brand_id' => $validatedData['brand'],
                    'price' => $validatedData['price'],
                    'cost' => $validatedData['cost'] ?? 0,
                    'wholesale_price' => $validatedData['wholesale_price'] ?? 0,
                    'total_stock' => $validatedData['total_stock'],

                    'thumbnail' => $validatedData['thumbnail'] ?? null,
                    'image' => $validatedData['image'] ?? null,
                    'short_description' => $validatedData['short_description'] ?? null,
                    'description' => $validatedData['description'] ?? null,
                    'meta_title' => $validatedData['meta_title'] ?? null,
                    'meta_description' => $validatedData['meta_description'] ?? null,

                    'has_variants' => $validatedData['has_variation'] ?? 0,
                    'is_flash_deal' => $validatedData['flash_deal'] ?? 0,
                    'status' => $validatedData['status'] ?? 0,
                ]);

                if ($request->filled('variant_attributes')) {
                    foreach ($request->variant_attributes as $index => $attributeJson) {
                        
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $request->variant_name[$index],
                            'sku' => $request->variant_name[$index],
                            'additional_cost' => $request->additional_cost[$index],
                            'additional_price' => $request->additional_price[$index],
                            'stock' => $request->stock[$index],
                        ]);
        
                        $attributes = json_decode($attributeJson, true);
                        $variantValues = [];
                        foreach ($attributes as $attributeName => $attributeValue) {
                            $variantValues[] = [
                                'product_variant_id' => $variant->id,
                                'attribute_name' => $attributeName,
                                'attribute_value' => $attributeValue,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        ProductVariantValue::insert($variantValues);
                    }
                }
            });

            try {
                $thumbnailPath = null;
                $imagePaths = [];

                if ($request->hasFile('thumbnail')) {
                    $thumbnailPath = uploadImage(
                        $request->file('thumbnail'),
                        'products'
                    );
                }
                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $image) {

                        $imagePaths[] = uploadImage(
                            $image,
                            'products'
                        );
                    }
                }
                $product->update([
                    'thumbnail' => $thumbnailPath,
                    'image' => $imagePaths
                ]);
            } catch (\Exception $e) {}

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the product.')->withInput();
        }
    }

    public function edit($product)
    {
        // Logic to display the product edit form
    }

    public function update(Request $request, $product)
    {
        // Logic to update the product
    }

    public function destroy($product)
    {
        $product = Product::findOrFail($product);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
