<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $user = User::query();
        $totalUsers = $user->count();
        $activeUsers = (clone $user)->where('status', 'true')->count();
        $inactiveUsers = (clone $user)->where('status', 'false')->count();

        $orders = Order::query();
        $totalOrders = $orders->count();
        $pendingOrders = (clone $orders)->where('order_status', 'pending')->count();
        $completedOrders = (clone $orders)->where('order_status', 'delivered')->count();
        $canceledOrders = (clone $orders)->where('order_status', 'cancelled')->count();
        $refundedOrders = (clone $orders)->where('order_status', 'returned')->count();
        $recentOrders = $orders->latest()->take(5)->get();

        $products = Product::query();
        $totalProducts = $products->count();
        $activeProducts = (clone $products)->where('status', 'true')->count();
        $inactiveProducts = (clone $products)->where('status', 'false')->count();

        // order monthly chart data
       $monthlyOrders = Order::selectRaw("DATE_PART('month', created_at) as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupByRaw("DATE_PART('month', created_at)")
            ->orderByRaw("DATE_PART('month', created_at)")
            ->get()
            ->mapWithKeys(function ($item) {
                $monthName = Carbon::create()->month((int) $item->month)->format('F'); // July

                return [$monthName => $item->count];
            });
        // dd($monthlyOrders);
        return view('admin.dashboard', compact('authUser', 'totalUsers', 'activeUsers', 'inactiveUsers', 'totalOrders', 'pendingOrders', 'completedOrders', 'canceledOrders', 'refundedOrders', 'recentOrders', 'monthlyOrders', 'totalProducts', 'activeProducts', 'inactiveProducts'));
    }
}
