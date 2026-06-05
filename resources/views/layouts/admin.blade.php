<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>@yield('title', 'Dashboard - RÉUTILISER')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('dashboard-assets/images/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('dashboard-assets/images/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('dashboard-assets/images/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('dashboard-assets/images/favicon_io/site.webmanifest') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Hanken+Grotesk:wght@300;400;600;800&display=swap" rel="stylesheet"/>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
  <link href="{{ asset('dashboard-assets/css/dashboard.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  @stack('css')
</head>

<body>
  <div id="overlay" class="overlay"></div>

  <!-- TOPBAR -->
  <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
    <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
      <i data-lucide="menu"></i>
    </button>

    <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
      <i data-lucide="menu"></i>
    </button>

    <div class="ms-auto">
      <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
        <li>
          <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
            <i data-lucide="bell" class="icon-sm"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">2<span class="visually-hidden">unread messages</span></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
            <ul class="list-unstyled p-0 m-0">
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('dashboard-assets/images/avatar/avatar-1.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">New order received</p>
                    <p class="mb-1">Order #12345 has been placed</p>
                    <div class="text-secondary">5 minutes ago</div>
                  </div>
                </div>
              </li>
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('dashboard-assets/images/avatar/avatar-4.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">New user registered</p>
                    <p class="mb-1">User @john_doe has signed up</p>
                    <div class="text-secondary">30 minutes ago</div>
                  </div>
                </div>
              </li>
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('dashboard-assets/images/avatar/avatar-2.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">Payment confirmed</p>
                    <p class="mb-1">Payment of $299 has been received</p>
                    <div class="text-secondary">1 hour ago</div>
                  </div>
                </div>
              </li>
              <li class="px-4 py-3 text-center">
                <a href="#" class="text-primary">View all notifications</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="ms-3 dropdown">
          <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('dashboard-assets/images/avatar/avatar-1.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
          </a>
          <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
            <div>
              <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3">
                <img src="{{ asset('dashboard-assets/images/avatar/avatar-1.jpg') }}" alt="" class="avatar avatar-md rounded-circle" />
                <div>
                  <h4 class="mb-0 small">{{ auth()->user()->name ?? 'User' }}</h4>
                  <p class="mb-0 small">{{ auth()->user()->username ?? 'user' }}</p>
                </div>
              </div>
              <div class="p-3 d-flex flex-column gap-1 small lh-lg">
                <a href="#!" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                  <i data-lucide="home" style="width: 14px; height: 14px;"></i> <span>Home</span>
                </a>
                <a href="#!" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                  <i data-lucide="inbox" style="width: 14px; height: 14px;"></i> <span>Inbox</span>
                </a>
                <a href="{{ url('/') }}" target="_blank" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                  <i data-lucide="external-link" style="width: 14px; height: 14px;"></i> <span>Lihat Toko</span>
                </a>
                <a href="#!" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                  <i data-lucide="user-cog" style="width: 14px; height: 14px;"></i> <span>Account Settings</span>
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-danger text-decoration-none d-flex align-items-center gap-2">
                  <i data-lucide="log-out" style="width: 14px; height: 14px;"></i> <span>Logout</span>
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- SIDEBAR -->
  @include('admin.partials.sidebar')

  <!-- MAIN CONTENT -->
  <main id="content" class="content py-10">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-none alert-flash" role="alert" data-type="success">
          <i data-lucide="check-circle" class="me-1"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-none alert-flash" role="alert" data-type="error">
          <i data-lucide="alert-circle" class="me-1"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-none alert-flash" role="alert" data-type="info">
          <i data-lucide="info" class="me-1"></i> {{ session('info') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @yield('content')
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('dashboard-assets/js/dashboard.js') }}"></script>
  <script>
    function confirmDelete(event, message = 'Hapus item ini?') {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2a4a38',
            cancelButtonColor: '#605e59',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    $(document).ready(function () {
      lucide.createIcons();
      
      $('select[multiple]').select2({
        theme: 'bootstrap-5',
        placeholder: function () {
          return $(this).data('placeholder') || 'Pilih opsi';
        },
        width: '100%'
      });

      document.querySelectorAll('.alert-flash').forEach(function (el) {
        var type = el.dataset.type || 'info';
        var message = el.textContent.trim();
        if (message) {
          Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
          });
        }
      });
    });

    function previewImages(input, containerId) {
      var container = document.getElementById(containerId);
      if (!container) return;
      container.innerHTML = '';
      if (input.files) {
        Array.from(input.files).forEach(function (file) {
          var reader = new FileReader();
          reader.onload = function (e) {
            var wrapper = document.createElement('div');
            wrapper.className = 'col-4';
            wrapper.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail w-100" style="height:100px;object-fit:cover;">';
            container.appendChild(wrapper);
          };
          reader.readAsDataURL(file);
        });
      }
    }

    function previewSingleImage(input, imgId) {
      var img = document.getElementById(imgId);
      if (!img || !input.files || !input.files[0]) return;
      var reader = new FileReader();
      reader.onload = function (e) { img.src = e.target.result; img.classList.remove('d-none'); };
      reader.readAsDataURL(input.files[0]);
    }
  </script>
  @stack('scripts')
</body>

</html>
