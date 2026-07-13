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
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Order No</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Order Status</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders ?? [] as $order)
            <tr data-items="{{ json_encode($order) }}">
                <td>{{ $orders->firstItem() + $loop->index }}</td>
                <td>{{ $order->order_no ?? 'N/A' }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ $order->grand_total ?? 'N/A' }}</td>
                <td>{{ $order->order_status ?? 'N/A' }}</td>
                <td>{{ $order->created_at ? $order->created_at->format('M j, Y') : 'N/A' }}</td>
                <td class="text-end">
                    <a class="btn btn-warning btn-sm" href="{{ route('admin.orders.details', $order->id) }}"><i class="bi bi-eye" aria-hidden="true"></i></a>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $order->id }}" data-url="{{ route('admin.orders.destroy', $order->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                    <form id="delete-form" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $orders->links() }}
     </div>
    </section>
</div>
@endsection
@push('scripts')

@endpush