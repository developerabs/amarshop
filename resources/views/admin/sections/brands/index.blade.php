@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">All Brands</h1>
        </div>
    </div>
    <div class="heading-actions"><a class="btn btn-primary btn-sm" href="{{ route('admin.brands.create') }}"><i class="bi bi-person-plus" aria-hidden="true"></i> Add Brand</a></div>
    </div>

    <section class="panel">
    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>All Brands</span></h2></div><input class="form-control form-control-sm table-search" type="search" placeholder="Search orders" data-table-search="ordersTable" aria-label="Search orders"></div>
    <div class="table-responsive">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands ?? [] as $brand)
                <tr>
                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                    <td></td>
                    <td>{{ $brand->name ?? 'N/A' }}</td>
                    <td>
                        @if($brand->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $brand->created_at ?$brand->created_at->format('M j, Y') : 'N/A' }}</td>
                    <td class="text-end">
                        <a class="btn btn-light btn-sm" href="{{ route('admin.brands.edit', $brand->id) }}">Edit</a>
                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-light btn-sm" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No brands found.</td>
                </tr>
                @endforelse
             </tbody>
         </table>
     </div>
    </section>
</div>
@endsection