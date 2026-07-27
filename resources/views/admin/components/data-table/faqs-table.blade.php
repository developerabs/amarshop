<table class="table align-middle mb-0" data-searchable-table>
    <thead>
        <tr>
            <th>Question</th>
            <th>Sort</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($faqs ?? collect()) as $faq)
        <tr data-items='@json($faq)'>
            <td>{{ substr($faq->question, 0, 50) . (strlen($faq->question) > 50 ? '...' : '') ?? 'N/A' }}</td>
            <td>{{ $faq->sort_order ?? 0 }}</td>
            <td>
                @if($faq->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $faq->created_at ? $faq->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-url="{{ route('admin.faqs.destroy', $faq->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No FAQs found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $faqs->links() }}
