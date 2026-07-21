<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pages ?? [] as $page)
        <tr data-items="{{ json_encode($page) }}">
            <td>{{ $page->name ?? 'N/A' }}</td>
            <td>{{ $page->type ?? 'N/A' }}</td>
            <td>
                @if($page->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $page->created_at ? $page->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $page->id }}" data-url="{{ route('admin.pages.destroy', $page->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No pages found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $pages->links() }}