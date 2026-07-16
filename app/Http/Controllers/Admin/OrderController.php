<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.sections.orders.index');
    }
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $query = Order::with('orderItems');

        if ($request->has('query') && !empty($request->input('query'))) {
            $searchTerm = $request->input('query');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_no', 'like', '%' . $searchTerm . '%');
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.components.data-table.orders-table', compact('orders'));
    }

    public function active()
    {
        $orders = Order::with('orderItems')->where('order_status', 'active')->latest()->paginate(20);
        return view('admin.sections.orders.active', compact('orders'));
    }

    public function pending()
    {
        $orders = Order::with('orderItems')->where('order_status', 'pending')->latest()->paginate(20);
        return view('admin.sections.orders.pending', compact('orders'));
    }

    public function completed()
    {
        $orders = Order::with('orderItems')->where('order_status', 'completed')->latest()->paginate(20);
        return view('admin.sections.orders.completed', compact('orders'));
    }

    public function cancelled()
    {
        $orders = Order::with('orderItems')->where('order_status', 'cancelled')->latest()->paginate(20);
        return view('admin.sections.orders.cancelled', compact('orders'));
    }
    public function details(Order $order)
    {
        $order->load('orderItems');
        return view('admin.sections.orders.details', compact('order'));
    }
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }
}
