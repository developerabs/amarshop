<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.brands.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="brand_id" id="brand_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Brand</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" id="formName" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formImage">Image</label>
                    <input class="form-control" id="formImage" name="image" type="file" accept="image/*">
                    <img id="image_preview" src="#" alt="Image Preview" class="img-fluid fade-in mt-2" style="display: none; max-height: 40px; max-width: 80px; object-fit: cover;">
                </div>
                <div class="col-12">
                    <label class="form-label" for="formMessage">Description</label>
                    <textarea class="form-control" id="formMessage" name="description" rows="5">{{ old('description') }}</textarea>
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
        const editModal = document.getElementById('editModal');
        const imageUrl = "{{ asset('storage') }}"; // Base URL for category images
        const imageInput = editModal.querySelector('input[name="image"]');
        const imagePreview = editModal.querySelector('#image_preview');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const brandData = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            imageInput.value = '';
            imagePreview.src = '#';
            imagePreview.style.display = 'none';

            if (brandData) {
                editModal.querySelector('input[name="brand_id"]').value = brandData.id || '';
                editModal.querySelector('input[name="name"]').value = brandData.name || '';
                editModal.querySelector('textarea[name="description"]').value = brandData.description || '';
                editModal.querySelector('input[name="meta_title"]').value = brandData.meta_title || '';
                editModal.querySelector('textarea[name="meta_description"]').value = brandData.meta_description || '';
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = brandData.status === true || brandData.status === 1;

                if (brandData.image) {
                    imagePreview.src = imageUrl + '/' + brandData.image;
                    imagePreview.style.display = 'block';
                }
            }
        });
    });
</script>
@endpush
