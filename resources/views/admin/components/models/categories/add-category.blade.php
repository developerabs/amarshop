<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add New Category</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" id="formName" name="name" value="{{ old('name') }}" >
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formPlan">Parent Category</label>
                    <select class="form-select" id="formPlan" name="parent_category">
                        <option value="">Choose Parent Category</option>
                        @foreach(($parentCategories ?? collect()) as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ old('parent_category') == $parentCategory->id ? 'selected' : '' }}>{{ $parentCategory->name }}</option>
                            @if($parentCategory->children->count() > 0)
                                @foreach ($parentCategory->children as $childCategory)
                                    <option value="{{ $childCategory->id }}" {{ old('parent_category') == $childCategory->id ? 'selected' : '' }}>-- {{ $childCategory->name }}</option>
                                    @if ($childCategory->children->count() > 0)
                                        @foreach ($childCategory->children as $subChildCategory)
                                            <option value="{{ $subChildCategory->id }}" {{ old('parent_category') == $subChildCategory->id ? 'selected' : '' }}>---- {{ $subChildCategory->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formIcon">Icon</label>
                    <input class="form-control" id="formIcon" name="icon" value="{{ old('icon') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formBudget">Image</label>
                    <input class="form-control" id="formBudget" name="image" type="file" accept="image/*" data-preview-target="#add_category_image_preview">
                    <img id="add_category_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="formMessage">Description</label>
                    <textarea class="form-control" id="formMessage" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formName">Meta Title</label>
                    <input class="form-control" id="formName" name="meta_title" value="{{ old('meta_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formPlan">Meta Description</label>
                    <textarea class="form-control" id="formPlan" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="formStatus" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addModal = document.getElementById('addModal');
        if (!addModal) {
            return;
        }

        const imageInput = addModal.querySelector('input[name="image"]');
        const preview = addModal.querySelector('#add_category_image_preview');

        if (imageInput && preview) {
            imageInput.addEventListener('change', function () {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    preview.src = '#';
                    preview.style.display = 'none';
                    return;
                }

                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            });
        }
    });
</script>
@endpush