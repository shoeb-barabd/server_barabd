<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>@yield('title','barabd server')</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="title" content="barabd Server Dashboard"/>
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />

  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
  <link rel="preload" href="{{ asset(url('front')) }}./css/adminlte.css" as="style" />
  <meta name="supported-color-schemes" content="light dark" />

  <!-- All Fonts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"
        media="print" onload="this.media='all'">

  <!-- 3rd-party styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" crossorigin="anonymous"/>

  <!-- AdminLTE core -->
  <link rel="stylesheet" href="{{ asset(url('back/css/adminlte.css')) }}" />

  <!-- Sidebar/Topbar theming -->
  <style>
    .app-sidebar {
      background: linear-gradient(180deg, #2348d3 0%, #0f8f87 100%) !important;
      color: #f8fbff !important;
    }
    .app-sidebar .sidebar-brand {
      background: #ffffff;
      padding: 0.65rem 1rem;
      margin: 0.35rem 0.35rem 0.5rem;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
    .app-sidebar .sidebar-brand .brand-link {
      display: flex;
      align-items: center;
      gap: .65rem;
      color: #0f172a !important;
    }
    .app-sidebar .sidebar-brand .brand-image {
      width: 48px;
      height: 48px;
      object-fit: contain;
      padding: 1px;
    }
    .app-sidebar .sidebar-brand .brand-text {
      color: #0047b6 !important;
      font-weight: 900;
    }
    .app-sidebar .brand-link,
    .app-sidebar .brand-text,
    .app-sidebar .nav-link,
    .app-sidebar .nav-link p,
    .app-sidebar .nav-link i,
    .app-sidebar .nav-header {
      color: #f8fbff !important;
    }
    .app-sidebar .nav-link {
      border-radius: 12px;
      margin: 0.15rem 0.35rem;
      transition: background 150ms ease, transform 150ms ease;
    }
    .app-sidebar .nav-link:hover {
      background: rgba(255,255,255,0.14);
      transform: translateY(-1px);
    }
    .app-sidebar .nav-link.active,
    .app-sidebar .menu-open > .nav-link {
      background: rgba(255,255,255,0.18) !important;
      color: #fff !important;
    }
    .app-sidebar .nav-header {
      opacity: 0.85;
      letter-spacing: .05em;
    }
    .app-sidebar .btn-outline-light {
      border-color: rgba(255,255,255,0.7);
      color: #fff;
    }
    .app-sidebar .btn-outline-light:hover {
      background: #dc3545;
      border-color: #dc3545;
      color: #fff;
    }
    .app-header.navbar {
      background: #ffffff !important;
      border-bottom: 1px solid #e5e7eb;
      box-shadow: 0 4px 18px rgba(15,23,42,0.06);
      min-height: 58px;
    }
    .app-header .nav-link {
      color: #1f2937 !important;
      font-weight: 600;
      transition: color 150ms ease, transform 150ms ease;
    }
    .app-header .nav-link:hover {
      color: #2563eb !important;
      transform: translateY(-1px);
    }
    .app-header .navbar-badge {
      top: 2px;
      right: -6px;
      font-weight: 700;
    }
    .app-header .dropdown-menu {
      border-radius: 12px;
      box-shadow: 0 18px 40px rgba(15,23,42,0.12);
    }
    .app-header .user-menu .dropdown-toggle {
      font-weight: 700;
      color: #111827 !important;
    }
    .app-header .user-menu .dropdown-menu {
      padding: 0.75rem;
    }

    /* Content area polish (applies to all admin pages) */
    .app-content {
      background: radial-gradient(circle at 20% 20%, rgba(37,99,235,0.08), transparent 35%),
                  radial-gradient(circle at 80% 0%, rgba(16,185,129,0.08), transparent 32%),
                  #f7f8fb;
    }
    .app-content .card {
      border-radius: 16px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 14px 35px rgba(15,23,42,0.08);
      overflow: hidden;
      animation: riseIn 280ms ease;
      background: linear-gradient(135deg, #ffffff, #f9fbff);
    }
    .app-content .card-header {
      border-bottom: 1px solid #e5e7eb;
      background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(247,249,255,0.9));
      padding: 0.9rem 1rem;
    }
    .app-content .card-footer {
      border-top: 1px solid #e5e7eb;
      background: #fafbff;
    }
    .app-content .form-control {
      border-radius: 12px;
      border: 1px solid #dfe3ec;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
    }
    .app-content .btn {
      border-radius: 10px;
    }
    .app-content .table {
      margin-bottom: 0;
    }
    .app-content .table thead th {
      background: #f4f6fb;
      border-bottom: 1px solid #e5e7eb;
      color: #111827;
      font-weight: 700;
    }
    .app-content .table tbody tr:hover {
      background: rgba(37,99,235,0.05);
    }
    @keyframes riseIn {
      from { transform: translateY(6px); opacity: 0; }
      to   { transform: translateY(0); opacity: 1; }
    }
  </style>

  @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
  @include('layouts.partials.header')

  @include('layouts.partials.sidebar')

  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12"><h3 class="mb-0">@yield('page_title','Dashboard')</h3></div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">



        @yield('content')





      </div>
    </div>
  </main>

  @include('layouts.partials.footer')
</div>

<!-- Core JS (vendor) -->
<script src="https://cdn.jsdelivr.net/npm/overlaysscrollbars@2.11.0/browser/overlaysscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!-- AdminLTE -->
<script src="{{ asset(url('back/js/adminlte.js')) }}"></script>

<!-- Sortable (reorderable cards) -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>

<!-- Optional libs to be used by pages via @push('scripts') -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" crossorigin="anonymous"></script>

<script>
  // OverlayScrollbars for sidebar
  document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');
    if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars) {
      OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true }
      });
    }
  });

  // Drag cards inside .connectedSortable regions
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.connectedSortable').forEach((el) => {
      new Sortable(el, { group: 'shared', handle: '.card-header' });
      el.querySelectorAll('.card-header').forEach(h => h.style.cursor = 'move');
    });
  });
</script>

@stack('scripts')
</body>
</html>
