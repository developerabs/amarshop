@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Variant Types</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Variant Type</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Variant Types</span></h2></div><input class="form-control form-control-sm table-search" type="search" placeholder="Search variant types" data-table-search="variantTypesTable" aria-label="Search variant types"></div>
    <div class="table-responsive" id="variantTypeTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.variant-types.add-variant-type')
@include('admin.components.models.variant-types.edit-variant-type')
@endsection
@push('scripts')
<script>
    const variantTypeSearchInput = document.getElementById('variantTypeSearch');
    const variantTypeTableBody = document.getElementById('variantTypeTableBody');

    function variantTypeFilter() {
        variantTypeTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.variant-types.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#variantTypeSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            variantTypeTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            console.error("Error:", error);
        });
    }
    variantTypeFilter();
</script>
@endpush