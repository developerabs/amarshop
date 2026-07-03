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
                'admin.brands.*'
              ];
              $ecomActive = request()->routeIs(...$ecomRoutes);
          @endphp
          <a class="nav-link nav-group-toggle {{ $ecomActive ? 'active' : '' }}" href="#ecomMenu" data-bs-toggle="collapse" aria-expanded="{{ $ecomActive ? 'true' : 'false' }}">
            <span class="nav-icon"><i class="bi bi-bag" aria-hidden="true"></i></span>
            <span class="nav-text">Ecom</span>
            <span class="nav-caret"><i class="bi bi-chevron-down"></i></span>
          </a>
          <div class="collapse {{ $ecomActive ? 'show' : '' }}" id="ecomMenu">
            <nav class="nav-group-items">
              <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <span class="nav-icon"><i class="bi bi-list" aria-hidden="true"></i></span>
                <span class="nav-text">Categories</span>
              </a>
              <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                <span class="nav-icon"><i class="bi bi-tag" aria-hidden="true"></i></span>
                <span class="nav-text">Brands</span>
              </a>
            </nav>
          </div>
        </div>
        <a class="nav-link" href="profile.html">
          <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
          <span class="nav-text">Profile</span>
        </a>
        <a class="nav-link" href="charts.html">
          <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
          <span class="nav-text">Charts</span>
        </a>
        <a class="nav-link" href="tables.html">
          <span class="nav-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
          <span class="nav-text">Tables</span>
        </a>
        <a class="nav-link" href="forms.html">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Forms</span>
        </a>
        <a class="nav-link" href="components.html">
          <span class="nav-icon"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i></span>
          <span class="nav-text">Components</span>
        </a>
        <a class="nav-link" href="alerts.html">
          <span class="nav-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
          <span class="nav-text">Alerts</span>
        </a>
        <a class="nav-link" href="modals.html">
          <span class="nav-icon"><i class="bi bi-window-stack" aria-hidden="true"></i></span>
          <span class="nav-text">Modals</span>
        </a>
        <a class="nav-link" href="{{ route('admin.site-settings') }}">
          <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
          <span class="nav-text">Settings</span>
        </a>
        <a class="nav-link" href="blank.html">
          <span class="nav-icon"><i class="bi bi-file-earmark" aria-hidden="true"></i></span>
          <span class="nav-text">Blank Page</span>
        </a>
      </nav>

      <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('admin/images/avatar/avatar.jpg') }}" alt="Admin Hasan">
        <strong>Admin Hasan</strong>
        <small>Active Workspace</small>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">System running smoothly</span>
      </div>
    </aside>