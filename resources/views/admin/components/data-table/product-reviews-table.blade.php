<table class="table align-middle mb-0" data-searchable-table>
    <thead>
        <tr>
            <th>Product</th>
            <th>User</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($productReviews ?? collect()) as $productReview)
        <tr>
            <td>{{ $productReview->product->name ?? 'N/A' }}</td>
            <td>
                <div>{{ $productReview->user->name ?? 'N/A' }}</div>
                <small class="text-muted">{{ $productReview->user->email ?? '' }}</small>
            </td>
            <td>{{ $productReview->rating }}/5</td>
            <td>{{ $productReview->review ?? 'N/A' }}</td>
            <td>
                @if($productReview->is_approved)
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-warning text-dark">Pending</span>
                @endif
            </td>
            <td>{{ $productReview->created_at ? $productReview->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <form method="POST" action="{{ route('admin.product-reviews.update-status') }}" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="review_id" value="{{ $productReview->id }}">
                    <input type="hidden" name="is_approved" value="{{ $productReview->is_approved ? 0 : 1 }}">
                    <button class="btn btn-sm {{ $productReview->is_approved ? 'btn-secondary' : 'btn-success' }}" type="submit">
                        {{ $productReview->is_approved ? 'Unapprove' : 'Approve' }}
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No product reviews found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $productReviews->links() }}
