<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const ORDER_STATUSES = [
        'pending',
        'confirmed',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
        'returned',
    ];

    private const PAYMENT_STATUSES = [
        'pending',
        'paid',
        'failed',
        'refunded',
    ];

    public function index()
    {
        return view('admin.sections.orders.index', [
            'orderStatuses' => self::ORDER_STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
            'order_status' => 'nullable|string',
            'payment_status' => 'nullable|string',
        ]);

        $ordersQuery = Order::with(['orderItems', 'user']);

        if ($request->has('query') && !empty($request->input('query'))) {
            $searchTerm = trim((string) $request->input('query'));

            $ordersQuery->where(function ($q) use ($searchTerm) {
                $q->where('order_no', 'ilike', '%' . $searchTerm . '%')
                    ->orWhere('payment_method', 'ilike', '%' . $searchTerm . '%')
                    ->orWhere('guest_id', 'ilike', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'ilike', '%' . $searchTerm . '%')
                            ->orWhere('email', 'ilike', '%' . $searchTerm . '%');
                    });
            });
        }

        $orderStatus = (string) $request->input('order_status', '');
        if ($orderStatus === 'active') {
            $ordersQuery->whereIn('order_status', ['confirmed', 'processing', 'shipped']);
        } elseif (in_array($orderStatus, self::ORDER_STATUSES, true)) {
            $ordersQuery->where('order_status', $orderStatus);
        }

        $paymentStatus = (string) $request->input('payment_status', '');
        if (in_array($paymentStatus, self::PAYMENT_STATUSES, true)) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }

        $orders = $ordersQuery->latest()->paginate(20)->withQueryString();

        return view('admin.components.data-table.orders-table', compact('orders'));
    }

    public function active()
    {
        return redirect()->route('admin.orders.index', ['order_status' => 'active']);
    }

    public function pending()
    {
        return redirect()->route('admin.orders.index', ['order_status' => 'pending']);
    }

    public function completed()
    {
        return redirect()->route('admin.orders.index', ['order_status' => 'delivered']);
    }

    public function cancelled()
    {
        return redirect()->route('admin.orders.index', ['order_status' => 'cancelled']);
    }

    public function show(Order $order)
    {
        return redirect()->route('admin.orders.details', $order->id);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:' . implode(',', self::ORDER_STATUSES),
        ]);
        if ($order->order_status === 'delivered') {
            return redirect()->back()->with('error', 'Cannot change the status of a delivered order.');
        }
        if ($order->order_status === 'cancelled') {
            return redirect()->back()->with('error', 'Cannot change the status of a cancelled order.');
        }
        if ($order->order_status === 'returned') {
            return redirect()->back()->with('error', 'Cannot change the status of a returned order.');
        }
        if ($order->order_status === 'pending' && $validated['order_status'] === 'delivered') {
            return redirect()->back()->with('error', 'Cannot mark a pending order as delivered.');
        }
        if ($order->order_status === 'pending' && $validated['order_status'] === 'returned') {
            return redirect()->back()->with('error', 'Cannot mark a pending order as returned.');
        }
        // stock update when cancelled or returned
        if (in_array($validated['order_status'], ['cancelled', 'returned'], true)) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('total_stock', $item->quantity);
                }
            }
        }

        $order->update([
            'order_status' => $validated['order_status'],
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:' . implode(',', self::PAYMENT_STATUSES),
        ]);
        if ($order->payment_status === 'paid' && $validated['payment_status'] !== 'paid') {
            return redirect()->back()->with('error', 'Cannot change the payment status of a paid order.');
        }
        if ($order->payment_status === 'refunded' && $validated['payment_status'] !== 'refunded') {
            return redirect()->back()->with('error', 'Cannot change the payment status of a refunded order.');
        }
        if ($order->payment_status === 'failed' && $validated['payment_status'] !== 'failed') {
            return redirect()->back()->with('error', 'Cannot change the payment status of a failed order.');
        }
        if ($order->payment_status === 'pending' && $validated['payment_status'] === 'refunded') {
            return redirect()->back()->with('error', 'Cannot mark a pending order as refunded.');
        }

        $order->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    public function details(Order $order)
    {
        $order->load(['orderItems', 'user', 'orderAddress']);

        return view('admin.sections.orders.details', [
            'order' => $order,
            'orderStatuses' => self::ORDER_STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }
}
