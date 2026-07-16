<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Banner;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.sections.banners.index');
    }
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);
        $searchTerm = $request->input('query');
        $banners = Banner::query()
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.components.data-table.banner-table', compact('banners', 'searchTerm'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $imagePath = uploadImage($request->file('image'), 'banners');
        } else {
            $imagePath = null;
        }
        Banner::create([
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);
        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'type' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $banner = Banner::findOrFail($request->input('id'));
        if ($request->hasFile('image')) {
            $imagePath = updateImage($request->file('image'), 'banners', $banner->image);
            $banner->image = $imagePath;
        }
        $banner->type = $request->input('type');
        $banner->title = $request->input('title');
        $banner->description = $request->input('description');
        $banner->save();
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
