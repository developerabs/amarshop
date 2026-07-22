<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogCategories = BlogCategory::where('status', true)->latest()->get();

        return view('admin.sections.blogs.index', compact('blogCategories'));
    }

    public function search(Request $request)
    {
        $query = Blog::with('category');

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                    ->orWhere('slug', 'like', '%' . $term . '%')
                    ->orWhere('excerpt', 'like', '%' . $term . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($term) {
                        $categoryQuery->where('name', 'like', '%' . $term . '%');
                    });
            });
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.blogs-table', compact('blogs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'published_at' => 'nullable|date',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'addModal');
        }

        $validated = $validator->validated();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = uploadImage($request->file('thumbnail'), 'blogs');
        }

        DB::transaction(function () use ($validated) {
            Blog::create([
                'blog_category_id' => $validated['blog_category_id'],
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . Str::lower(Str::random(5)),
                'thumbnail' => $validated['thumbnail'] ?? null,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'published_at' => $validated['published_at'] ?? now(),
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'published_at' => 'nullable|date',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'editModal');
        }

        $validated = $validator->validated();
        $blog = Blog::findOrFail($validated['blog_id']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = updateImage($request->file('thumbnail'), 'blogs', $blog->thumbnail);
        }

        DB::transaction(function () use ($validated, $blog) {
            $blog->update([
                'blog_category_id' => $validated['blog_category_id'],
                'title' => $validated['title'],
                'slug' => $blog->title !== $validated['title']
                    ? Str::slug($validated['title']) . '-' . Str::lower(Str::random(5))
                    : $blog->slug,
                'thumbnail' => $validated['thumbnail'] ?? $blog->thumbnail,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'published_at' => $validated['published_at'] ?? $blog->published_at,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if (!empty($blog->thumbnail)) {
            deleteImage($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
