<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.client-reviews.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="client_review_id" id="edit_client_review_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Client Review</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="editClientReviewTitle">Title*</label>
                    <input class="form-control" id="editClientReviewTitle" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editClientReviewRating">Rating*</label>
                    <input class="form-control" id="editClientReviewRating" type="number" name="rating" value="{{ old('rating') }}" step="0.1" min="1" max="5" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editClientReviewImage">Image</label>
                    <input class="form-control" id="editClientReviewImage" name="image" type="file" accept="image/*">
                    <img id="edit_client_review_image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 6px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editClientReviewVideoLink">Video Link</label>
                    <input class="form-control" id="editClientReviewVideoLink" name="video_link" type="url" value="{{ old('video_link') }}" placeholder="https://...">
                </div>
                <div class="col-12">
                    <label class="form-label" for="editClientReviewDescription">Description</label>
                    <textarea class="form-control" id="editClientReviewDescription" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editClientReviewStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editClientReviewStatus" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                        <label class="form-check-label" for="editClientReviewStatus">Active</label>
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

        const imageInput = editModal.querySelector('input[name="image"]');
        const imagePreview = editModal.querySelector('#edit_client_review_image_preview');

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
                editModal.querySelector('input[name="client_review_id"]').value = data.id || '';
                editModal.querySelector('input[name="title"]').value = data.title || '';
                editModal.querySelector('input[name="rating"]').value = data.rating || '';
                editModal.querySelector('textarea[name="description"]').value = data.description || '';
                editModal.querySelector('input[name="video_link"]').value = data.video_link || '';
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = data.status === true || data.status === 1;

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
