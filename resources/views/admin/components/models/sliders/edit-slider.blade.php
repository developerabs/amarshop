<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.sliders.update') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="id" value="{{ old('id') }}">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Slider</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formTitle">Title*</label>
                    <input class="form-control" type="text" id="formTitle" name="title" value="{{ old('title') }}" >
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formDescription">Description*</label>
                    <input class="form-control" type="text" id="formDescription" name="description" value="{{ old('description') }}" >
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formImage">Image*</label>
                    <input class="form-control" type="file" id="formImage" name="image" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formButtonText">Button Text*</label>
                    <input class="form-control" type="text" id="formButtonText" name="button_text" value="{{ old('button_text') }}" >
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formButtonLink">Button Link*</label>
                    <input class="form-control" type="text" id="formButtonLink" name="button_link" value="{{ old('button_link') }}" >
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

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const shippingChargeData = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            if (shippingChargeData) {
                editModal.querySelector('input[name="id"]').value = shippingChargeData.id || '';
                editModal.querySelector('input[name="title"]').value = shippingChargeData.title || '';
                editModal.querySelector('input[name="description"]').value = shippingChargeData.description || '';
                editModal.querySelector('input[name="button_text"]').value = shippingChargeData.button_text || '';
                editModal.querySelector('input[name="button_link"]').value = shippingChargeData.button_link || '';
            }
        });
    });
</script>
@endpush