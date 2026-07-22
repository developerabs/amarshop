<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return view('admin.sections.blog-categories.index');
    }

    public function search(Request $request)
    {
        $query = BlogCategory::query();

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                    ->orWhere('meta_title', 'like', '%' . $term . '%');
            });
        }

        $blogCategories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.blog-categories-table', compact('blogCategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'description' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'addModal');
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = uploadImage($request->file('image'), 'blog-categories');
        }

        DB::transaction(function () use ($validated) {
            BlogCategory::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . Str::lower(Str::random(5)),
                'image' => $validated['image'] ?? null,
                'description' => $validated['description'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'description' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'editModal');
        }

        $validated = $validator->validated();
        $blogCategory = BlogCategory::findOrFail($validated['blog_category_id']);

        if ($request->hasFile('image')) {
            $validated['image'] = updateImage($request->file('image'), 'blog-categories', $blogCategory->image);
        }

        DB::transaction(function () use ($validated, $blogCategory) {
            $blogCategory->update([
                'name' => $validated['name'],
                'slug' => $blogCategory->name !== $validated['name']
                    ? Str::slug($validated['name']) . '-' . Str::lower(Str::random(5))
                    : $blogCategory->slug,
                'image' => $validated['image'] ?? $blogCategory->image,
                'description' => $validated['description'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category updated successfully.');
    }

    public function destroy($id)
    {
        $blogCategory = BlogCategory::withCount('blogs')->findOrFail($id);

        if ($blogCategory->blogs_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete this category because blogs are assigned to it.');
        }

        if (!empty($blogCategory->image)) {
            deleteImage($blogCategory->image);
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category deleted successfully.');
    }
}
