<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories ?? [] as $category)
        <tr>
            <td><img src="{{ getImageUrl($category->image) ?? "" }}" class="img-thumbnail" width="80" height="40"></td>
            <td>
                @php $level = (int) ($category->level ?? 0); @endphp
                @if($level > 0)
                    <span>{{ str_repeat('--', $level) }}</span>
                @endif
                {{ $category->name ?? 'N/A' }}
            </td>
            <td>
                @if ($category->parent)
                    @php $level = (int) ($category->parent->level ?? 0); @endphp
                    @if($level > 0)
                        <span>{{ str_repeat('--', $level) }}</span>
                    @endif
                    {{ $category->parent->name }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($category->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $category->created_at ?$category->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <a class="btn btn-warning btn-sm" href="{{ route('admin.categories.edit', $category->id) }}"><i class="bi bi-pencil" aria-hidden="true"></i></a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash" aria-hidden="true"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No categories found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $categories->links() }}