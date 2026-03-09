<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('back/img/logo-big.png') }}" alt="Logo" class="brand-image opacity-75 shadow"/>
      <span class="brand-text fw-light">Server Admin</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" id="navigation">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}"
             class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Core Setup -->
        @php
          $coreActive = request()->routeIs('admin.countries.*') ||
                        request()->routeIs('admin.currencies.*') ||
                        request()->routeIs('admin.exchange-rates.*') ||
                        request()->routeIs('admin.tax-rules.*') ||
                        request()->routeIs('admin.accounts.*') ||
                        request()->routeIs('admin.contacts.*') ||
                        request()->routeIs('admin.users.*');
        @endphp

        <li class="nav-item has-treeview {{ $coreActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $coreActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-gear"></i>
            <p>
              Core Setup
              <i class="bi bi-chevron-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ms-3">
            <li class="nav-item"><a href="{{ route('admin.countries.index') }}" class="nav-link {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}"><i class="bi bi-flag"></i><p>Countries</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.currencies.index') }}" class="nav-link {{ request()->routeIs('admin.currencies.*') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i><p>Currencies</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.exchange-rates.index') }}" class="nav-link {{ request()->routeIs('admin.exchange-rates.*') ? 'active' : '' }}"><i class="bi bi-arrow-left-right"></i><p>Exchange Rates</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.tax-rules.index') }}" class="nav-link {{ request()->routeIs('admin.tax-rules.*') ? 'active' : '' }}"><i class="bi bi-percent"></i><p>Tax Rules</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.accounts.index') }}" class="nav-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}"><i class="bi bi-building"></i><p>Accounts</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"><i class="bi bi-people"></i><p>Contacts</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i><p>Users</p></a></li>
          </ul>
        </li>

        <!-- Catalog & Pricing -->
        @php
          $catalogActive = request()->routeIs('admin.locations.*') ||
                           request()->routeIs('admin.billing-cycles.*') ||
                           request()->routeIs('admin.categories.*') ||
                           request()->routeIs('admin.products.*') ||
                           request()->routeIs('admin.product-features.*') ||
                           request()->routeIs('admin.customize-features.*') ||
                           request()->routeIs('admin.add-ons.*') ||
                           request()->routeIs('admin.coupons.*') ||
                           request()->routeIs('admin.base-prices.*') ||
                           request()->routeIs('admin.feature-prices.*') ||
                           request()->routeIs('admin.add-on-prices.*') ||
                           request()->routeIs('admin.presets.*') ||
                           request()->routeIs('admin.payments.*') ||
                           request()->routeIs('admin.announcements.*');
        @endphp

        <li class="nav-item has-treeview {{ $catalogActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $catalogActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-box"></i>
            <p>
              Catalog & Pricing
              <i class="bi bi-chevron-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ms-3">
            <li class="nav-item"><a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}"><i class="bi bi-geo-alt"></i><p>Locations</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.billing-cycles.index') }}" class="nav-link {{ request()->routeIs('admin.billing-cycles.*') ? 'active' : '' }}"><i class="bi bi-calendar3"></i><p>Billing Cycles</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-folder2"></i><p>Categories</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i><p>Products</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.base-prices.index') }}" class="nav-link {{ request()->routeIs('admin.base-prices.*') ? 'active' : '' }}"><i class="bi bi-currency-exchange"></i><p>Products Base Prices</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.product-features.index') }}" class="nav-link {{ request()->routeIs('admin.product-features.*') ? 'active' : '' }}"><i class="bi bi-sliders"></i><p>Product Features</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.offers.index') }}" class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}"><i class="nav-icon bi bi-box-seam"></i><p>Product Offer</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.customize-features.index') }}" class="nav-link {{ request()->routeIs('admin.customize-features.*') ? 'active' : '' }}"><i class="bi bi-wrench-adjustable"></i><p>Customize Features</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.feature-prices.index') }}" class="nav-link {{ request()->routeIs('admin.feature-prices.*') ? 'active' : '' }}"><i class="bi bi-graph-up"></i><p>Customize Feature Prices</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.add-ons.index') }}" class="nav-link {{ request()->routeIs('admin.add-ons.*') ? 'active' : '' }}"><i class="bi bi-plus-square"></i><p>Add-ons</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.add-on-prices.index') }}" class="nav-link {{ request()->routeIs('admin.add-on-prices.*') ? 'active' : '' }}"><i class="bi bi-tag"></i><p>Add-on Prices</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><i class="bi bi-ticket-perforated"></i><p>Coupons</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.presets.index') }}" class="nav-link {{ request()->routeIs('admin.presets.*') ? 'active' : '' }}"><i class="bi bi-stars"></i><p>Presets</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"><i class="bi bi-credit-card"></i><p>Payment</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"><i class="bi bi-megaphone"></i><p>Hosting Announcements</p></a></li>

          </ul>
        </li>

        <!-- Domain Management -->
        @php
          $domainActive = request()->routeIs('admin.domain-tlds.*') ||
                          request()->routeIs('admin.domain-orders.*');
        @endphp

        <li class="nav-item has-treeview {{ $domainActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $domainActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-globe2"></i>
            <p>
              Domains
              <i class="bi bi-chevron-right right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ms-3">
            <li class="nav-item"><a href="{{ route('admin.domain-tlds.index') }}" class="nav-link {{ request()->routeIs('admin.domain-tlds.*') ? 'active' : '' }}"><i class="bi bi-tags"></i><p>TLD Pricing</p></a></li>
            <li class="nav-item"><a href="{{ route('admin.domain-orders.index') }}" class="nav-link {{ request()->routeIs('admin.domain-orders.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i><p>Domain Orders</p></a></li>
          </ul>
        </li>

        <!-- Logout  -->
        <li class="nav-header mt-3">ACCOUNT</li>
        <li class="nav-item px-3">
          <form method="post" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn btn-outline-light w-100">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </li>

      </ul>
    </nav>
  </div>
</aside>
