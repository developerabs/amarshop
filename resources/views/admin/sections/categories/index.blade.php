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
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#formModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Category</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Categories</span></h2></div><input id="categorySearch" class="form-control form-control-sm table-search" type="search" placeholder="Search categories" aria-label="Search categories"></div>
    <div class="table-responsive categoryTableBody" id="categoryTableBody">
        
     </div>
    </section>
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
            <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add New Category</span></h2></div></div>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="formName">Name*</label>
                        <input class="form-control" id="formName" name="name" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formPlan">Parent Category</label>
                        <select class="form-select" id="formPlan" name="parent_category">
                            <option value="">Choose Parent Category</option>
                            @foreach($parentCategories ?? [] as $parentCategory)
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                @if($parentCategory->children->count() > 0)
                                    @foreach ($parentCategory->children as $childCategory)
                                        <option value="{{ $childCategory->id }}">-- {{ $childCategory->name }}</option>
                                        @if ($childCategory->children->count() > 0)
                                            @foreach ($childCategory->children as $subChildCategory)
                                                <option value="{{ $subChildCategory->id }}">---- {{ $subChildCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formBudget">Image</label>
                        <input class="form-control" id="formBudget" name="image" type="file" accept="image/*"
                            onchange="if (this.files && this.files[0]) { const preview = document.getElementById('image_preview'); preview.src = window.URL.createObjectURL(this.files[0]); preview.style.display = 'block'; } else { document.getElementById('image_preview').style.display = 'none'; }">
                        <img id="image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 180px;">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="formMessage">Description</label>
                        <textarea class="form-control" id="formMessage" name="description" rows="5"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formName">Meta Title</label>
                        <input class="form-control" id="formName" name="meta_title">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formPlan">Meta Description</label>
                        <textarea class="form-control" id="formPlan" name="meta_description" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formStatus">Status</label>
                        <input type="hidden" name="status" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="formStatus" name="status" value="1" checked>
                            <label class="form-check-label" for="formStatus">Active</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-send" aria-hidden="true"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
            console.error("Error:", error);
        });
    }
    categoryFilter();
</script>
@endpush