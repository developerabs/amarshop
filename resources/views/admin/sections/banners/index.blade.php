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
        <h1 class="h3 mb-1">All Banners</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Banner</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Banners</span></h2></div><input id="bannerSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search banners" aria-label="Search banners"></div>
    <div class="table-responsive bannerTableBody" id="bannerTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.banners.add-banner')
@include('admin.components.models.banners.edit-banner')
@endsection
@push('scripts')
<script>
    const bannerSearchInput = document.getElementById('bannerSearch');
    const bannerTableBody = document.getElementById('bannerTableBody');

    function bannerFilter() {
        bannerTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.banners.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#bannerSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            bannerTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    bannerFilter();
</script>
<script>
    $(document).ready(function() {
        $('#bannerSearch').on('input', function() {
            bannerFilter();
        });
    });
</script>
@endpush