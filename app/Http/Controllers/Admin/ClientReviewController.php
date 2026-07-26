<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClientReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientReviewController extends Controller
{
    public function index()
    {
        return view('admin.sections.client-reviews.index');
    }

    public function search(Request $request)
    {
        $query = ClientReview::query();

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                    ->orWhere('video_link', 'like', '%' . $term . '%');
            });
        }

        $clientReviews = $query->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.client-reviews-table', compact('clientReviews'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'rating' => 'required|numeric|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'video_link' => 'nullable|url|max:1000',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'addModal');
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = uploadImage($request->file('image'), 'client-reviews');
        }

        DB::transaction(function () use ($validated) {
            ClientReview::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'rating' => $validated['rating'],
                'image' => $validated['image'] ?? null,
                'video_link' => $validated['video_link'] ?? null,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.client-reviews.index')->with('success', 'Client review created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_review_id' => 'required|exists:client_reviews,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'rating' => 'required|numeric|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'video_link' => 'nullable|url|max:1000',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'editModal');
        }

        $validated = $validator->validated();
        $clientReview = ClientReview::findOrFail($validated['client_review_id']);

        if ($request->hasFile('image')) {
            $validated['image'] = updateImage($request->file('image'), 'client-reviews', $clientReview->image);
        }

        DB::transaction(function () use ($validated, $clientReview) {
            $clientReview->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'rating' => $validated['rating'],
                'image' => $validated['image'] ?? $clientReview->image,
                'video_link' => $validated['video_link'] ?? null,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.client-reviews.index')->with('success', 'Client review updated successfully.');
    }

    public function destroy($id)
    {
        $clientReview = ClientReview::findOrFail($id);

        if (!empty($clientReview->image)) {
            deleteImage($clientReview->image);
        }

        $clientReview->delete();

        return redirect()->route('admin.client-reviews.index')->with('success', 'Client review deleted successfully.');
    }
}
