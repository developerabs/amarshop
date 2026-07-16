<table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Code</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Cost</th>
                <th>Total Stock</th>
                <th>Status</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products ?? [] as $product)
            <tr data-items="{{ json_encode($product) }}">
                <td><img src="{{ getImageUrl($product->thumbnail) ?? "" }}" class="img-thumbnail" width="40" height="30"></td>
                <td>{{ $product->name ?? 'N/A' }}</td>
                <td>{{ $product->code ?? 'N/A' }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                <td>{{ $product->price ?? 'N/A' }}</td>
                <td>{{ $product->cost ?? 'N/A' }}</td>
                <td>{{ $product->total_stock ?? 'N/A' }}</td>
                <td>
                    @if($product->status)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $product->created_at ? $product->created_at->format('M j, Y') : 'N/A' }}</td>
                <td class="text-end">
                    <a class="btn btn-warning btn-sm" href="{{ route('admin.products.edit', $product->id) }}"><i class="bi bi-pencil" aria-hidden="true"></i></a>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $product->id }}" data-url="{{ route('admin.products.destroy', $product->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                    <form id="delete-form" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
{{ $products->links() }}