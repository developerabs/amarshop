<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Menu;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        return view('admin.sections.menus.index');
    }

    public function search(Request $request)
    {
        $menus = Menu::query();
        if ($request->has('query')) {
            $menus->whereRaw('LOWER(name) like ?', ['%' . strtolower($request->input('query')) . '%']);
        }
        $menus = $menus->latest()->paginate(10);
        return view('admin.components.data-table.menu-table', ['menus' => $menus]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'location' => 'required|string|max:255',
        ]);

        Menu::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'location' => Str::slug($request->input('location')),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }
    public function update(Request $request)
    {
        $id = $request->input('menu_id');
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $menu = Menu::findOrFail($id);
        $data = [
            'name' => $request->input('name'),
            'location' => Str::slug($request->input('location')),
        ];
        
        if ($menu->name !== $request->input('name')) {
            $data['slug'] = Str::slug($request->input('name'));
        }
        
        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    // menu items management
    public function getMenuItems($menuId)
    {
        $menu = Menu::findOrFail($menuId);
        $menuItems = $menu->items()->with('children')->get();

        $pages = Page::where('status', true)->get(['id', 'name', 'permalink']);
        $categories = Category::where('status', true)->get(['id', 'name', 'slug']);
        $brands = Brand::where('status', true)->get(['id', 'name', 'slug']);
        
        return view('admin.sections.menus.items.index', compact('menu', 'menuItems', 'pages', 'categories', 'brands'));
    }
}
