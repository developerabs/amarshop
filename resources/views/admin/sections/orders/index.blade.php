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
    <div class="panel-header">
        <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Orders</span></h2>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center" style="min-width: 320px;">
            <input id="orderSearch" class="form-control form-control-sm table-search" type="search" placeholder="Search order no, customer" aria-label="Search orders">
            <select id="orderStatusFilter" class="form-select form-select-sm" style="max-width: 170px;">
                <option value="">Order status</option>
                <option value="active" {{ request('order_status') === 'active' ? 'selected' : '' }}>Active</option>
                @foreach(($orderStatuses ?? []) as $status)
                    <option value="{{ $status }}" {{ request('order_status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <select id="paymentStatusFilter" class="form-select form-select-sm" style="max-width: 170px;">
                <option value="">Payment status</option>
                @foreach(($paymentStatuses ?? []) as $status)
                    <option value="{{ $status }}" {{ request('payment_status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-responsive orderTableBody" id="orderTableBody">
       
     </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    const orderSearchInput = document.getElementById('orderSearch');
    const orderStatusFilter = document.getElementById('orderStatusFilter');
    const paymentStatusFilter = document.getElementById('paymentStatusFilter');
    const orderTableBody = document.getElementById('orderTableBody');

    function orderFilter() {
        orderTableBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $.post("{{ route('admin.orders.search') }}", {
            "_token": "{{ csrf_token() }}",
            "query": $("#orderSearch").val(),
            "order_status": $("#orderStatusFilter").val(),
            "payment_status": $("#paymentStatusFilter").val(),
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
    orderStatusFilter.addEventListener('change', orderFilter);
    paymentStatusFilter.addEventListener('change', orderFilter);
</script>
@endpush