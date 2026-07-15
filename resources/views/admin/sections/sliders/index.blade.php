@extends('admin.layouts.master')
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
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Sliders</h1>
        </div>
    </div>
    <div class="heading-actions"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus" aria-hidden="true"></i> Add Slider</button></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Sliders</span></h2></div><input id="sliderSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search sliders" aria-label="Search sliders"></div>
    <div class="table-responsive sliderTableBody" id="sliderTableBody">
        
     </div>
    </section>
</div>
@include('admin.components.models.sliders.add-slider')
@include('admin.components.models.sliders.edit-slider')
@endsection
@push('scripts')
<script>
    const sliderSearchInput = document.getElementById('sliderSearch');
    const sliderTableBody = document.getElementById('sliderTableBody');

    function sliderFilter() {
        sliderTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.sliders.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#sliderSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            sliderTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    sliderFilter();
</script>
<script>
    $(document).ready(function() {
        $('#sliderSearch').on('input', function() {
            sliderFilter();
        });
    });
</script>
@endpush