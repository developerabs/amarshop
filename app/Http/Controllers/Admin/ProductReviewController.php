<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    public function index()
    {
        return view('admin.sections.product-reviews.index');
    }

    public function search(Request $request)
    {
        $query = ProductReview::with(['product:id,name', 'user:id,name,email']);

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('review', 'ilike', '%' . $term . '%')
                    ->orWhereHas('product', function ($productQuery) use ($term) {
                        $productQuery->where('name', 'ilike', '%' . $term . '%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'ilike', '%' . $term . '%')
                            ->orWhere('email', 'ilike', '%' . $term . '%');
                    });
            });
        }

        $productReviews = $query->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.product-reviews-table', compact('productReviews'));
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|exists:product_ratings,id',
            'is_approved' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $validated = $validator->validated();
        $review = ProductReview::findOrFail($validated['review_id']);

        DB::transaction(function () use ($review, $validated) {
            $isApproved = (bool) $validated['is_approved'];

            $review->update([
                'is_approved' => $isApproved,
                'approved_at' => $isApproved ? now() : null,
            ]);
        });

        return redirect()->route('admin.product-reviews.index')->with('success', 'Review status updated successfully.');
    }
}
