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
    <div class="table-responsive productTableBody" id="productTableBody">
     
     </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    const productSearchInput = document.getElementById('productSearch');
    const productTableBody = document.getElementById('productTableBody');

    function productFilter() {
        productTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.products.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#productSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            productTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    productFilter();
</script>
<script>
    $(document).ready(function() {
        $('#productSearch').on('input', function() {
            productFilter();
        });
    });
</script>
@endpush