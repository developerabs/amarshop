<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Menu;
use App\Models\Admin\MenuItem;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255|unique:menus,location',
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
        $menu = Menu::findOrFail($id);
        $locationRule = Str::slug($request->input('location')) === $menu->location 
            ? 'required|string|max:255' 
            : 'required|string|max:255|unique:menus,location';
        
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => $locationRule,
        ]);

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
        $menuItems = $menu->menuItems()->get();
        $menuItemsData = $menuItems->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'reference_id' => $item->reference_id,
                'title' => $item->title,
                'url' => $item->url,
            ];
        })->values();

        $pages = Page::where('status', true)->get(['id', 'name', 'permalink']);
        $categories = Category::where('status', true)->get(['id', 'name', 'slug']);
        $brands = Brand::where('status', true)->get(['id', 'name', 'slug']);
        
        return view('admin.sections.menus.items.index', compact('menu', 'menuItems', 'menuItemsData', 'pages', 'categories', 'brands'));
    }

    public function storeMenuItem(Request $request, $menuId)
    {
        $menu = Menu::findOrFail($menuId);

        $validated = $request->validate([
            'type' => 'required|in:page,category,brand,custom',
            'reference_id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
        ]);

        $resolved = $this->resolveMenuItemData(
            $validated['type'],
            $validated['reference_id'] ?? null,
            $validated['title'] ?? null,
            $validated['url'] ?? null
        );

        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => $resolved['title'],
            'type' => $validated['type'],
            'reference_id' => $validated['reference_id'] ?? null,
            'url' => $resolved['url'],
            'parent_id' => null,
            'position' => (int) MenuItem::where('menu_id', $menu->id)->max('position') + 1,
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Menu item added successfully.',
            'item' => $menuItem,
        ]);
    }

    public function updateMenuItem(Request $request, $menuId, $itemId)
    {
        $menu = Menu::findOrFail($menuId);
        $menuItem = MenuItem::where('menu_id', $menu->id)->findOrFail($itemId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $menuItem->update([
            'title' => $validated['title'],
            'url' => $validated['url'] ?? null,
            'status' => (bool) ($validated['status'] ?? true),
        ]);

        return response()->json([
            'message' => 'Menu item updated successfully.',
            'item' => $menuItem->fresh(),
        ]);
    }

    public function destroyMenuItem($menuId, $itemId)
    {
        $menu = Menu::findOrFail($menuId);
        $menuItem = MenuItem::where('menu_id', $menu->id)->findOrFail($itemId);
        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully.',
        ]);
    }

    public function reorderMenuItems(Request $request, $menuId)
    {
        $menu = Menu::findOrFail($menuId);

        $validated = $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'integer',
        ]);

        $itemIds = $validated['item_ids'];

        $ownedItemCount = MenuItem::where('menu_id', $menu->id)
            ->whereIn('id', $itemIds)
            ->count();

        if ($ownedItemCount !== count($itemIds)) {
            return response()->json([
                'message' => 'Some items are invalid for this menu.',
            ], 422);
        }

        DB::transaction(function () use ($menu, $itemIds) {
            foreach ($itemIds as $index => $itemId) {
                MenuItem::where('menu_id', $menu->id)
                    ->where('id', $itemId)
                    ->update(['position' => $index + 1]);
            }
        });

        return response()->json([
            'message' => 'Menu item order updated successfully.',
        ]);
    }

    private function resolveMenuItemData(string $type, ?int $referenceId, ?string $title, ?string $url): array
    {
        if ($type === 'page') {
            $page = Page::where('status', true)->findOrFail($referenceId);

            return [
                'title' => $title ?: $page->name,
                'url' => $url ?: ('/' . ltrim((string) $page->permalink, '/')),
            ];
        }

        if ($type === 'category') {
            $category = Category::where('status', true)->findOrFail($referenceId);

            return [
                'title' => $title ?: $category->name,
                'url' => $url ?: ('/category/' . $category->slug),
            ];
        }

        if ($type === 'brand') {
            $brand = Brand::where('status', true)->findOrFail($referenceId);

            return [
                'title' => $title ?: $brand->name,
                'url' => $url ?: ('/brand/' . $brand->slug),
            ];
        }

        return [
            'title' => $title ?: 'Custom Item',
            'url' => $url ?: '#',
        ];
    }
}
