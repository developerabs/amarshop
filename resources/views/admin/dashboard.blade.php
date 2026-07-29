@extends('admin.layouts.master') 
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">Overview</p>
          <h1 class="h3 mb-1">Dashboard</h1>
          <p class="text-muted mb-0">Monitor performance, sales, users, and support from one clean workspace.</p>
        </div>
      </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-success">
          <div class="metric-top">
            <span class="metric-label">Orders</span>
            <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalOrders ?? 0 }}</div>
          <div class="metric-meta">
            <span class="text-success">({{ $completedOrders ?? 0 }} Completed)</span>
            <span class="text-info">({{ $pendingOrders ?? 0 }} Pending)</span>
            <span class="text-danger">({{ $canceledOrders ?? 0 }} Canceled)</span>
            <span class="text-warning">({{ $refundedOrders ?? 0 }} Refunded)</span>
          </div>
        </article>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-primary">
          <div class="metric-top">
            <span class="metric-label">Products</span>
            <span class="metric-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalProducts ?? 0 }}</div>
          <div class="metric-meta">
            <span class="text-success">({{ $activeProducts ?? 0 }} Active)</span>
            <span class="text-danger">({{ $inactiveProducts ?? 0 }} Inactive)</span>
          </div>
        </article>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-warning">
          <div class="metric-top">
            <span class="metric-label">Customers</span>
            <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
          <div class="metric-meta">
            <span class="text-success">({{ $activeUsers ?? 0 }} Active)</span>
            <span class="text-danger">({{ $inactiveUsers ?? 0 }} Inactive)</span>
          </div>
        </article>
      </div>
    </section>

    <section class="row g-3 mt-1">
      <div class="col-12 col-xl-12">
        <div class="panel">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Sales Performance</span></h2>
              <p class="text-muted mb-0">Monthly revenue compared with operational targets.</p>
            </div>
          </div>

          <div class="chart-bars" aria-label="Sales performance chart">
            @foreach ($monthlyOrders ?? [] as $month => $count)
                <div class="chart-column bar-{{ $count }}"><span></span><small>{{ $month }}</small></div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    <section class="panel mt-3">
      <div class="panel-header">
        <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Recent Orders</span></h2>
            <p class="text-muted mb-0">Latest orders placed by customers.</p>
        </div>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.orders.index') }}">View All</a>
      </div>
      <div class="table-responsive">
        @include('admin.components.data-table.orders-table-dashboard', ['orders' => $recentOrders ?? []])
      </div>
    </section>
  </div>
@endsection