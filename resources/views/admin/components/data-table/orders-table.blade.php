 <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Order No</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Order Status</th>
                <th>Payment Status</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($orders ?? collect()) as $order)
            <tr data-items="{{ json_encode($order) }}">
                <td>{{ $orders->firstItem() + $loop->index }}</td>
                <td>{{ $order->order_no ?? 'N/A' }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ $order->grand_total ?? 'N/A' }}</td>
                <td>
                    @php
                        $orderStatusClass = match($order->order_status) {
                            'pending' => 'bg-warning',
                            'confirmed' => 'bg-info',
                            'processing' => 'bg-primary',
                            'shipped' => 'bg-secondary',
                            'delivered' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'returned' => 'bg-dark',
                            default => 'bg-light text-dark',
                        };
                    @endphp
                    <span class="badge {{ $orderStatusClass }}">{{ ucfirst($order->order_status ?? 'N/A') }}</span>
                </td>
                <td>
                    @php
                        $paymentStatusClass = match($order->payment_status) {
                            'pending' => 'bg-warning',
                            'paid' => 'bg-success',
                            'failed' => 'bg-danger',
                            'refunded' => 'bg-info',
                            default => 'bg-light text-dark',
                        };
                    @endphp
                    <span class="badge {{ $paymentStatusClass }}">{{ ucfirst($order->payment_status ?? 'N/A') }}</span>
                </td>
                <td>{{ $order->created_at ? $order->created_at->format('M j, Y') : 'N/A' }}</td>
                <td class="text-end">
                    <a class="btn btn-warning btn-sm" href="{{ route('admin.orders.details', $order->id) }}"><i class="bi bi-eye" aria-hidden="true"></i></a>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $order->id }}" data-url="{{ route('admin.orders.destroy', $order->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    {{ $orders->links() }}