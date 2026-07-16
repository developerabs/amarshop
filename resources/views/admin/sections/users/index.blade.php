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
        <h1 class="h3 mb-1">All Users</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add User</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Users</span></h2></div><input id="userSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search users" aria-label="Search users"></div>
    <div class="table-responsive userTableBody" id="userTableBody">
        
     </div>
    </section>
</div>
{{-- @include('admin.components.models.users.add-user')
@include('admin.components.models.users.edit-user') --}}
@endsection
@push('scripts')
<script>
    const userSearchInput = document.getElementById('userSearch');
    const userTableBody = document.getElementById('userTableBody');

    function userFilter() {
        userTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.users.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#userSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            userTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    userFilter();
</script>
<script>
    $(document).ready(function() {
        $('#userSearch').on('input', function() {
            userFilter();
        });
    });
</script>
@endpush