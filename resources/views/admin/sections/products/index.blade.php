@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Products</h1>
        </div>
    </div>
    <div class="heading-actions"><a class="btn btn-primary btn-sm" href="{{ route('admin.products.create') }}"><i class="bi bi-plus" aria-hidden="true"></i> Add Product</a></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Products</span></h2></div><input id="productSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search products" aria-label="Search products"></div>
    <div class="table-responsive">
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
                    <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
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
     </div>
    </section>
</div>
@endsection
@push('scripts')

@endpush