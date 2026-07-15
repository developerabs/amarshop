<table class="table align-middle mb-0" id="shippingChargesTable" data-searchable-table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Charge</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($shippingCharges ?? [] as $shippingCharge)
        <tr data-items="{{ json_encode($shippingCharge) }}">
            <td>{{ $shippingCharge->name ?? 'N/A' }}</td>
            <td>{{ $shippingCharge->charge ?? 'N/A' }}</td>
            <td>{{ $shippingCharge->created_at ? $shippingCharge->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $shippingCharge->id }}" data-url="{{ route('admin.shipping-charges.destroy', $shippingCharge->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No shipping charges found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $shippingCharges->links() }}