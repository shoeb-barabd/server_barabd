@extends('customer.layouts.userLayout')

@section('title', 'User Dashboard | BARABD Hosting')

@section('content')
@push('styles')
<style>
  .dashboard-hero {
    background: linear-gradient(120deg, #0F34DB, #3E67FF);
    color: #fff;
    border-radius: 16px;
    padding: 24px 28px;
    box-shadow: 0 10px 30px rgba(13,110,253,0.15);
  }
  .dashboard-hero .meta { color: rgba(255,255,255,0.9); font-size: 0.95rem; }
  .nav-card .list-group-item {
    border: none;
    padding: 12px 14px;
    font-weight: 600;
    color: #445066;
    background: transparent;
  }
  .nav-card .list-group-item.active {
    background: linear-gradient(90deg, rgba(13,110,253,0.15), rgba(13,110,253,0.05));
    color: #0a49c8;
    border-left: 4px solid #0d6efd;
    border-radius: 10px;
    box-shadow: 0 6px 14px rgba(13,110,253,0.12);
  }
  .nav-card .list-group-item:hover {
    background: rgba(13,110,253,0.08);
    color: #0a49c8;
  }
  .nav-card .list-group-item i {
    color: #7c8aa3;
  }
  .nav-card .list-group-item.active i {
    color: #0a49c8;
  }
  .card-custom {
    border: 1px solid #dfe7f3;
    border-radius: 14px;
    box-shadow: 0 12px 28px rgba(13, 51, 109, 0.08);
    background: linear-gradient(180deg, #f8fbff 0%, #f3f7ff 70%, #eef3ff 100%);
  }
  .card-custom-header {
    border-bottom: 1px solid #e2e8f5;
    background: linear-gradient(90deg, #f0f6ff, #e6efff);
    border-radius: 14px 14px 0 0;
    color: #294574;
    font-weight: 700;
  }
  .summary-box {
    border: 1px solid #e8ecf2;
    background: #fff;
    border-radius: 12px;
    padding: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  .summary-box .icon-wrapper {
    background: #f1f5ff;
    width: 40px;
    height: 40px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    color: #0d6efd;
    font-size: 18px;
  }
  .btn-ticket {
    border-color: #0d6efd;
    color: #0d6efd;
    transition: all 0.15s ease-in-out;
  }
  .btn-ticket:hover {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
  }
  .status-dot {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 10px;
    border-radius: 30px;
    background: #e9f7ef;
    color: #198754;
    font-weight: 600;
    font-size: 0.85rem;
  }
  .status-dot::before {
    content: '';
    width: 10px; height: 10px;
    border-radius: 50%;
    background: #198754;
  }
  .pill {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    background: #f1f3f5;
    font-size: 0.85rem;
    color: #495057;
  }
  [data-section-toggle] { cursor: pointer; }
</style>
@endpush

<!-- Main content -->
<div class="main-wrapper">
  <div class="container">
    @php
      $user = auth()->user();
      $payments = $payments ?? collect();
      $paidPayments = $payments->where('status', 'SUCCESS');
      $pendingPayments = $payments->where('status', 'PENDING');
      $totalPaidAmount = $paidPayments->sum('amount');
      $currencyCode = $payments->first()?->currency ?? 'BDT';
    @endphp
    <div class="row">

      <!-- Left Column -->
      <div class="col-lg-3">
        <div class="sidebar-sticky">
          <div class="card-custom nav-card mb-3">
            <div class="card-custom-header">Navigation</div>
            <div class="list-group list-group-flush">
              <a class="list-group-item list-group-item-action active" href="#" data-section-toggle="dashboard">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
              </a>
              <a class="list-group-item list-group-item-action" href="#" data-section-toggle="profile">
                <i class="bi bi-person-circle me-1"></i> Profile
              </a>
              <a class="list-group-item list-group-item-action" href="#" data-section-toggle="password">
                <i class="bi bi-shield-lock me-1"></i> Change Password
              </a>
            </div>
          </div>

          <!-- Account Summary -->
          <div class="card-custom">
            <div class="card-custom-header d-flex justify-content-between align-items-center">
              <span>Account Summary</span>
              <button class="toggle-btn" type="button" data-bs-toggle="collapse"
                      data-bs-target="#cardAccount" aria-expanded="true">
                <i class="bi bi-chevron-up toggle-icon"></i>
              </button>
            </div>
            <div id="cardAccount" class="card-custom-body collapse show">
              <div class="fw-semibold">{{ $user?->name ?? 'BARABD Customer' }}</div>
              <div class="text-muted small mb-2">{{ $user?->email }}</div>
              <div>Role: {{ $user?->role ?? 'user' }}</div>
              <div>Status: {{ $user?->status ?? 'active' }}</div>
            </div>
          </div>

          <!-- Support Contacts -->
          <div class="card-custom">
            <div class="card-custom-header d-flex justify-content-between align-items-center">
              <span>Support Contacts</span>
              <button class="toggle-btn" type="button" data-bs-toggle="collapse"
                      data-bs-target="#cardSupport" aria-expanded="true">
                <i class="bi bi-chevron-up toggle-icon"></i>
              </button>
            </div>
            <div id="cardSupport" class="card-custom-body collapse show">
              <p class="mb-1"><i class="bi bi-headset me-1"></i> 24/7 Technical Support</p>
              <p class="mb-1"><i class="bi bi-envelope me-1"></i> support@barabd.com</p>
              <p class="mb-1"><i class="bi bi-chat-square-text me-1"></i> Live Chat from portal</p>
              <button class="btn btn-outline-secondary btn-sm w-100 mt-2 btn-ticket">Open Ticket</button>
            </div>
          </div>

          <!-- Quick Shortcuts -->
          <div class="card-custom">
            <div class="card-custom-header d-flex justify-content-between align-items-center">
              <span>Quick Shortcuts</span>
              <button class="toggle-btn" type="button" data-bs-toggle="collapse"
                      data-bs-target="#cardShortcuts" aria-expanded="true">
                <i class="bi bi-chevron-up toggle-icon"></i>
              </button>
            </div>
            <div id="cardShortcuts" class="card-custom-body collapse show">
              <ul class="list-unstyled mb-0">
                {{-- <li class="mb-2">
                  <i class="bi bi-plus-square me-1"></i>Order New Hosting
                </li> --}}
                <li class="mb-2">
                  <i class="bi bi-box-arrow-in-right me-1"></i>
                  <a style="text-decoration:none; color:#FF6C2C;" href="https://103.187.24.178:2083/">Login to cPanel</a>
                </li>
                <li class="mb-2">
                  <i class="bi bi-hdd-network me-1"></i>Check Server Status
                </li>

                <li>
              <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100 mt-2">Logout</button>
            </form>
            </li>
              </ul>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column -->
      <div class="col-lg-9">
        <div class="dashboard-hero mb-3 d-flex flex-wrap align-items-center justify-content-between">
          <div>
            <div class="text-uppercase fw-semibold small mb-1">Welcome back</div>
            <h3 class="fw-bold mb-1">{{ $user?->name ?? 'BARABD Customer' }}</h3>
            <div class="meta">email: {{ $user?->email }}</div>
          </div>
          <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
            <span class="pill">Status: {{ $user?->status ?? 'active' }}</span>
            <span class="status-dot">Secure</span>
            <a class="btn btn-light text-primary fw-semibold" href="#" data-section-toggle="profile">
              <i class="bi bi-person-circle me-1"></i> Update Profile
            </a>
          </div>
        </div>

        <div id="dashboard-section">

          <!-- Summary boxes row -->
          <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
              <div class="summary-box sb-blue">
                <div class="icon-wrapper">
                  <i class="bi bi-hdd-stack"></i>
                </div>
                <div class="number">{{ $payments->count() }}</div>
                <div class="label">Total Orders</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-box sb-green">
                <div class="icon-wrapper">
                  <i class="bi bi-server"></i>
                </div>
                <div class="number">{{ $paidPayments->count() }}</div>
                <div class="label">Paid</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-box sb-amber">
                <div class="icon-wrapper">
                  <i class="bi bi-chat-dots"></i>
                </div>
                <div class="number">{{ $pendingPayments->count() }}</div>
                <div class="label">Pending</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-box sb-purple">
                <div class="icon-wrapper">
                  <i class="bi bi-receipt"></i>
                </div>
                <div class="number">{{ number_format($totalPaidAmount, 2) }}</div>
                <div class="label">Total Paid ({{ $currencyCode }})</div>
              </div>
            </div>
          </div>

          <!-- Recent Payments + Customizations -->
          <div class="card-custom">
            <div class="card-custom-header d-flex justify-content-between align-items-center">
              <span>Recent Payments</span>
              <span class="small text-muted">Linked to your products, categories & features</span>
            </div>
            <div class="card-custom-body">
              @forelse ($payments as $payment)
                @php
                  $statusClass = match (strtoupper($payment->status)) {
                    'SUCCESS' => 'bg-success',
                    'PENDING' => 'bg-warning text-dark',
                    'FAILED', 'CANCELLED' => 'bg-danger',
                    default => 'bg-secondary',
                  };
                  $features = $payment->config['features'] ?? [];
                  $addOns   = $payment->config['add_ons'] ?? [];
                @endphp
                <div class="row align-items-start gy-1 mb-3">
                  <div class="col-md-2 col-4">
                    <span class="badge {{ $statusClass }} badge-status">{{ strtoupper($payment->status) }}</span>
                    <div class="small text-muted mt-1">
                      {{ $payment->paid_at?->format('d M Y') ?? $payment->created_at?->format('d M Y') }}
                    </div>
                    <div class="small text-muted">#{{ $payment->tran_id }}</div>
                  </div>
                  <div class="col-md-7 col-8">
                    <div class="fw-semibold">
                      {{ $payment->product_name ?? $payment->product?->name ?? 'Custom Order' }}
                    </div>
                    <small class="text-muted d-block">
                      {{ $payment->category?->name ?? 'Category N/A' }} |
                      {{ $payment->billingCycle?->name ?? ($payment->meta['billing_cycle'] ?? 'Cycle') }} |
                      {{ $payment->location?->name ?? ($payment->meta['location'] ?? 'Location') }}
                    </small>
                    @if(!empty($features))
                      <div class="small text-muted mt-1">
                        Features:
                        {{ collect($features)->map(fn($val, $key) => $key.': '.(is_array($val) ? json_encode($val) : $val))->take(3)->join(' | ') }}
                      </div>
                    @endif
                    @if(!empty($addOns))
                      <div class="small text-muted">
                        Add-ons:
                        {{ collect($addOns)->map(fn($row) => ($row['key'] ?? 'add-on').(isset($row['qty']) ? ' x'.($row['qty']) : ''))->take(3)->join(' | ') }}
                      </div>
                    @endif
                    @if(!empty($payment->line_items))
                      <div class="small text-muted">
                        Items:
                        {{ collect($payment->line_items)->map(fn($line) => ($line['label'] ?? $line['key'] ?? 'item').': '.number_format((float)($line['amount'] ?? 0), 2))->take(3)->join(', ') }}
                      </div>
                    @endif
                    @if(!empty($payment->meta['notes']))
                      <div class="small text-muted">
                        Note: {{ \Illuminate\Support\Str::limit($payment->meta['notes'], 80) }}
                      </div>
                    @endif
                  </div>
                  <div class="col-md-3 text-md-end">
                    <div class="fw-semibold">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</div>
                    <div class="small text-muted">Ref: {{ $payment->val_id ?? 'N/A' }}</div>
                    <div class="small text-muted">Status: {{ ucfirst(strtolower($payment->status)) }}</div>
                    @if($payment->user_id === auth()->id())
                      <a href="{{ route('user.payments.invoice', $payment) }}" class="btn btn-outline-primary btn-sm mt-1">
                        <i class="bi bi-receipt"></i> Invoice
                      </a>
                    @endif
                  </div>
                </div>
                @if (! $loop->last)
                  <hr>
                @endif
              @empty
                <p class="mb-0 text-muted">No payments recorded yet. Complete a purchase to see it here.</p>
              @endforelse
            </div>
          </div>

        <!-- Quick Order + Server Status -->

        {{-- <div class="row">
          <!-- Quick Order -->
          <div class="col-md-6">
            <div class="card-custom">
              <div class="card-custom-header">
                <i class="bi bi-cart-plus me-1"></i>Quick Order Hosting
              </div>
              <div class="card-custom-body">
                <label class="form-label small mb-1">Choose Plan</label>
                <select class="form-select form-select-sm mb-2">
                  <option>Shared Hosting Starter</option>
                  <option>Shared Hosting Business</option>
                  <option>VPS Hosting 2 vCPU</option>
                  <option>Cloud Hosting</option>
                </select>
                <label class="form-label small mb-1">Billing Cycle</label>
                <select class="form-select form-select-sm mb-3">
                  <option>Monthly</option>
                  <option>Yearly (Save 20%)</option>
                </select>
                <button class="btn btn-success btn-sm w-100">Continue to Order</button>
              </div>
            </div>
          </div>

          <!-- Server Status -->
          <div class="col-md-6">
            <div class="card-custom">
              <div class="card-custom-header">
                <i class="bi bi-hdd-network me-1"></i>Server Status
              </div>
              <div class="card-custom-body">
                <ul class="list-unstyled mb-0">
                  <li class="d-flex justify-content-between align-items-center mb-2">
                    <span>bdix-shared-01</span>
                    <span class="badge bg-success">Online</span>
                  </li>
                  <li class="d-flex justify-content-between align-items-center mb-2">
                    <span>vps-node-02</span>
                    <span class="badge bg-success">Online</span>
                  </li>
                  <li class="d-flex justify-content-between align-items-center">
                    <span>backup-node</span>
                    <span class="badge bg-secondary">Maintenance</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div> --}}

        <!-- Resource Usage + Recent News -->
        <div class="row">
          <!-- Resource Usage -->
          <div class="col-md-6">
            <div class="card-custom">
              <div class="card-custom-header">
                <i class="bi bi-speedometer2 me-1"></i>Resource Usage (example)
              </div>
              <div class="card-custom-body">
                <div class="mb-3">
                  <div class="d-flex justify-content-between small mb-1">
                    <span>Disk Usage</span><span>6.5 GB / 10 GB</span>
                  </div>
                  <div class="usage-bar">
                    <div style="width: 65%;" class="bg-primary"></div>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="d-flex justify-content-between small mb-1">
                    <span>Bandwidth</span><span>120 GB / 500 GB</span>
                  </div>
                  <div class="usage-bar">
                    <div style="width: 24%;" class="bg-success"></div>
                  </div>
                </div>
                <div>
                  <div class="d-flex justify-content-between small mb-1">
                    <span>Email Accounts</span><span>4 / 20</span>
                  </div>
                  <div class="usage-bar">
                    <div style="width: 20%;" class="bg-warning"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent News -->
          <div class="col-md-6">
            <div class="card-custom">
              <div class="card-custom-header">Hosting Announcements</div>
              <div class="card-custom-body scroll-box">
                @forelse($announcements ?? [] as $announcement)
                  <div class="mb-3">
                    <p class="mb-2 fw-semibold">{{ $announcement->title }}</p>
                    <p class="mb-2 text-muted small">
                      {{ optional($announcement->published_at)->format('l, F jS, Y') ?? '' }}
                    </p>
                    <p class="mb-0 small">{!! nl2br(e($announcement->description)) !!}</p>
                  </div>
                  @if (! $loop->last)
                    <hr>
                  @endif
                @empty
                  <p class="text-muted small mb-0">No announcements yet.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>

        </div><!-- /dashboard-section -->

        @include('customer.partials.profile-content', ['sectionId' => 'profile-section', 'hidden' => true])
        @include('customer.partials.changlePassword', ['sectionId' => 'password-section', 'hidden' => true])

      </div><!-- /col-lg-9 -->

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function() {
    const sections = {
      dashboard: document.getElementById('dashboard-section'),
      profile: document.getElementById('profile-section'),
      password: document.getElementById('password-section'),
    };
    const navLinks = document.querySelectorAll('.nav-card [data-section-toggle]');
    const toggles = document.querySelectorAll('[data-section-toggle]');

    function showSection(target) {
      Object.entries(sections).forEach(([key, section]) => {
        if (!section) return;
        section.classList.toggle('d-none', key !== target);
      });
      navLinks.forEach(link => {
        const active = link.getAttribute('data-section-toggle') === target;
        link.classList.toggle('active', active);
      });
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    toggles.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const target = this.getAttribute('data-section-toggle');
        if (target) showSection(target);
      });
    });

    showSection('dashboard');
  })();
</script>
@endpush
