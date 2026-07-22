<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add Blog</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="addBlogCategory">Category*</label>
                    <select class="form-select" id="addBlogCategory" name="blog_category_id" required>
                        <option value="">Choose category</option>
                        @foreach(($blogCategories ?? collect()) as $category)
                            <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogTitle">Title*</label>
                    <input class="form-control" id="addBlogTitle" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogThumbnail">Thumbnail</label>
                    <input class="form-control" id="addBlogThumbnail" name="thumbnail" type="file" accept="image/*">
                    <img id="add_blog_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 80px; max-width: 140px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogPublishedAt">Published At</label>
                    <input class="form-control" id="addBlogPublishedAt" name="published_at" type="datetime-local" value="{{ old('published_at') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="addBlogExcerpt">Excerpt</label>
                    <textarea class="form-control" id="addBlogExcerpt" name="excerpt" rows="2">{{ old('excerpt') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label" for="addBlogContent">Content</label>
                    <textarea class="form-control" id="addBlogContent" name="content" rows="5">{{ old('content') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogMetaTitle">Meta Title</label>
                    <input class="form-control" id="addBlogMetaTitle" name="meta_title" value="{{ old('meta_title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogMetaDescription">Meta Description</label>
                    <textarea class="form-control" id="addBlogMetaDescription" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addBlogStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addBlogStatus" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="addBlogStatus">Published</label>
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

        const imageInput = addModal.querySelector('input[name="thumbnail"]');
        const preview = addModal.querySelector('#add_blog_image_preview');

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
