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
        <h1 class="h3 mb-1">All Brands</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Brand</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Brands</span></h2></div><input class="form-control form-control-sm table-search" type="search" placeholder="Search brands" data-table-search="brandsTable" aria-label="Search brands"></div>
    <div class="table-responsive" id="brandTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.brands.add-brand')
@include('admin.components.models.brands.edit-brand')
@endsection
@push('scripts')
<script>
    const brandSearchInput = document.getElementById('brandSearch');
    const brandTableBody = document.getElementById('brandTableBody');

    function brandFilter() {
        brandTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.brands.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#brandSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            brandTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }
    brandFilter();
</script>
@endpush