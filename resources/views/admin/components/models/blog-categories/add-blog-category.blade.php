<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.blog-categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add Blog Category</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategoryName">Name*</label>
                    <input class="form-control" id="addBlogCategoryName" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategoryImage">Image</label>
                    <input class="form-control" id="addBlogCategoryImage" name="image" type="file" accept="image/*">
                    <img id="add_blog_category_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-12">
                    <label class="form-label" for="addBlogCategoryDescription">Description</label>
                    <textarea class="form-control" id="addBlogCategoryDescription" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategoryMetaTitle">Meta Title</label>
                    <input class="form-control" id="addBlogCategoryMetaTitle" name="meta_title" value="{{ old('meta_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategoryMetaDescription">Meta Description</label>
                    <textarea class="form-control" id="addBlogCategoryMetaDescription" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategoryStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addBlogCategoryStatus" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="addBlogCategoryStatus">Active</label>
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
        if (!addModal) return;

        const imageInput = addModal.querySelector('input[name="image"]');
        const preview = addModal.querySelector('#add_blog_category_image_preview');

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
    });
</script>
@endpush
