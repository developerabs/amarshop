<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <meta name="author" content="adminHMD">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard | adminHMD</title>


  <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
  @stack('styles')
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('admin/js/main.js') }}"></script>
  @stack('scripts')
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
  @if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error("{{ $error }}");
        </script>
    @endforeach
  @endif
  @if(session('modal'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        let modal = new bootstrap.Modal(
            document.getElementById('{{ session('modal') }}')
        );
        modal.show();
    });
  </script>
  @endif
  <script>
    $(document).on('click', '.delete-btn', function () {
      let url = $(this).data('url');
      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {

          if (result.isConfirmed) {

              let form = document.getElementById('delete-form');
              form.action = url;
              form.submit();
          }
      });
    });
  </script>
</body>
</html>
