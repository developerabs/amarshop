<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.shipping-charges.update') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Shipping Charge</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="formName">Name*</label>
                    <input class="form-control" type="text" id="formName" name="name" value="{{ old('name') }}" >
                    <input type="hidden" name="shipping_charge_id" value="{{ old('shipping_charge_id') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="formCharge">Charge*</label>
                    <input class="form-control" type="number" id="formCharge" name="charge" value="{{ old('charge') }}" >
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
                editModal.querySelector('input[name="shipping_charge_id"]').value = shippingChargeData.id || '';
                editModal.querySelector('input[name="name"]').value = shippingChargeData.name || '';
                editModal.querySelector('input[name="charge"]').value = shippingChargeData.charge || '';
            }
        });
    });
</script>
@endpush