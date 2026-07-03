<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Dashboard | adminHMD</title>

  <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>
    @include('admin.inc.aside')
    <div class="admin-main">
      @include('admin.inc.nav')
      <main class="dashboard-content">
        @yield('content')
      </main>

      @include('admin.inc.footer')
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="{{ asset('admin/js/main.js') }}"></script>
  @if(session('success'))
  <script>
      toastr.success("{{ session('success') }}");
  </script>
  @endif

  @if(session('error'))
  <script>
      toastr.error("{{ session('error') }}");
  </script>
  @endif
</body>
</html>
