<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
    <thead>
        <tr>
            <th>SL</th>
            <th>Name</th>
            <th>Variant Type</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($variantValues ?? [] as $variantValue)
        <tr data-items="{{ json_encode($variantValue) }}">
            <td>{{ $variantValues->firstItem() + $loop->index }}</td>
            <td>{{ $variantValue->name ?? 'N/A' }}</td>
            <td>{{ $variantValue->variantType->name ?? 'N/A' }}</td>
            <td>{{ $variantValue->created_at ? $variantValue->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $variantValue->id }}" data-url="{{ route('admin.variant-values.destroy', $variantValue->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No variant values found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $variantValues->links() }}