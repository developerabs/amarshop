<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.social-links.store') }}">
            @csrf
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add Social Link</span></h2></div></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="addSocialLinkName">Name*</label>
                    <input class="form-control" id="addSocialLinkName" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addSocialLinkIcon">Icon Class</label>
                    <input class="form-control" id="addSocialLinkIcon" name="icon" value="{{ old('icon') }}" placeholder="bi bi-facebook">
                </div>
                <div class="col-12">
                    <label class="form-label" for="addSocialLinkUrl">URL*</label>
                    <input class="form-control" id="addSocialLinkUrl" type="url" name="url" value="{{ old('url') }}" placeholder="https://..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addSocialLinkSortOrder">Sort Order</label>
                    <input class="form-control" id="addSocialLinkSortOrder" type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="addSocialLinkStatus">Status</label>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addSocialLinkStatus" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="addSocialLinkStatus">Active</label>
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
