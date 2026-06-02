<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>@yield('title', 'Admin - RÉUTILISER')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
        }
        body { background: #f5f6fa; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        #sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: var(--sidebar-width);
            background: #fff; border-right: 1px solid #e9ecef; z-index: 1030;
            transition: transform .3s ease, width .3s ease; overflow-y: auto;
        }
        #sidebar.collapsed { width: 70px; }
        #sidebar .logo-area {
            padding: 1rem 1.25rem; border-bottom: 1px solid #e9ecef;
            display: flex; align-items: center; gap: .75rem; height: 64px;
        }
        #sidebar .logo-area .logo-text { font-weight: 700; font-size: 1.1rem; color: #2a4a38; white-space: nowrap; overflow: hidden; }
        #sidebar.collapsed .logo-text,
        #sidebar.collapsed .nav-text { display: none; }
        #sidebar .nav-link {
            display: flex; align-items: center; gap: .75rem; padding: .625rem 1.25rem;
            color: #495057; border-radius: 0; font-size: .9rem; transition: all .15s;
        }
        #sidebar .nav-link:hover { background: #f0f1f3; color: #2a4a38; }
        #sidebar .nav-link.active { background: #e8f5e9; color: #2a4a38; font-weight: 600; border-right: 3px solid #2a4a38; }
        #sidebar .nav-link i { font-size: 1.25rem; width: 1.25rem; text-align: center; flex-shrink: 0; }
        #sidebar .nav-section { padding: 1rem 1.25rem .25rem; font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: #adb5bd; font-weight: 600; }
        #sidebar.collapsed .nav-section span { display: none; }

        #topbar {
            position: fixed; top: 0; right: 0; left: var(--sidebar-width); height: 64px;
            background: #fff; border-bottom: 1px solid #e9ecef; z-index: 1025;
            transition: left .3s ease; display: flex; align-items: center; padding: 0 1.5rem;
        }
        #topbar.full { left: 70px; }

        #content {
            margin-left: var(--sidebar-width); padding-top: 64px; transition: margin-left .3s ease;
        }
        #content.full { margin-left: 70px; }

        #sidebar.collapsed + #topbar,
        #sidebar.collapsed ~ #content { left: 70px; margin-left: 70px; }

        .overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1029;
        }
        .overlay.show { display: block; }

        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.mobile-show { transform: translateX(0); }
            #topbar { left: 0; }
            #content { margin-left: 0; }
        }

        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.06); border-radius: .75rem; }
        .card-header { background: transparent; border-bottom: 1px solid #e9ecef; padding: 1rem 1.25rem; font-weight: 600; }
        .stat-card { transition: transform .15s; }
        .stat-card:hover { transform: translateY(-2px); }
        .icon-shape { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: .5rem; }
        .icon-shape-sm { width: 36px; height: 36px; }

        .table th { font-weight: 600; font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }
        .table td { vertical-align: middle; }

        .badge { font-weight: 500; padding: .35em .65em; }
    </style>
    @stack('css')
</head>
<body>
    <div id="overlay" class="overlay"></div>

    <nav id="topbar">
        <button id="toggleBtn" class="btn btn-light btn-sm d-none d-lg-inline-flex me-3">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button>
        <button id="mobileBtn" class="btn btn-light btn-sm d-lg-none me-3">
            <i class="ti ti-menu-2"></i>
        </button>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small">{{ auth()->user()->name }}</span>
            <div class="dropdown">
                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                    <i class="ti ti-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank"><i class="ti ti-external-link me-2"></i>Lihat Toko</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="ti ti-logout me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <aside id="sidebar">
        <div class="logo-area">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <span style="width:28px;height:28px;background:#2a4a38;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;">R</span>
                <span class="logo-text">RÉUTILISER</span>
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-section"><span>Main</span></li>
            <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="ti ti-home"></i><span class="nav-text">Dashboard</span></a></li>

            <li class="nav-section"><span>Katalog</span></li>
            <li><a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="ti ti-box-seam"></i><span class="nav-text">Produk</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="ti ti-tags"></i><span class="nav-text">Kategori</span></a></li>

            <li class="nav-section"><span>Transaksi</span></li>
            <li><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="ti ti-shopping-cart"></i><span class="nav-text">Pesanan</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.promos.*') ? 'active' : '' }}" href="{{ route('admin.promos.index') }}"><i class="ti ti-discount-2"></i><span class="nav-text">Promo</span></a></li>

            @if(auth()->user()->isSuperAdmin())
                <li class="nav-section"><span>Administrasi</span></li>
                <li><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="ti ti-users"></i><span class="nav-text">Pengguna</span></a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="ti ti-report-analytics"></i><span class="nav-text">Laporan</span></a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><i class="ti ti-settings"></i><span class="nav-text">Pengaturan</span></a></li>
            @else
                <li class="nav-section"><span>Laporan</span></li>
                <li><a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="ti ti-report-analytics"></i><span class="nav-text">Laporan</span></a></li>
            @endif
        </ul>
    </aside>

    <main id="content" class="py-4 px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="ti ti-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="ti ti-info-circle me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const topbar = document.getElementById('topbar');
            const toggleBtn = document.getElementById('toggleBtn');
            const mobileBtn = document.getElementById('mobileBtn');
            const overlay = document.getElementById('overlay');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('full');
                    topbar.classList.toggle('full');
                });
            }

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function () {
                    sidebar.classList.add('mobile-show');
                    overlay.classList.add('show');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('mobile-show');
                    overlay.classList.remove('show');
                });
            }

            const currentRoute = '{{ request()->route() ? request()->route()->getName() : '' }}';
            document.querySelectorAll('#sidebar .nav-link').forEach(function (link) {
                if (link.getAttribute('href') === window.location.pathname) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
