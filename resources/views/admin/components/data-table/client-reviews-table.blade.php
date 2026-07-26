<table class="table align-middle mb-0" data-searchable-table>
    <thead>
        <tr>
            <th></th>
            <th>Title</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($clientReviews ?? collect()) as $clientReview)
        <tr data-items='@json($clientReview)'>
            <td><img src="{{ getImageUrl($clientReview->image) }}" class="img-thumbnail" width="48" height="36" alt=""></td>
            <td>{{ $clientReview->title ?? 'N/A' }}</td>
            <td>{{ number_format((float) $clientReview->rating, 1) }}/5</td>
            <td>
                @if($clientReview->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $clientReview->created_at ? $clientReview->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-url="{{ route('admin.client-reviews.destroy', $clientReview->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No client reviews found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $clientReviews->links() }}
