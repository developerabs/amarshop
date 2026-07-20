<div class="modal fade" id="freeShippingModal" tabindex="-1" aria-labelledby="freeShippingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-small">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.shipping-charges.update-free-shipping-amount') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Update Max Free Shipping Amount</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label" for="formAmount">Amount*</label>
                    <input class="form-control" type="text" id="formAmount" name="free_shipping_amount" value="{{ $setting->value ?? old('free_shipping_amount') }}" >
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