<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.social-links.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="social_link_id" id="edit_social_link_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Social Link</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="editSocialLinkName">Name*</label>
                    <input class="form-control" id="editSocialLinkName" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editSocialLinkIcon">Icon Class</label>
                    <input class="form-control" id="editSocialLinkIcon" name="icon" value="{{ old('icon') }}" placeholder="bi bi-facebook">
                </div>
                <div class="col-12">
                    <label class="form-label" for="editSocialLinkUrl">URL*</label>
                    <input class="form-control" id="editSocialLinkUrl" type="url" name="url" value="{{ old('url') }}" placeholder="https://..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editSocialLinkSortOrder">Sort Order</label>
                    <input class="form-control" id="editSocialLinkSortOrder" type="number" name="sort_order" min="0" value="{{ old('sort_order') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="editSocialLinkStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editSocialLinkStatus" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                        <label class="form-check-label" for="editSocialLinkStatus">Active</label>
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
                editModal.querySelector('input[name="social_link_id"]').value = data.id || '';
                editModal.querySelector('input[name="name"]').value = data.name || '';
                editModal.querySelector('input[name="url"]').value = data.url || '';
                editModal.querySelector('input[name="icon"]').value = data.icon || '';
                editModal.querySelector('input[name="sort_order"]').value = data.sort_order ?? 0;
                editModal.querySelector('input[type="checkbox"][name="status"]').checked = data.status === true || data.status === 1;
            }
        });
    });
</script>
@endpush
