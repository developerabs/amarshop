@extends('admin.layouts.master')
@section('title', 'Product Reviews')
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-star" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">Product Reviews</h1>
            </div>
        </div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Product Reviews</span></h2></div><input id="productReviewSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search product reviews" aria-label="Search product reviews"></div>
        <div class="table-responsive" id="productReviewTableBody"></div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const productReviewSearchInput = document.getElementById('productReviewSearch');
    const productReviewTableBody = document.getElementById('productReviewTableBody');

    function productReviewFilter() {
        productReviewTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.product-reviews.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": productReviewSearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            productReviewTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    productReviewFilter();
    productReviewSearchInput.addEventListener('input', productReviewFilter);
</script>
@endpush
