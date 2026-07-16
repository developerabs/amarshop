@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Orders</h1>
        </div>
    </div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Orders</span></h2></div><input id="orderSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search orders" aria-label="Search orders"></div>
    <div class="table-responsive orderTableBody" id="orderTableBody">
       
     </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    const orderSearchInput = document.getElementById('orderSearch');
    const orderTableBody = document.getElementById('orderTableBody');

    function orderFilter() {
        orderTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.orders.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#orderSearch").val(),
            "page": "{{ request()->get('page', 1) }}"
        }).done(function(data) {
            orderTableBody.innerHTML = data;
        }).fail(function(xhr, status, error) {
            alert(error);
            console.error("Error:", error);
        });
    }
    orderFilter();
    orderSearchInput.addEventListener('input', orderFilter);
</script>
@endpush