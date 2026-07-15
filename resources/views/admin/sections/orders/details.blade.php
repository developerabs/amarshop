@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">Order Details</h1>
        </div>
    </div>
    </div>
    <section class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Order Details</span></h2></div></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $order->order_no ?? 'N/A' }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->grand_total ?? 'N/A' }}</td>
                        <td>{{ $order->order_status ?? 'N/A' }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('M j, Y') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel-header mt-4"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Order Items</span></h2></div></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Variant Name</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $item)
                    <tr data-items="{{ json_encode($item) }}">
                        <td>{{ $item->product_name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity ?? 'N/A' }}</td>
                        <td>{{ $item->variant_name ?? 'N/A' }}</td>
                        <td>{{ $item->price ?? 'N/A' }}</td>
                        <td>{{ $item->subtotal ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No order items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection