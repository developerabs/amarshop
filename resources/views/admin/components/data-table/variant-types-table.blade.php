<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
    <thead>
        <tr>
            <th>SL</th>
            <th>Name</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($variantTypes ?? [] as $variantType)
        <tr data-items="{{ json_encode($variantType) }}">
            <td>{{ $variantTypes->firstItem() + $loop->index }}</td>
            <td>{{ $variantType->name ?? 'N/A' }}</td>
            <td>{{ $variantType->created_at ? $variantType->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $variantType->id }}" data-url="{{ route('admin.variant-types.destroy', $variantType->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No variant types found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $variantTypes->links() }}