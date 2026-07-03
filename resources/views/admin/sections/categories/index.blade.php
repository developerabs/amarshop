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
    <div class="heading-actions"><a class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#formModal" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus" aria-hidden="true"></i> Add Category</a></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Categories</span></h2></div><input class="form-control form-control-sm table-search" type="search" placeholder="Search orders" data-table-search="ordersTable" aria-label="Search orders"></div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories ?? [] as $category)
                <tr>
                    <td class="fw-semibold">{{ $categories->firstItem() + $loop->index }}</td>
                    <td></td>
                    <td>{{ $category->name ?? 'N/A' }}</td>
                    <td>{{ $category->parent ? $category->parent->name : 'None' }}</td>
                    <td>
                        @if($category->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $category->created_at ?$category->created_at->format('M j, Y') : 'N/A' }}</td>
                    <td class="text-end">
                        <a class="btn btn-warning btn-sm" href="{{ route('admin.categories.edit', $category->id) }}"><i class="bi bi-pencil" aria-hidden="true"></i></a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No categories found.</td>
                </tr>
                @endforelse
             </tbody>
         </table>
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
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
    </div>
</div>
@endsection