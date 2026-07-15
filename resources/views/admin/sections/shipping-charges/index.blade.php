@extends('admin.layouts.master')
@push('styles')
<style>
    .modal-dialog {
        max-width: 800px !important;
        margin-right: auto;
        margin-left: auto;
    }
</style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Shipping Charges</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Shipping Charge</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Shipping Charges</span></h2></div><input id="shippingChargeSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search shipping charges" aria-label="Search shipping charges"></div>
    <div class="table-responsive shippingChargeTableBody" id="shippingChargeTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.shipping-charges.add-shipping-charge')
@include('admin.components.models.shipping-charges.edit-shipping-charge')
@endsection
@push('scripts')
<script>
    const shippingChargeSearchInput = document.getElementById('shippingChargeSearch');
    const shippingChargeTableBody = document.getElementById('shippingChargeTableBody');

    function shippingChargeFilter() {
        shippingChargeTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.shipping-charges.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#shippingChargeSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            shippingChargeTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    shippingChargeFilter();
</script>
<script>
    $(document).ready(function() {
        $('#shippingChargeSearch').on('input', function() {
            shippingChargeFilter();
        });
    });
</script>
@endpush