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
            <span class="page-icon"><i class="bi bi-question-circle" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">All FAQs</h1>
            </div>
        </div>
        <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add FAQ</button></div>
    </div>

    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All FAQs</span></h2></div><input id="faqSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search faqs" aria-label="Search faqs"></div>
        <div class="table-responsive" id="faqTableBody"></div>
    </section>
</div>
@include('admin.components.models.faqs.add-faq')
@include('admin.components.models.faqs.edit-faq')
@endsection
@push('scripts')
<script>
    const faqSearchInput = document.getElementById('faqSearch');
    const faqTableBody = document.getElementById('faqTableBody');

    function faqFilter() {
        faqTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.faqs.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": faqSearchInput.value,
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            faqTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }

    faqFilter();
    faqSearchInput.addEventListener('input', faqFilter);
</script>
@endpush
