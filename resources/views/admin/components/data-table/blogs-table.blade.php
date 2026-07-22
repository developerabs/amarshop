<table class="table align-middle mb-0" id="blogsTable" data-searchable-table>
    <thead>
        <tr>
            <th></th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Published</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($blogs ?? collect()) as $blog)
        <tr data-items="{{ json_encode($blog) }}">
            <td><img src="{{ getImageUrl($blog->thumbnail) }}" class="img-thumbnail" width="40" height="30" alt=""></td>
            <td>{{ $blog->title ?? 'N/A' }}</td>
            <td>{{ $blog->category->name ?? 'N/A' }}</td>
            <td>
                @if($blog->status)
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-danger">Draft</span>
                @endif
            </td>
            <td>{{ $blog->published_at ? $blog->published_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-url="{{ route('admin.blogs.destroy', $blog->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No blogs found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $blogs->links() }}
