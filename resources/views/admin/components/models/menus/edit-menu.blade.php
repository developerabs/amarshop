<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.menus.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="menu_id" id="menu_id">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Edit Menu</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label" for="name">Name*</label>
                    <input class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="location">Location*</label>
                    <select name="location" id="location" class="form-select" required>
                        <option value="" disabled selected>Select location</option>
                        <option value="main-navigation" {{ old('location') == 'main-navigation' ? 'selected' : '' }}>Main Navigation</option>
                        <option value="footer-menu" {{ old('location') == 'footer-menu' ? 'selected' : '' }}>Footer Menu</option>
                        <option value="company-menu" {{ old('location') == 'company-menu' ? 'selected' : '' }}>Company Menu</option>
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
            const menuData = button.closest('tr').dataset.items ? JSON.parse(button.closest('tr').dataset.items) : null;

            if (menuData) {
                editModal.querySelector('input[name="menu_id"]').value = menuData.id || '';
                editModal.querySelector('input[name="name"]').value = menuData.name || '';
                
                const locationSelect = editModal.querySelector('select[name="location"]');
                locationSelect.value = menuData.location || '';
                
                locationSelect.querySelectorAll('option').forEach(option => {
                    if (option.value === menuData.location) {
                        option.selected = true;
                    }
                });
            }
        });
    });
</script>
@endpush