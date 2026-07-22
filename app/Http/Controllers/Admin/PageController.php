<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.sections.pages.index');
    }
    public function search(Request $request)
    {
        $pages = Page::query();
        if ($request->has('query')) {
            $pages->whereRaw('LOWER(name) like ?', ['%' . strtolower($request->input('query')) . '%']);
        }
        $pages = $pages->latest()->paginate(10);
        return view('admin.components.data-table.pages-table', ['pages' => $pages]);
    }

    public function create()
    {
        return view('admin.sections.pages.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permalink' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();

        if ($request->hasFile('banner')) {
            $validatedData['banner'] = uploadImage($request->file('banner'), 'banners');
        }
        Page::create([
            'type' => 'dynamic',
            'name' => $validatedData['name'],
            'permalink' => $validatedData['permalink'],
            'title' => $validatedData['title'],
            'content' => $validatedData['content'] ?? null,
            'banner' => $validatedData['banner'] ?? null,
            'meta_title' => $validatedData['meta_title'] ?? null,
            'meta_description' => $validatedData['meta_description'] ?? null,
            'meta_keywords' => $validatedData['meta_keywords'] ?? null,
            'status' => $validatedData['status'] ?? false,
        ]);
        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.sections.pages.edit', compact('page'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permalink' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();

        $page = Page::findOrFail($id);
        
        if ($request->hasFile('banner')) {
            $validatedData['banner'] = uploadImage($request->file('banner'), 'banners');
        }
        $page->update([
            'name' => $validatedData['name'],
            'permalink' => $validatedData['permalink'],
            'title' => $validatedData['title'],
            'content' => $validatedData['content'] ?? null,
            'banner' => $validatedData['banner'] ?? $page->banner,
            'meta_title' => $validatedData['meta_title'] ?? null,
            'meta_description' => $validatedData['meta_description'] ?? null,
            'meta_keywords' => $validatedData['meta_keywords'] ?? null,
            'status' => $validatedData['status'] ?? false,
        ]);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');   
    }
}
