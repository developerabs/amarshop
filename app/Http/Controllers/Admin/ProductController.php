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
            'description' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'variant_name' => 'nullable|array',
            'variant_attributes' => 'nullable|array',
            'additional_cost' => 'nullable|array',
            'additional_price' => 'nullable|array',
            'stock' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validatedData = $validator->validated();
        // dd($validatedData);
        try {
            DB::beginTransaction();

            $product = Product::create([
                'code' => 'P' . time(),
                'admin_id' => auth()->id(),
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'category_id' => $validatedData['category'],
                'brand_id' => $validatedData['brand'],
                'price' => $validatedData['price'],
                'cost' => $validatedData['cost'] ?? 0,
                'wholesale_price' => $validatedData['wholesale_price'] ?? 0,
                'total_stock' => $validatedData['total_stock'],
                'description' => $validatedData['description'] ?? null,
                'meta_title' => $validatedData['meta_title'] ?? null,
                'meta_description' => $validatedData['meta_description'] ?? null,
                'thumbnail' => "null now",
            ]);

            foreach ($request->variant_attributes as $index => $attributeJson) {
                
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $request->variant_name[$index],
                    'sku' => "sdfsaf",
                    'additional_cost' => $request->additional_cost[$index],
                    'additional_price' => $request->additional_price[$index],
                    'stock' => $request->stock[$index],
                ]);
 
                $attributes = json_decode($attributeJson, true);

                foreach ($attributes as $attributeName => $attributeValue) {

                    ProductVariantValue::create([
                        'product_variant_id' => $variant->id,
                        'attribute_name' => $attributeName,
                        'attribute_value' => $attributeValue,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
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
        // Logic to delete the product
    }
}
