<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems')->latest()->paginate(20);
        return view('admin.sections.orders.index', compact('orders'));
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
