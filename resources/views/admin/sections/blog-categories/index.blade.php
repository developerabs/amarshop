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
                <h1 class="h3 mb-1">All Blog Categories</h1>
            </div>
        </div>
        <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Blog Category</button></div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Blog Categories</span></h2></div><input id="blogCategorySearch" class="form-control form-control-sm table-search" type="search" placeholder="Search blog categories" aria-label="Search blog categories"></div>
        <div class="table-responsive" id="blogCategoryTableBody"></div>
    </section>
</div>
@include('admin.components.models.blog-categories.add-blog-category')
@include('admin.components.models.blog-categories.edit-blog-category')
@endsection
@push('scripts')
<script>
    const blogCategorySearchInput = document.getElementById('blogCategorySearch');
    const blogCategoryTableBody = document.getElementById('blogCategoryTableBody');

    function blogCategoryFilter() {
        blogCategoryTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.blog-categories.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": blogCategorySearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            blogCategoryTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    blogCategoryFilter();
    blogCategorySearchInput.addEventListener('input', blogCategoryFilter);
</script>
@endpush
