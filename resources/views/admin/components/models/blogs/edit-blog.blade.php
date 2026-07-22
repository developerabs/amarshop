<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.blogs.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="blog_id" id="edit_blog_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Blog</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="editBlogCategory">Category*</label>
                    <select class="form-select" id="editBlogCategory" name="blog_category_id" required>
                        <option value="">Choose category</option>
                        @foreach(($blogCategories ?? collect()) as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogTitle">Title*</label>
                    <input class="form-control" id="editBlogTitle" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogThumbnail">Thumbnail</label>
                    <input class="form-control" id="editBlogThumbnail" name="thumbnail" type="file" accept="image/*">
                    <img id="edit_blog_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 80px; max-width: 140px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogPublishedAt">Published At</label>
                    <input class="form-control" id="editBlogPublishedAt" name="published_at" type="datetime-local">
                </div>
                <div class="col-12">
                    <label class="form-label" for="editBlogExcerpt">Excerpt</label>
                    <textarea class="form-control" id="editBlogExcerpt" name="excerpt" rows="2">{{ old('excerpt') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="editBlogContent">Content</label>
                    <textarea class="form-control" id="editBlogContent" name="content" rows="5">{{ old('content') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogMetaTitle">Meta Title</label>
                    <input class="form-control" id="editBlogMetaTitle" name="meta_title" value="{{ old('meta_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogMetaDescription">Meta Description</label>
                    <textarea class="form-control" id="editBlogMetaDescription" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editBlogStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editBlogStatus" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                        <label class="form-check-label" for="editBlogStatus">Published</label>
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
        if (!editModal) return;

        const imageInput = editModal.querySelector('input[name="thumbnail"]');
        const imagePreview = editModal.querySelector('#edit_blog_image_preview');

        imageInput.addEventListener('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
                return;
            }

            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        });

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const data = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            imageInput.value = '';
            imagePreview.src = '#';
            imagePreview.style.display = 'none';

            if (data) {
                editModal.querySelector('input[name="blog_id"]').value = data.id || '';
                editModal.querySelector('select[name="blog_category_id"]').value = data.blog_category_id || '';
                editModal.querySelector('input[name="title"]').value = data.title || '';
                editModal.querySelector('textarea[name="excerpt"]').value = data.excerpt || '';
                editModal.querySelector('textarea[name="content"]').value = data.content || '';
                editModal.querySelector('input[name="meta_title"]').value = data.meta_title || '';
                editModal.querySelector('textarea[name="meta_description"]').value = data.meta_description || '';
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = data.status === true || data.status === 1;

                if (data.published_at) {
                    editModal.querySelector('input[name="published_at"]').value = String(data.published_at).slice(0, 16);
                } else {
                    editModal.querySelector('input[name="published_at"]').value = '';
                }

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
