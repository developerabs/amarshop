@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Variant Values</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Variant Value</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Variant Values</span></h2></div><input class="form-control form-control-sm table-search" type="search" placeholder="Search variant values" data-table-search="variantValuesTable" aria-label="Search variant values"></div>
    <div class="table-responsive" id="variantValueTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.variant-values.add-variant-value', ['variantTypes' => $variantTypes ?? []])
@include('admin.components.models.variant-values.edit-variant-value', ['variantTypes' => $variantTypes ?? [], 'variantValue' => $variantValue ?? null])
@endsection
@push('scripts')
<script>
    const variantValueSearchInput = document.getElementById('variantValueSearch');
    const variantValueTableBody = document.getElementById('variantValueTableBody');

    function variantValueFilter() {
        variantValueTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.variant-values.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#variantValueSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            variantValueTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }
    variantValueFilter();
</script>
@endpush