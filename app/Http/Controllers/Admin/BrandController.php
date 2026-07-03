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
        $brands = Brand::orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.brands.index', compact('brands'));
    }
    public function create()
    {
        return view('admin.sections.brands.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction();
            $brand = Brand::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'meta_title' => $validatedData['meta_title'],
                'meta_keywords' => $validatedData['meta_keywords'],
                'meta_description' => $validatedData['meta_description'],
                'status' => true, // Default status
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
    public function update(Request $request, $brandId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction();
            $brand = Brand::findOrFail($brandId);
            $brand->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'meta_title' => $validatedData['meta_title'],
                'meta_keywords' => $validatedData['meta_keywords'],
                'meta_description' => $validatedData['meta_description'],
                'status' => $request->has('status') ? true : false,
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
