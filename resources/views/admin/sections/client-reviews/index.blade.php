@extends('admin.layouts.master')
@push('styles')
<style>
    .modal-dialog {
        max-width: 900px !important;
        margin-right: auto;
        margin-left: auto;
    }
</style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-chat-right-quote" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">All Client Reviews</h1>
            </div>
        </div>
        <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Client Review</button></div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Client Reviews</span></h2></div><input id="clientReviewSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search client reviews" aria-label="Search client reviews"></div>
        <div class="table-responsive" id="clientReviewTableBody"></div>
    </section>
</div>
@include('admin.components.models.client-reviews.add-client-review')
@include('admin.components.models.client-reviews.edit-client-review')
@endsection
@push('scripts')
<script>
    const clientReviewSearchInput = document.getElementById('clientReviewSearch');
    const clientReviewTableBody = document.getElementById('clientReviewTableBody');

    function clientReviewFilter() {
        clientReviewTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.client-reviews.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": clientReviewSearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            clientReviewTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    clientReviewFilter();
    clientReviewSearchInput.addEventListener('input', clientReviewFilter);
</script>
@endpush
