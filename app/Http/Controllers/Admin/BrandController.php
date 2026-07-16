<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.sections.brands.index', compact('brands'));
    }
    public function search(Request $request)
    {
        $query = Brand::query();

        if ($request->has('query') && !empty($request->input('query'))) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }

        $brands = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.components.data-table.brands-table', compact('brands'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','addModal');
        }

        $validatedData = $validator->validated();

        if (isset($validatedData['image']) && $request->hasFile('image')) {
            $validatedData['image'] = uploadImage($request->file('image'), 'brands');
        }

        try {
            DB::beginTransaction();
            $brand = Brand::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'image' => $validatedData['image'] ?? null,
                'meta_title' => $validatedData['meta_title'],
                'meta_description' => $validatedData['meta_description'],
                'status' => $validatedData['status'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create brand: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }
    public function edit($brandId)
    {
        // Fetch the brand by ID
        $brand = Brand::findOrFail($brandId);
        return view('admin.sections.brands.edit', compact('brand'));
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','editModal');
        }

        $validatedData = $validator->validated();

        $brand = Brand::findOrFail($validatedData['brand_id']);
        if (isset($validatedData['image']) && $request->hasFile('image')) {
            $validatedData['image'] = updateImage($request->file('image'), 'brands', $brand->image);
        }

        try {
            DB::beginTransaction();
            $brand->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'meta_title' => $validatedData['meta_title'],
                'meta_description' => $validatedData['meta_description'],
                'image' => $validatedData['image'] ?? $brand->image,
                'status' => $validatedData['status'] ?? $brand->status,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update brand: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }
    public function destroy($brandId)
    {
        try {
            DB::beginTransaction();
            $brand = Brand::findOrFail($brandId);
            $brand->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete brand: ' . $e->getMessage());
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

}
