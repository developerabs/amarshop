@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Categories</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Category</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Categories</span></h2></div><input id="categorySearch" class="form-control form-control-sm table-search" type="search" placeholder="Search categories" aria-label="Search categories"></div>
    <div class="table-responsive categoryTableBody" id="categoryTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.categories.add-category', ['parentCategories' => $parentCategories ?? []])
@include('admin.components.models.categories.edit-category', ['parentCategories' => $parentCategories ?? [], 'category' => $category ?? null])
@endsection
@push('scripts')
<script>
    const categorySearchInput = document.getElementById('categorySearch');
    const categoryTableBody = document.getElementById('categoryTableBody');

    function categoryFilter() {
        categoryTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.categories.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#categorySearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            categoryTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    categoryFilter();
</script>
<script>
    $(document).ready(function() {
        $('#categorySearch').on('input', function() {
            categoryFilter();
        });
    });
</script>
@endpush