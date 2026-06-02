<aside id="sidebar" class="sidebar">
  <div class="logo-area">
    <a href="{{ route('admin.dashboard') }}" class="d-inline-flex">
      <img src="{{ asset('dashboard-assets/images/logo-icon.svg') }}" alt="" width="24">
      <span class="logo-text ms-2"><img src="{{ asset('dashboard-assets/images/logo.svg') }}" alt=""></span>
    </a>
  </div>
  <ul class="nav flex-column">
    <li class="px-4 py-2"><small class="nav-text text-secondary text-uppercase fw-semibold">Main</small></li>
    <li>
      <a class="nav-link {{ Request::is('admin') || Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/products*') && !Request::is('admin/products/create*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
        <i class="ti ti-box-seam"></i><span class="nav-text">Inventory</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/products/create*') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">
        <i class="ti ti-plus"></i><span class="nav-text">Add Product</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i class="ti ti-truck-delivery"></i><span class="nav-text">Orders</span>
      </a>
    </li>
    <li>
      <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
        <i class="ti ti-receipt"></i><span class="nav-text">Reports</span>
      </a>
    </li>

    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold">Management</small></li>
    @can('superadmin')
    <li>
      <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
        <i class="ti ti-users"></i><span class="nav-text">Users</span>
      </a>
    </li>
    @endcan
    <li>
      <a class="nav-link {{ Request::is('admin/promos*') ? 'active' : '' }}" href="{{ route('admin.promos.index') }}">
        <i class="ti ti-discount-2"></i><span class="nav-text">Promos</span>
      </a>
    </li>
    @can('superadmin')
    <li>
      <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
        <i class="ti ti-settings"></i><span class="nav-text">Settings</span>
      </a>
    </li>
    @endcan

    <li class="px-4 pt-4 pb-2"><small class="nav-text text-secondary text-uppercase fw-semibold">Site</small></li>
    <li>
      <a class="nav-link" href="{{ route('home') }}">
        <i class="ti ti-arrow-left"></i><span class="nav-text">Back to Site</span>
      </a>
    </li>
    <li>
      <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="ti ti-logout"></i><span class="nav-text">Sign Out</span>
      </a>
    </li>
  </ul>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
