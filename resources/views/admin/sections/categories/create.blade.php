@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">Add New Category</h1>
        </div>
    </div>
    
    </div>

    <section class="row g-3">
    <div class="col-12 col-xl-7">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add New Category</span></h2><p class="text-muted mb-0">Bootstrap-ready fields with custom validation feedback.</p></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" id="formName" name="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formPlan">Parent Category</label>
                    <select class="form-select" id="formPlan" name="parent_category">
                        <option value="">Choose Parent Category</option>
                        @foreach($parentCategories ?? [] as $parentCategory)
                            @if($parentCategory->children->count() > 0)
                                @foreach ($parentCategory->children as $childCategory)
                                    <option value="{{ $childCategory->id }}">-- {{ $childCategory->name }}</option>
                                    
                                @endforeach
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }} (Has Subcategories)</option>
                            @else
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formBudget">Image</label>
                    <input class="form-control" id="formBudget" name="image" type="file" accept="image/*" onchange="document.getElementById('image_preview').src=window.URL.createObjectURL(this.files[0]); document.getElementById('image_preview').style.display='block';">
                </div>
                <div class="col-12">
                    <label class="form-label" for="formMessage">Description</label>
                    <textarea class="form-control" id="formMessage" rows="5"></textarea>
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
    </section>
</div>
@endsection