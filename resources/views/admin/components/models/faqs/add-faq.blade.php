<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add FAQ</span></h2></div></div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="addFaqQuestion">Question*</label>
                    <input class="form-control" id="addFaqQuestion" name="question" value="{{ old('question') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="addFaqAnswer">Answer*</label>
                    <textarea class="form-control" id="addFaqAnswer" name="answer" rows="4" required>{{ old('answer') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addFaqSortOrder">Sort Order</label>
                    <input class="form-control" id="addFaqSortOrder" type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addFaqStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addFaqStatus" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="addFaqStatus">Active</label>
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
