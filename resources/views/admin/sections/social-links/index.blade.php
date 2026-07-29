@extends('admin.layouts.master')
@section('title', 'All Social Links')
@push('styles')
<style>
    .modal-dialog {
        max-width: 800px !important;
        margin-right: auto;
        margin-left: auto;
    }
</style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-share" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">All Social Links</h1>
            </div>
        </div>
        <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Social Link</button></div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Social Links</span></h2></div><input id="socialLinkSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search social links" aria-label="Search social links"></div>
        <div class="table-responsive" id="socialLinkTableBody"></div>
    </section>
</div>
@include('admin.components.models.social-links.add-social-link')
@include('admin.components.models.social-links.edit-social-link')
@endsection
@push('scripts')
<script>
    const socialLinkSearchInput = document.getElementById('socialLinkSearch');
    const socialLinkTableBody = document.getElementById('socialLinkTableBody');

    function socialLinkFilter() {
        socialLinkTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.social-links.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": socialLinkSearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            socialLinkTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    socialLinkFilter();
    socialLinkSearchInput.addEventListener('input', socialLinkFilter);
</script>
@endpush
