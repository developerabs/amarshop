<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.variant-values.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add New Variant Value</span></h2></div></div>
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