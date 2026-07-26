<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.client-reviews.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add Client Review</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="addClientReviewTitle">Title*</label>
                    <input class="form-control" id="addClientReviewTitle" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addClientReviewRating">Rating*</label>
                    <input class="form-control" id="addClientReviewRating" type="number" name="rating" value="{{ old('rating', 5) }}" step="0.1" min="1" max="5" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addClientReviewImage">Image</label>
                    <input class="form-control" id="addClientReviewImage" name="image" type="file" accept="image/*">
                    <img id="add_client_review_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addClientReviewVideoLink">Video Link</label>
                    <input class="form-control" id="addClientReviewVideoLink" name="video_link" type="url" value="{{ old('video_link') }}" placeholder="https://...">
                </div>
                <div class="col-12">
                    <label class="form-label" for="addClientReviewDescription">Description</label>
                    <textarea class="form-control" id="addClientReviewDescription" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addClientReviewStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addClientReviewStatus" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="addClientReviewStatus">Active</label>
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
        const preview = addModal.querySelector('#add_client_review_image_preview');

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
