<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.variant-values.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="variant_value_id" id="variant_value_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Variant Value</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" id="formName" name="name" value="{{ old('name') }}" >
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="formPlan">Variant Type</label>
                    <select class="form-select" id="formPlan" name="variant_type">
                        <option value="">Choose Variant Type</option>
                        @foreach($variantTypes ?? [] as $variantType)
                            <option value="{{ $variantType->id }}" {{ old('variant_type') == $variantType->id ? 'selected' : '' }}>{{ $variantType->name }}</option>
                        @endforeach
                    </select>
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
            const variantValueData = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            if (variantValueData) {
                editModal.querySelector('input[name="variant_value_id"]').value = variantValueData.id || '';
                editModal.querySelector('input[name="name"]').value = variantValueData.name || '';
                editModal.querySelector('select[name="variant_type"]').value = variantValueData.variant_type_id || '';
            }
        });
    });
</script>
@endpush