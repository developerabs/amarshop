<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $parentCategories = Category::with('children', 'children.children')->where('level', 0)->orderBy('id', 'desc')->get();
        
        return view('admin.sections.categories.index', compact('parentCategories'));
    }
    public function search(Request $request)
    {
        $query = Category::query();

        if ($request->has('name') && !empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }

        $categories = $query->with('parent')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.components.data-table.categories-table', compact('categories'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_category' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();

        $parentCategory = null;
        if (!empty($validatedData['parent_category'])) {
            $parentCategory = Category::find($validatedData['parent_category']);
            if (!$parentCategory) {
                return redirect()->back()->with('error', 'Selected parent category does not exist.')->withInput();
            }
        }
        if ($parentCategory && $parentCategory->level >= 2) {
            return redirect()->back()->with('error', 'Cannot add a subcategory to a level 2 category.')->withInput();
        }
        if (isset($validatedData['image']) && $request->hasFile('image')) {
            $validatedData['image'] = uploadImage($request->file('image'), 'categories');
        }

        try {
            DB::beginTransaction();
            // Create the category
            $category = Category::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'parent_id' => $parentCategory ? $parentCategory->id : null,
                'level' => $parentCategory ? $parentCategory->level + 1 : 0,
                'image' => $validatedData['image'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'meta_title' => $validatedData['meta_title'] ?? null,
                'meta_description' => $validatedData['meta_description'] ?? null,
                'status' => $validatedData['status'] ?? false,
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($categoryId)
    {
        // Fetch the category by ID
        $parentCategories = Category::where('level', 0)->orderBy('id', 'desc')->get();
        $category = Category::findOrFail($categoryId);
        return view('admin.sections.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $categoryId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_category' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction();
            $category = Category::findOrFail($categoryId);
            $category->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'parent_id' => $validatedData['parent_category'] ?? null,
                'meta_title' => $validatedData['meta_title'] ?? null,
                'meta_description' => $validatedData['meta_description'] ?? null,
                'description' => $validatedData['description'] ?? null,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($categoryId)
    {
        try {
            DB::beginTransaction();
            $category = Category::findOrFail($categoryId);
            $category->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
