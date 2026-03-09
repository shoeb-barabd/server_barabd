<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('back/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image opacity-75 shadow"/>
      <span class="brand-text fw-light">barabd Admin</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i><p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">CORE SETUP</li>
        <li class="nav-item">
          <a href="{{ route('admin.countries.index') }}" class="nav-link">
            <i class="nav-icon bi bi-flag"></i><p>Countries</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.currencies.index') }}" class="nav-link">
            <i class="nav-icon bi bi-cash-coin"></i><p>Currencies</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.exchange-rates.index') }}" class="nav-link">
            <i class="nav-icon bi bi-arrow-left-right"></i><p>Exchange Rates</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.tax-rules.index') }}" class="nav-link">
            <i class="nav-icon bi bi-percent"></i><p>Tax Rules</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.accounts.index') }}" class="nav-link">
            <i class="nav-icon bi bi-building"></i><p>Accounts</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.contacts.index') }}" class="nav-link">
            <i class="nav-icon bi bi-people"></i><p>Contacts</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon bi bi-person-badge"></i><p>Users</p>
          </a>
        </li>

        <li class="nav-header mt-3">ACCOUNT</li>
        <li class="nav-item px-3">
          <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </li>

      </ul>
    </nav>
  </div>
</aside>
