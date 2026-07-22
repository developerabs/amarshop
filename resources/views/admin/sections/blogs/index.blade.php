@extends('admin.layouts.master')
@push('styles')
<style>
    .modal-dialog {
        max-width: 900px !important;
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
                <h1 class="h3 mb-1">All Blogs</h1>
            </div>
        </div>
        <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Blog</button></div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Blogs</span></h2></div><input id="blogSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search blogs" aria-label="Search blogs"></div>
        <div class="table-responsive" id="blogTableBody"></div>
    </section>
</div>
@include('admin.components.models.blogs.add-blog', ['blogCategories' => $blogCategories ?? collect()])
@include('admin.components.models.blogs.edit-blog', ['blogCategories' => $blogCategories ?? collect()])
@endsection
@push('scripts')
<script>
    const blogSearchInput = document.getElementById('blogSearch');
    const blogTableBody = document.getElementById('blogTableBody');

    function blogFilter() {
        blogTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.blogs.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": blogSearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            blogTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    blogFilter();
    blogSearchInput.addEventListener('input', blogFilter);
</script>
@endpush
