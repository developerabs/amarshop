<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.faqs.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="faq_id" id="edit_faq_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit FAQ</span></h2></div></div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="editFaqQuestion">Question*</label>
                    <input class="form-control" id="editFaqQuestion" name="question" value="{{ old('question') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="editFaqAnswer">Answer*</label>
                    <textarea class="form-control" id="editFaqAnswer" name="answer" rows="4" required>{{ old('answer') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editFaqSortOrder">Sort Order</label>
                    <input class="form-control" id="editFaqSortOrder" type="number" name="sort_order" min="0" value="{{ old('sort_order') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editFaqStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editFaqStatus" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                        <label class="form-check-label" for="editFaqStatus">Active</label>
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

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const data = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            if (data) {
                editModal.querySelector('input[name="faq_id"]').value = data.id || '';
                editModal.querySelector('input[name="question"]').value = data.question || '';
                editModal.querySelector('textarea[name="answer"]').value = data.answer || '';
                editModal.querySelector('input[name="sort_order"]').value = data.sort_order ?? 0;
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = data.status === true || data.status === 1;
            }
        });
    });
</script>
@endpush
