@extends('admin.layouts.master')
@push('styles')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Pages</h1>
        </div>
    </div>
    <div class="heading-actions"><a class="btn btn-primary btn-sm" href="{{ route('admin.pages.create') }}"><i class="bi bi-plus" aria-hidden="true"></i> Add Page</a></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Pages</span></h2></div><input id="pageSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search pages" aria-label="Search pages"></div>
    <div class="table-responsive pageTableBody" id="pageTableBody">
        
     </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    const pageSearchInput = document.getElementById('pageSearch');
    const pageTableBody = document.getElementById('pageTableBody');

    function pageFilter() {
        pageTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.pages.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#pageSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            pageTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    pageFilter();
</script>
<script>
    $(document).ready(function() {
        $('#pageSearch').on('input', function() {
            pageFilter();
        });
    });
</script>
@endpush