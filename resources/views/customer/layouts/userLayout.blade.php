<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
   <title>@yield('title', 'BARABD Hosting')</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

  <style>
    body {
      background: radial-gradient(circle at 12% 20%, rgba(13,110,253,.08), transparent 32%),
                  radial-gradient(circle at 85% 10%, rgba(72,173,241,.08), transparent 28%),
                  #f5f7fb;
      font-size: 0.95rem;
      color: #24292f;
    }

    .top-bar {
      background: linear-gradient(90deg, #0d6efd, #4bb6f3);
      color: #fff;
      border-bottom: 0;
      font-size: 0.85rem;
      padding: 4px 0;
    }
    .top-bar a { color: #fff; text-decoration: none; }

    .logo-text {
      font-weight: 800;
      font-size: 1.2rem;
      letter-spacing: .03em;
      color: inherit;
      font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    .brand-logo{ height: 40px; width: auto; }
    .brand-mark{ color: #009970; } /* keeps "Data Center" green */
    .brand-red{ color: #e63946; }
    .brand-green{ color: #009970; }

    .navbar {
      border: 1px solid #e6ebf2;
      background-color: #ffffff;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
      border-radius: 12px;
      margin-top: 0;
      padding: 10px 18px;
      position: sticky;
      top: 0;
      z-index: 1030;
      transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }
    .navbar:hover{
      transform: translateY(-1px);
      box-shadow: 0 16px 36px rgba(15,23,42,0.12);
      border-color: #dce6f3;
    }

    .nav-link {
      font-size: 0.92rem;
      padding-inline: 0.95rem !important;
      transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
      border-radius: .35rem;
      font-weight: 600;
    }

    .nav-link.active {
      font-weight: 700;
      color: #0d6efd !important;
      box-shadow: inset 0 -2px 0 #0d6efd;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link:focus {
      background-color: #0d6efd;
      color: #fff !important;
      box-shadow: 0 6px 16px rgba(13,110,253,0.25);
    }

    /* Dropdown show on hover (desktop) */
    @media (min-width: 992px) {
      .navbar .dropdown:hover > .dropdown-menu {
        display: block;
      }
      .navbar .dropdown-menu {
        margin-top: 0;
      }
    }

    .dropdown-menu {
      border-radius: 10px;
      border: 1px solid #e6ebf2;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .dropdown-menu .dropdown-item {
      font-size: 0.9rem;
      padding: 10px 14px;
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: #0d6efd;
      color: #fff;
    }

    .main-wrapper {
      padding: 22px 0 40px;
      position: relative;
    }
    .main-wrapper::before{
      content:"";
      position:absolute;
      inset:0;
      background: radial-gradient(circle at 20% 60%, rgba(13,110,253,.06), transparent 32%),
                  radial-gradient(circle at 80% 80%, rgba(75,182,243,.08), transparent 38%);
      pointer-events:none;
      z-index:-1;
    }

    .card-custom {
      background: #fff;
      border-radius: 12px;
      border: 1px solid #e6ebf2;
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
      margin-bottom: 1rem;
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
    }
    .card-custom:hover{
      transform: translateY(-3px);
      border-color: #d8e4f5;
      box-shadow: 0 14px 32px rgba(15,23,42,0.12);
      background: linear-gradient(180deg,#ffffff 0%,#f9fbff 100%);
    }

    .card-custom-header {
      padding: .7rem 1rem;
      border-bottom: 1px solid #e9edf4;
      font-weight: 700;
      font-size: 0.96rem;
      background: #f8fafc;
      border-radius: 12px 12px 0 0;
      border-left: 3px solid #0d6efd1f;
      transition: border-color .2s ease, box-shadow .2s ease;
    }

    .card-custom-body {
      padding: .9rem 1rem;
    }

    /* header toggle icon */
    .card-custom-header .toggle-btn {
      border: 0;
      background: transparent;
      padding: 0;
      margin: 0;
      color: #6c757d;
    }

    .card-custom-header .toggle-icon {
      font-size: 1rem;
      transition: transform 0.2s ease;
    }

    .card-custom-header .toggle-btn[aria-expanded="false"] .toggle-icon {
      transform: rotate(180deg);
    }

    .summary-box {
      text-align: center;
      padding: 1rem .5rem;
      border-radius: 12px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      margin-bottom: 1rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
      position: relative;
      overflow:hidden;
      color:#0f2230;
    }
    .summary-box .label{ color: #4b5563; }
    .summary-box .number{ color:#0f2230; }
    .summary-box::after{
      content:"";
      position:absolute;
      inset:0;
      opacity:.4;
      pointer-events:none;
    }

    .summary-box .number {
      font-size: 2rem;
      font-weight: 700;
      color: #0d6efd;
      line-height: 1.1;
    }

    .summary-box .label {
      font-size: 0.82rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .summary-box i {
      font-size: 2.2rem;
      opacity: .25;
      transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .summary-box .icon-wrapper {
      margin-bottom: .35rem;
    }
    /* Per-box color variants */
    .summary-box.sb-blue{
      border-color: #bfd4ff;
      box-shadow: 0 10px 22px rgba(37,99,235,.1);
      background: linear-gradient(145deg,#e8f1ff,#d9e9ff);
    }
    .summary-box.sb-blue .icon-wrapper{
      background: rgba(59,130,246,.12);
      color:#1d4ed8;
    }
    .summary-box.sb-green{
      border-color: #b3e4ce;
      box-shadow: 0 10px 22px rgba(16,185,129,.12);
      background: linear-gradient(145deg,#e6f7ef,#d0f0e2);
    }
    .summary-box.sb-green .icon-wrapper{
      background: rgba(16,185,129,.14);
      color:#0f9f6e;
    }
    .summary-box.sb-amber{
      border-color: #f6c7cd;
      box-shadow: 0 10px 22px rgba(239,68,68,.12);
      background: linear-gradient(145deg,#fdeef0,#f9d8dd);
    }
    .summary-box.sb-amber .icon-wrapper{
      background: rgba(239,68,68,.14);
      color:#c53030;
    }
    .summary-box.sb-purple{
      border-color: #c2dcff;
      box-shadow: 0 10px 22px rgba(59,130,246,.12);
      background: linear-gradient(145deg,#e6f1ff,#d7e6ff);
    }
    .summary-box.sb-purple .icon-wrapper{
      background: rgba(59,130,246,.14);
      color:#2563eb;
    }

    .summary-box:hover {
      transform: translateY(-4px);
      box-shadow: 0 14px 30px rgba(15, 23, 42, 0.14);
      border-color: #0d6efd;
    }

    .summary-box:hover i {
      transform: scale(1.08);
      opacity: .7;
    }
    .card-custom-header:hover{
      border-left-color:#0d6efd4d;
      box-shadow: inset 4px 0 0 rgba(13,110,253,.15);
    }

    .badge-status {
      font-size: 0.75rem;
    }

    .scroll-box {
      max-height: 190px;
      overflow-y: auto;
    }
    .scroll-box::-webkit-scrollbar{ width: 8px; }
    .scroll-box::-webkit-scrollbar-thumb{ background: #c9d4e6; border-radius: 999px; }

    @media (min-width: 992px) {
      .sidebar-sticky {
        position: sticky;
        top: 90px;
      }
    }

    .usage-bar {
      height: 6px;
      border-radius: 10px;
      overflow: hidden;
      background-color: #eee;
    }

    .usage-bar > div {
      height: 100%;
      background: linear-gradient(90deg, #0d6efd, #4bb6f3);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.25);
    }

    /* Hero glow */
    .dashboard-hero{
      position: relative;
      overflow: hidden;
      background: linear-gradient(120deg, #0d6efd, #4bb6f3);
      box-shadow: 0 14px 36px rgba(13,110,253,0.16);
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .dashboard-hero::after{
      content:"";
      position:absolute;
      inset:-40%;
      background: radial-gradient(circle at 20% 20%, rgba(255,255,255,.22), transparent 45%),
                  radial-gradient(circle at 80% 30%, rgba(255,255,255,.16), transparent 40%);
      animation: floatGlow 9s ease-in-out infinite alternate;
      pointer-events:none;
    }
    .dashboard-hero:hover{
      transform: translateY(-2px);
      box-shadow: 0 18px 42px rgba(13,110,253,0.2);
    }
    @keyframes floatGlow{
      0%{ transform: translate3d(0,0,0) scale(1); }
      100%{ transform: translate3d(8px, -6px,0) scale(1.05); }
    }

    /* Buttons */
    .btn-soft-primary{
      background: rgba(13,110,253,.08);
      color: #0d6efd;
      border:1px solid rgba(13,110,253,.18);
      transition: all .2s ease;
    }
    .btn-soft-primary:hover,
    .btn-soft-primary:focus-visible{
      background: #0d6efd;
      color:#fff;
      box-shadow: 0 10px 22px rgba(13,110,253,.22);
    }

    /* List items hover */
    .list-group-item-action{
      transition: background-color .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .list-group-item-action:hover{
      background:#f3f7ff;
      transform: translateX(2px);
      box-shadow: inset 3px 0 0 #0d6efd;
    }

    /* Scrollbar polish */
    .scroll-box::-webkit-scrollbar{ width: 8px; }
    .scroll-box::-webkit-scrollbar-thumb{ background: #c9d4e6; border-radius: 999px; }
  </style>
   @stack('styles')
</head>
<body>

@php
  $categories = $categories ?? collect();
@endphp

<!-- Main navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('index') }}">
      <img src="{{ asset('front/images/logo.png') }}" alt="BARABD logo" class="brand-logo">
      <span class="logo-text brand-mark">
        <span class="brand-red">bara</span><span class="brand-green">bd</span> Data Center
      </span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="mainNavbar">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('index') }}">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Hosting</a>
          <ul class="dropdown-menu">
            @forelse($categories as $category)
              <li>
                <a class="dropdown-item service-category-link"
                   href="{{ route('index', ['category' => $category->id]) }}#pricing"
                   data-service-category="{{ $category->id }}">
                  {{ $category->name }}
                </a>
              </li>
            @empty
              <li>
                <span class="dropdown-item-text text-muted small">services</span>
              </li>
            @endforelse
          </ul>
        </li>

        {{-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Billing</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Invoices</a></li>
            <li><a class="dropdown-item" href="#">Transactions</a></li>
            <li><a class="dropdown-item" href="#">Payment Methods</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Server</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Server Status</a></li>
            <li><a class="dropdown-item" href="#">Network Status</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Support</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Open Ticket</a></li>
            <li><a class="dropdown-item" href="#">My Tickets</a></li>
            <li><a class="dropdown-item" href="#">Knowledgebase</a></li>
          </ul>
        </li> --}}

        <li class="nav-item">
          <a class="nav-link" href="{{ route('index') }}#contact">Contact</a>
        </li>
      </ul>

    </div>
  </div>
</nav>

    <div class="main-wrapper">
        <div class="container">
            @yield('content')
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
