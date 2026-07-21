<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">adminHMD</span>
            <span class="brand-subtitle">Admin Template</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" aria-current="page">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
        <div class="nav-group">
          @php
              $ecomRoutes = [
                'admin.categories.*', 
                'admin.brands.*',
                'admin.variant-types.*',
                'admin.variant-values.*',
                'admin.products.*',
              ];
              $ecomActive = request()->routeIs(...$ecomRoutes);
          @endphp
          <a class="nav-link nav-group-toggle {{ $ecomActive ? 'active' : '' }}" href="#ecomMenu" data-bs-toggle="collapse" aria-expanded="{{ $ecomActive ? 'true' : 'false' }}">
            <span class="nav-icon"><i class="bi bi-bag" aria-hidden="true"></i></span>
            <span class="nav-text">Ecom</span>
            <span class="nav-caret"><i class="bi bi-chevron-down"></i></span>
          </a>
          <div class="collapse {{ $ecomActive ? 'show' : '' }} sub-menu" id="ecomMenu">
            <nav class="nav-group-items">
              <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">Categories</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                <span class="nav-icon"><i class="bi bi-tag" aria-hidden="true"></i></span>
                <span class="nav-text">Brands</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <span class="nav-icon"><i class="bi bi-box" aria-hidden="true"></i></span>
                <span class="nav-text">Products</span>
              </a>
            </nav>
          </div>
        </div>
        <div class="nav-group">
          @php
              $orderRoutes = [
                'admin.orders.*', 
              ];
              $orderActive = request()->routeIs(...$orderRoutes);
          @endphp
          <a class="nav-link nav-group-toggle {{ $orderActive ? 'active' : '' }}" href="#ordersMenu" data-bs-toggle="collapse" aria-expanded="{{ $orderActive ? 'true' : 'false' }}">
            <span class="nav-icon"><i class="bi bi-cart" aria-hidden="true"></i></span>
            <span class="nav-text">Orders</span>
          </a>
          <div class="collapse {{ $orderActive ? 'show' : '' }} sub-menu" id="ordersMenu">
            <nav class="nav-group-items">
              <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">All Orders</span>
              </a>
            </nav>
          </div>
        </div>
        <div class="nav-group">
          @php
              $siteSectionRoutes = [
                'admin.sliders.*', 
                'admin.banners.*',
                'admin.pages.*',
              ];
              $siteSectionActive = request()->routeIs(...$siteSectionRoutes);
          @endphp
          <a class="nav-link nav-group-toggle {{ $siteSectionActive ? 'active' : '' }}" href="#siteSectionsMenu" data-bs-toggle="collapse" aria-expanded="{{ $siteSectionActive ? 'true' : 'false' }}">
            <span class="nav-icon"><i class="bi bi-cart" aria-hidden="true"></i></span>
            <span class="nav-text">Site Sections</span>
            <span class="nav-caret"><i class="bi bi-chevron-down"></i></span>
          </a>
          <div class="collapse sub-menu {{ $siteSectionActive ? 'show' : '' }}" id="siteSectionsMenu">
            <nav class="nav-group-items">
              <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">Sliders</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">Banners</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">Pages</span>
              </a>
            </nav>
          </div>
        </div>
        <a class="nav-link" href="{{ route('admin.users.index') }}">
          <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
          <span class="nav-text">Users</span>
        </a>
        <div class="nav-group">
          @php
              $settingsRoutes = [
                'admin.settings.*',
                'admin.shipping-charges.*',
              ];
              $settingsActive = request()->routeIs(...$settingsRoutes);
          @endphp
          <a class="nav-link nav-group-toggle {{ $settingsActive ? 'active' : '' }}" href="#settingsMenu" data-bs-toggle="collapse" aria-expanded="{{ $settingsActive ? 'true' : 'false' }}">
            <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
            <span class="nav-text">Settings</span>
            <span class="nav-caret"><i class="bi bi-chevron-down"></i></span>
          </a>
          <div class="collapse {{ $settingsActive ? 'show' : '' }} sub-menu" id="settingsMenu">
            <nav class="nav-group-items">
              <a class="nav-link {{ request()->routeIs('admin.settings.general-settings') ? 'active' : '' }}" href="{{ route('admin.settings.general-settings') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">General Settings</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.shipping-charges.*') ? 'active' : '' }}" href="{{ route('admin.shipping-charges.index') }}">
                <span class="nav-icon"><i class="bi bi-box" aria-hidden="true"></i></span>
                <span class="nav-text">Shipping Charges</span>
              </a>
            </nav>
          </div>
        </div>
      </nav>

      <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('admin/images/avatar/avatar.jpg') }}" alt="{{ auth()->user()->name }}">
        <strong>{{ auth()->user()->name }}</strong>
        <small>Active Workspace</small>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">System running smoothly</span>
      </div>
    </aside>