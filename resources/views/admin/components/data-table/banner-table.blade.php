<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($banners ?? [] as $banner)
        <tr data-items="{{ json_encode($banner) }}">
            <td><img src="{{ getImageUrl($banner->image) ?? "" }}" class="img-thumbnail" width="40" height="30"></td>
            <td>{{ $banner->created_at ?$banner->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $banner->id }}" data-url="{{ route('admin.banners.destroy', $banner->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">No banners found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $banners->links() }}