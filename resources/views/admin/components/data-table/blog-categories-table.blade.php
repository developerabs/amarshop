<table class="table align-middle mb-0" id="blogCategoriesTable" data-searchable-table>
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($blogCategories ?? collect()) as $blogCategory)
        <tr data-items="{{ json_encode($blogCategory) }}">
            <td><img src="{{ getImageUrl($blogCategory->image) }}" class="img-thumbnail" width="40" height="30" alt=""></td>
            <td>{{ $blogCategory->name ?? 'N/A' }}</td>
            <td>
                @if($blogCategory->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $blogCategory->created_at ? $blogCategory->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-url="{{ route('admin.blog-categories.destroy', $blogCategory->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No blog categories found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $blogCategories->links() }}
