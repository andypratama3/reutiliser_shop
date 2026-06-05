<aside id="sidebar" class="sidebar">
  <div class="logo-area">
    <a href="{{ route('admin.dashboard') }}" class="d-inline-flex align-items-center text-decoration-none">
      <img src="{{ asset('assets_landing/logo_core.png') }}" alt="Logo" style="height: 24px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
      <span class="logo-text ms-2" style="font-weight: 800; color: #2a4a38; letter-spacing: -0.05em; font-size: 1.25rem; display: none;">RÉUTILISER</span>
    </a>
  </div>
  <ul class="nav flex-column">
    <li class="px-4 py-2"><small class="nav-text text-secondary text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">Main</small></li>
    <li>
      <a class="nav-link {{ Request::is('admin') || Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i data-lucide="layout-dashboard"></i><span class="nav-text">Dashboard</span>
      </a>
    </li>
    
    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">Katalog</small></li>
    <li>
      <a class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
        <i data-lucide="package"></i><span class="nav-text">Produk</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
        <i data-lucide="layers"></i><span class="nav-text">Kategori</span>
      </a>
    </li>

    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">Transaksi</small></li>
    <li>
      <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i data-lucide="shopping-cart"></i><span class="nav-text">Pesanan</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/promos*') ? 'active' : '' }}" href="{{ route('admin.promos.index') }}">
        <i data-lucide="ticket"></i><span class="nav-text">Promo</span>
      </a>
    </li>

    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">Administrasi</small></li>
    @if(auth()->user() && auth()->user()->isSuperAdmin())
    <li>
      <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
        <i data-lucide="users"></i><span class="nav-text">Pengguna</span>
      </a>
    </li>
    @endif
    <li>
      <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
        <i data-lucide="bar-chart-3"></i><span class="nav-text">Laporan</span>
      </a>
    </li>
    @if(auth()->user() && auth()->user()->isSuperAdmin())
    <li>
      <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
        <i data-lucide="settings"></i><span class="nav-text">Pengaturan</span>
      </a>
    </li>
    @endif

    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">Situs</small></li>
    <li>
      <a class="nav-link" href="{{ url('/') }}" target="_blank">
        <i data-lucide="external-link"></i><span class="nav-text">Lihat Toko</span>
      </a>
    </li>
    <li>
      <a class="nav-link text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i data-lucide="log-out"></i><span class="nav-text">Keluar</span>
      </a>
    </li>
  </ul>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
