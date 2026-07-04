<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
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
        @forelse($brands ?? [] as $brand)
        <tr data-items="{{ json_encode($brand) }}">
            <td><img src="{{ getImageUrl($brand->image) ?? "" }}" class="img-thumbnail" width="80" height="40"></td>
            <td>{{ $brand->name ?? 'N/A' }}</td>
            <td>
                @if($brand->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $brand->created_at ?$brand->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $brand->id }}" data-url="{{ route('admin.brands.destroy', $brand->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No brands found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $brands->links() }}