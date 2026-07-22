<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.categories.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="category_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Category</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" id="formName" name="name" value="{{ old('name') }}" required>
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
                    <label class="form-label" for="formImage">Image</label>
                    <input class="form-control" id="formImage" name="image" type="file" accept="image/*">
                    <img id="edit_category_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-12">
                    <label class="form-label" for="formMessage">Description</label>
                    <textarea class="form-control" id="formMessage" name="description" rows="5">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formMetaTitle">Meta Title</label>
                    <input class="form-control" id="formMetaTitle" name="meta_title" value="{{ old('meta_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formMetaDescription">Meta Description</label>
                    <textarea class="form-control" id="formMetaDescription" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
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
        const editModal = document.getElementById('editModal');
        const imageInput = editModal.querySelector('input[name="image"]');
        const imagePreview = editModal.querySelector('#edit_category_image_preview');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.style.display = 'block';
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const categoryData = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            imageInput.value = '';
            imagePreview.src = '#';
            imagePreview.style.display = 'none';

            if (categoryData) {
                editModal.querySelector('input[name="category_id"]').value = categoryData.id || '';
                editModal.querySelector('input[name="name"]').value = categoryData.name || '';
                editModal.querySelector('select[name="parent_category"]').value = categoryData.parent_id || '';
                editModal.querySelector('textarea[name="description"]').value = categoryData.description || '';
                editModal.querySelector('input[name="meta_title"]').value = categoryData.meta_title || '';
                editModal.querySelector('textarea[name="meta_description"]').value = categoryData.meta_description || '';
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = categoryData.status === true || categoryData.status === 1;
                editModal.querySelector('input[name="icon"]').value = categoryData.icon || '';

                const rowImage = button.closest('tr')?.querySelector('td img');
                if (rowImage && rowImage.getAttribute('src')) {
                    imagePreview.src = rowImage.getAttribute('src');
                    imagePreview.style.display = 'block';
                }
            }
        });
    });
</script>
@endpush