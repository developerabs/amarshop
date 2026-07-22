<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function byLocation(string $location)
    {
        $locationKey = Str::slug($location);

        $menus = Menu::query()
            ->where('is_active', true)
            ->where('location', $locationKey)
            ->with(['menuItems' => function ($query) {
                $query->where('status', true)->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        $data = [
            'location' => $locationKey,
            'menus' => $menus->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'slug' => $menu->slug,
                    'location' => $menu->location,
                    'items' => $this->buildMenuTree($menu->menuItems),
                ];
            })->values(),
        ];

        return ApiResponse::success('Menus retrieved successfully', $data);
    }

    private function buildMenuTree(Collection $items, ?int $parentId = null): array
    {
        return $items
            ->where('parent_id', $parentId)
            ->values()
            ->map(function ($item) use ($items) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => $item->url,
                    'type' => $item->type,
                    'reference_id' => $item->reference_id,
                    'parent_id' => $item->parent_id,
                    'position' => $item->position,
                    'children' => $this->buildMenuTree($items, $item->id),
                ];
            })
            ->all();
    }
}
