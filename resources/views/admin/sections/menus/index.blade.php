@extends('admin.layouts.master')
@push('styles')
<style>
    .modal-dialog {
        max-width: 400px !important;
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
        <h1 class="h3 mb-1">All Menus</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Menu</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Menus</span></h2></div><input id="menuSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search menus" aria-label="Search menus"></div>
    <div class="table-responsive pageTableBody" id="menuTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.menus.add-menu')
@include('admin.components.models.menus.edit-menu')
@endsection
@push('scripts')
<script>
    const menuSearchInput = document.getElementById('menuSearch');
    const menuTableBody = document.getElementById('menuTableBody');

    function menuFilter() {
        menuTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.menus.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#menuSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            menuTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    menuFilter();
</script>
<script>
    $(document).ready(function() {
        $('#menuSearch').on('input', function() {
            menuFilter();
        });
    });
</script>
@endpush