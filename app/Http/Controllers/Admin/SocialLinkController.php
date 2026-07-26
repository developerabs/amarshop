<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SocialLinkController extends Controller
{
    public function index()
    {
        return view('admin.sections.social-links.index');
    }

    public function search(Request $request)
    {
        $query = SocialLink::query();

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('url', 'like', '%' . $term . '%')
                    ->orWhere('icon', 'like', '%' . $term . '%');
            });
        }

        $socialLinks = $query->orderBy('sort_order')->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.social-links-table', compact('socialLinks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'addModal');
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($validated) {
            SocialLink::create([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'icon' => $validated['icon'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.social-links.index')->with('success', 'Social link created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_link_id' => 'required|exists:social_links,id',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'editModal');
        }

        $validated = $validator->validated();
        $socialLink = SocialLink::findOrFail($validated['social_link_id']);

        DB::transaction(function () use ($validated, $socialLink) {
            $socialLink->update([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'icon' => $validated['icon'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.social-links.index')->with('success', 'Social link updated successfully.');
    }

    public function destroy($id)
    {
        $socialLink = SocialLink::findOrFail($id);
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')->with('success', 'Social link deleted successfully.');
    }
}
