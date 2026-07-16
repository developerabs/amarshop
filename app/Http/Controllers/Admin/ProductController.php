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
        return view('admin.sections.products.index');
    }
    public function search(Request $request)
    {
        $query = Product::with('category', 'brand');

        if ($request->has('query') && !empty($request->input('query'))) {
            $query->where('name', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('code', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('model', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('short_description', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('meta_title', 'like', '%' . $request->input('query') . '%')
                  ->orWhere('meta_description', 'like', '%' . $request->input('query') . '%')
                  ->orWhereHas('category', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->input('query') . '%');
                  })
                  ->orWhereHas('brand', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->input('query') . '%');
                  });
        }

        $products = $query->with(['category', 'brand'])->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.components.data-table.products-table', compact('products'));
    }
    public function create()
    {
        $categories = Category::with(['children' => function ($query) {
            $query->where('status', true)
            ->with(['children' => function ($query) {
                $query->where('status', true);
            }]);
        }])->where('status', true)->where('level', 0)->orderBy('id', 'desc')->get();
        $brands = Brand::where('status', true)->orderBy('id', 'desc')->get();
        return view('admin.sections.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'code' => 'required|string|max:50|unique:products,code',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'total_stock' => 'required|integer|min:0',
            'variant_name' => 'nullable|array',
            'variant_name.*' => 'nullable|string|max:255',
            'additional_cost.*' => 'nullable|numeric|min:0',
            'additional_price.*' => 'nullable|numeric|min:0',
            'stock.*' => 'nullable|integer|min:0',
            'variant_attributes' => 'nullable|array',
            'additional_cost' => 'nullable|array',
            'additional_price' => 'nullable|array',
            'stock' => 'nullable|array',
            'model' => 'nullable|string|max:100',
            'alert_quantity' => 'nullable|integer|min:0',

            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|in:inclusive,exclusive',

            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'desc_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',

            'has_variations' => 'nullable|boolean',
            'flash_deal' => 'nullable|boolean',
            'trending' => 'nullable|boolean',
            'daily_offer' => 'nullable|boolean',
            'best_deal' => 'nullable|boolean',
            'top_sale' => 'nullable|boolean',
            'new_arrivals' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validatedData = $validator->validated();
        
        if ($request->has('has_variations') && $request->has('variant_name') && $request->has('variant_attributes')) {
            if (count($request->variant_name) !== count($request->variant_attributes)) {
                return redirect()->back()->with('error', 'Variant names and attributes count mismatch.')->withInput();
            }
        }
        if ($validatedData['discount_amount'] && $validatedData['discount_amount'] > 0 && $validatedData['discount_type'] == null) {
            return redirect()->back()->with('error', 'Discount type must be specified when discount amount is provided.')->withInput();
        }
        if ($validatedData['tax_amount'] && $validatedData['tax_amount'] > 0 && $validatedData['tax_type'] == null) {
            return redirect()->back()->with('error', 'Tax type must be specified when tax amount is provided.')->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        $slug = $count ? "{$slug}-" . ($count + 1) : $slug;
        
        try {
            DB::transaction(function () use ($validatedData, $request, $slug, &$product) {
                $product = Product::create([
                    'code' => $validatedData['code'],
                    'admin_id' => auth()->id(),
                    'name' => $validatedData['name'],
                    'slug' => $slug,
                    'category_id' => $validatedData['category'],
                    'brand_id' => $validatedData['brand'],
                    'price' => $validatedData['price'],
                    'sale_price' => $validatedData['sale_price'] ?? null,
                    'cost' => $validatedData['cost'] ?? 0,
                    'wholesale_price' => $validatedData['wholesale_price'] ?? 0,
                    'total_stock' => $validatedData['total_stock'],
                    'model' => $validatedData['model'] ?? null,
                    'alert_quantity' => $validatedData['alert_quantity'] ?? 0,

                    'discount_amount' => $validatedData['discount_amount'] ?? null,
                    'discount_type' => $validatedData['discount_type'] ?? null,
                    'tax_rate' => $validatedData['tax_amount'] ?? null,
                    'tax_type' => $validatedData['tax_type'] ?? null,

                    'short_description' => $validatedData['short_description'] ?? null,
                    'description' => $validatedData['description'] ?? null,
                    'meta_title' => $validatedData['meta_title'] ?? null,
                    'meta_description' => $validatedData['meta_description'] ?? null,

                    'has_variants' => $validatedData['has_variations'] ?? 0,
                    'is_flash_deal' => $validatedData['flash_deal'] ?? 0,
                    'is_trending' => $validatedData['trending'] ?? 0,
                    'is_daily_offer' => $validatedData['daily_offer'] ?? 0,
                    'is_best_deal' => $validatedData['best_deal'] ?? 0,
                    'is_top_sale' => $validatedData['top_sale'] ?? 0,
                    'is_new_arrival' => $validatedData['new_arrivals'] ?? 0,
                    'status' => $validatedData['status'] ?? 0,
                ]);

                if ($request->filled('variant_attributes') && $validatedData['has_variations']) {
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
                $descImagePath = null;

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
                if ($request->hasFile('desc_image')) {
                    $descImagePath = uploadImage(
                        $request->file('desc_image'),
                        'products'
                    );
                }
                $product->update([
                    'thumbnail' => $thumbnailPath,
                    'image' => $imagePaths,
                    'desc_image' => $descImagePath
                ]);
            } catch (\Exception $e) {}

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while creating the product.')->withInput();
        }
    }

    public function edit($product)
    {
        $categories = Category::with(['children' => function ($query) {
            $query->where('status', true)
            ->with(['children' => function ($query) {
                $query->where('status', true);
            }]);
        }])->where('status', true)->where('level', 0)->orderBy('id', 'desc')->get();
        $brands = Brand::where('status', true)->orderBy('id', 'desc')->get();
        $product = Product::with(['variants', 'variants.variantValues', 'category', 'brand'])->findOrFail($product);

        $variationGroups = [];

        if ($product->has_variants) {
            foreach ($product->variants as $variant) {
                foreach ($variant->variantValues as $value) {

                    $variationGroups[$value->attribute_name][] = $value->attribute_value;
                }
            }

            foreach ($variationGroups as $attribute => $values) {
                $variationGroups[$attribute] = array_unique($values);
            }
        }
        return view('admin.sections.products.edit', compact('categories', 'brands', 'product', 'variationGroups'));
    }

    public function update(Request $request, $product)
    {
        $product = Product::findOrFail($product);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.')->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'total_stock' => 'required|integer|min:0',
            'variant_name' => 'nullable|array',
            'variant_name.*' => 'nullable|string|max:255',
            'additional_cost.*' => 'nullable|numeric|min:0',
            'additional_price.*' => 'nullable|numeric|min:0',
            'stock.*' => 'nullable|integer|min:0',
            'variant_attributes' => 'nullable|array',
            'additional_cost' => 'nullable|array',
            'additional_price' => 'nullable|array',
            'stock' => 'nullable|array',
            'model' => 'nullable|string|max:100',
            'alert_quantity' => 'nullable|integer|min:0',

            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|in:inclusive,exclusive',

            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'desc_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',

            'has_variations' => 'nullable|boolean',
            'flash_deal' => 'nullable|boolean',
            'trending' => 'nullable|boolean',
            'daily_offer' => 'nullable|boolean',
            'best_deal' => 'nullable|boolean',
            'top_sale' => 'nullable|boolean',
            'new_arrivals' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();
        
        if ($request->has('has_variations') && $request->has('variant_name') && $request->has('variant_attributes')) {
            if (count($request->variant_name) !== count($request->variant_attributes)) {
                return redirect()->back()->with('error', 'Variant names and attributes count mismatch.')->withInput();
            }
        }
        if ($validatedData['discount_amount'] && $validatedData['discount_amount'] > 0 && $validatedData['discount_type'] == null) {
            return redirect()->back()->with('error', 'Discount type must be specified when discount amount is provided.')->withInput();
        }
        if ($validatedData['tax_amount'] && $validatedData['tax_amount'] > 0 && $validatedData['tax_type'] == null) {
            return redirect()->back()->with('error', 'Tax type must be specified when tax amount is provided.')->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        $slug = $count ? "{$slug}-" . ($count + 1) : $slug;
        
        try {
            DB::transaction(function () use ($validatedData, $request, $slug, $product) {
                $product->update([
                    'code' => $validatedData['code'],
                    'name' => $validatedData['name'],
                    'slug' => $slug,
                    'category_id' => $validatedData['category'],
                    'brand_id' => $validatedData['brand'],
                    'price' => $validatedData['price'],
                    'sale_price' => $validatedData['sale_price'] ?? null,
                    'cost' => $validatedData['cost'] ?? 0,
                    'wholesale_price' => $validatedData['wholesale_price'] ?? 0,
                    'total_stock' => $validatedData['total_stock'],
                    'model' => $validatedData['model'] ?? null,
                    'alert_quantity' => $validatedData['alert_quantity'] ?? 0,

                    'discount_amount' => $validatedData['discount_amount'] ?? null,
                    'discount_type' => $validatedData['discount_type'] ?? null,
                    'tax_rate' => $validatedData['tax_amount'] ?? null,
                    'tax_type' => $validatedData['tax_type'] ?? null,

                    'short_description' => $validatedData['short_description'],
                    'description' => $validatedData['description'],
                    'meta_title' => $validatedData['meta_title'],
                    'meta_description' => $validatedData['meta_description'],

                    'has_variants' => $validatedData['has_variations'] ?? 0,
                    'is_flash_deal' => $validatedData['flash_deal'] ?? 0,
                    'is_trending' => $validatedData['trending'] ?? 0,
                    'is_daily_offer' => $validatedData['daily_offer'] ?? 0,
                    'is_best_deal' => $validatedData['best_deal'] ?? 0,
                    'is_top_sale' => $validatedData['top_sale'] ?? 0,
                    'is_new_arrival' => $validatedData['new_arrivals'] ?? 0,
                    'status' => $validatedData['status'] ?? 0,
                ]);

                if ($request->filled('variant_attributes') && $validatedData['has_variations']) {
                    $product->variants()->delete();

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
                if ($request->hasFile('thumbnail')) {
                    if ($product->thumbnail) {
                        deleteImage($product->thumbnail);
                    }
                    $thumbnailPath = uploadImage(
                        $request->file('thumbnail'),
                        'products'
                    );
                    $product->update(['thumbnail' => $thumbnailPath]);
                }
                if ($request->hasFile('image')) {
                    if ($product->image) {
                        foreach ($product->image as $oldImage) {
                            deleteImage($oldImage);
                        }
                    }
                    $imagePaths = [];
                    foreach ($request->file('image') as $image) {
                        $imagePaths[] = uploadImage(
                            $image,
                            'products'
                        );
                    }
                    $product->update(['image' => $imagePaths]);
                }
                if ($request->hasFile('desc_image')) {
                    if ($product->desc_image) {
                        deleteImage($product->desc_image);
                    }
                    $descImagePath = uploadImage(
                        $request->file('desc_image'),
                        'products'
                    );
                    $product->update(['desc_image' => $descImagePath]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'An error occurred while updating the product images.')->withInput();
            }
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while updating the product.')->withInput();
        }
    }

    public function destroy($product)
    {
        $product = Product::findOrFail($product);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
