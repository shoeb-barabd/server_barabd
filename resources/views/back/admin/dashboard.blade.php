@extends('layouts.app')

@section('title','barabd server')
@section('page_title','Dashboard')
@section('breadcrumb','Dashboard')

@section('content')
@include('back.partials.flash')

<div class="dashboard-shell">
  <section class="stat-grid">
    <article class="stat-card tilt fade-in delay-1">
      <div class="icon-pill icon-pill-blue"><i class="bi bi-people-fill"></i></div>
      <div class="stat-body">
        <p class="stat-label">Total users</p>
        <div class="stat-value count-up" data-count="{{ $totalUsers }}" data-decimals="0">{{ number_format($totalUsers) }}</div>
        <small class="text-success fw-semibold">+{{ number_format($newUsersLast30) }} in 30d</small>
      </div>
    </article>

    <article class="stat-card tilt fade-in delay-2">
      <div class="icon-pill icon-pill-mint"><i class="bi bi-cash-stack"></i></div>
      <div class="stat-body">
        <p class="stat-label">Total revenue (USD)</p>
        <div class="stat-value count-up" data-count="{{ $totalRevenue }}" data-decimals="2" data-prefix="USD ">{{ number_format($totalRevenue, 2) }}</div>
        <small class="text-muted">Today: {{ number_format($todayRevenue, 2) }}</small>
      </div>
    </article>

    <article class="stat-card tilt fade-in delay-3">
      <div class="icon-pill icon-pill-amber"><i class="bi bi-wallet2"></i></div>
      <div class="stat-body">
        <p class="stat-label">Total revenue (BDT)</p>
        <div class="stat-value count-up" data-count="{{ $bdtRevenue }}" data-decimals="2" data-prefix="BDT ">{{ number_format($bdtRevenue, 2) }}</div>
        <small class="text-muted">USD/Other: {{ number_format($usdRevenue, 2) }}</small>
      </div>
    </article>

    <article class="stat-card tilt fade-in delay-4">
      <div class="icon-pill icon-pill-cyan"><i class="bi bi-credit-card-2-back-fill"></i></div>
      <div class="stat-body">
        <p class="stat-label">Successful payments</p>
        <div class="stat-value count-up" data-count="{{ $successfulPayments }}" data-decimals="0">{{ number_format($successfulPayments) }}</div>
        <small class="text-amber fw-semibold">{{ number_format($pendingPayments) }} pending</small>
      </div>
    </article>
  </section>

  <section class="chart-grid fade-in delay-2">
    <div class="chart-card glassy revenue-card">
      <div class="card-heading">
        <div>
          <p class="eyebrow">Revenue</p>
          <h5 class="mb-0">Last 12 months</h5>
          <small class="text-muted">Successful payments only</small>
        </div>
        <span class="pill">USD + BDT</span>
      </div>
      <div id="revenue-chart" class="chart-area"></div>
    </div>

    <div class="chart-card glassy">
      <div class="card-heading">
        <div>
          <p class="eyebrow">Payment status</p>
          <h5 class="mb-0">Health snapshot</h5>
          <small class="text-muted">Count of all transactions</small>
        </div>
        <span class="pill pill-soft">Live</span>
      </div>
      <div id="status-chart" class="chart-area"></div>
      <div class="status-legend">
        @forelse($statusBreakdown as $row)
          @php
            $statusClass = match($row->status) {
              'SUCCESS' => 'text-success',
              'PENDING' => 'text-amber',
              'FAILED' => 'text-danger',
              'CANCELLED' => 'text-secondary',
              default => 'text-info',
            };
          @endphp
          <div class="legend-item">
            <span class="legend-dot {{ $statusClass }}"></span>
            <span class="legend-label">{{ $row->status ?? 'Unknown' }}</span>
            <span class="legend-value">{{ number_format($row->total) }}</span>
            <span class="legend-amount text-muted">{{ number_format($row->amount ?? 0, 2) }}</span>
          </div>
        @empty
          <p class="text-muted mb-0">No payment data yet.</p>
        @endforelse
      </div>
    </div>
  </section>

  <section class="recent-card glassy fade-in delay-3">
    <div class="card-heading">
      <div>
        <p class="eyebrow">Activity</p>
        <h5 class="mb-0">Recent payments</h5>
      </div>
      <a href="{{ route('admin.payments.index') }}" class="pill-link pill-link-ghost">View all</a>
    </div>
    <div class="activity-list">
      @forelse($recentPayments as $payment)
        @php
          $statusClass = match($payment->status) {
            'SUCCESS' => 'status-chip status-chip-success',
            'PENDING' => 'status-chip status-chip-pending',
            'FAILED' => 'status-chip status-chip-failed',
            'CANCELLED' => 'status-chip status-chip-cancelled',
            default => 'status-chip status-chip-info',
          };
        @endphp
        <div class="activity-row">
          <div class="activity-col primary">
            <div class="tran-id">{{ $payment->tran_id }}</div>
            <div class="meta">{{ $payment->user?->name ?? 'Guest' }}</div>
          </div>
          <div class="activity-col">
            <div class="label">Product</div>
            <div class="value">{{ $payment->product_name ?? $payment->product?->name ?? 'N/A' }}</div>
          </div>
          <div class="activity-col">
            <div class="label">Amount</div>
            <div class="value">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</div>
          </div>
          <div class="activity-col">
            <div class="label">Status</div>
            <span class="{{ $statusClass }}">{{ $payment->status }}</span>
          </div>
          <div class="activity-col">
            <div class="label">Created</div>
            <div class="value">{{ $payment->created_at?->format('d M Y, H:i') }}</div>
          </div>
        </div>
      @empty
        <div class="text-center text-muted py-4">No recent payments found.</div>
      @endforelse
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const revenueSeries = @json($monthlyRevenue);
  const revenueLabels = @json($monthlyLabels);

  const revenueOpts = {
    series: [{ name: 'Revenue', data: revenueSeries }],
    chart: { type: 'area', height: 330, toolbar: { show: false }, foreColor: '#eaf4ff' },
    colors: ['#f8fbff'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.8,
        opacityTo: 0.25,
        stops: [0, 90, 100]
      }
    },
    xaxis: {
      categories: revenueLabels,
      labels: { rotate: -45, style: { colors: '#f4f7ff' } },
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: { formatter: val => Number(val).toLocaleString(), style: { colors: '#f4f7ff' } }
    },
    grid: { borderColor: 'rgba(255,255,255,0.35)', strokeDashArray: 2 },
    tooltip: {
      theme: 'dark',
      shared: true,
      y: { formatter: val => Number(val ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
    }
  };
  const revenueEl = document.querySelector('#revenue-chart');
  if (revenueEl) new ApexCharts(revenueEl, revenueOpts).render();
});

// Subtle count-up animation
document.addEventListener('DOMContentLoaded', function () {
  const counters = document.querySelectorAll('.count-up');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = Number(el.dataset.count || 0);
      const decimals = Number(el.dataset.decimals || 0);
      const prefix = el.dataset.prefix || '';
      const suffix = el.dataset.suffix || '';
      const start = 0;
      const duration = 900;
      const startTime = performance.now();
      const step = (now) => {
        const progress = Math.min((now - startTime) / duration, 1);
        const value = start + (target - start) * progress;
        const formatted = Number(value).toLocaleString(undefined, {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        });
        el.textContent = `${prefix}${formatted}${suffix}`;
        if (progress < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
      observer.unobserve(el);
    });
  }, { threshold: 0.4 });
  counters.forEach(c => observer.observe(c));
});

// Highcharts donut with fan animation
(function (H) {
  H.seriesTypes.pie.prototype.animate = function (init) {
    const series = this;
    const chart = series.chart;
    const points = series.points;
    const animation = series.options.animation;
    const startAngleRad = series.startAngleRad;

    function fanAnimate(point, startAngle) {
      const graphic = point.graphic;
      const args = point.shapeArgs;
      if (graphic && args) {
        graphic.attr({ start: startAngle, end: startAngle, opacity: 1 })
          .animate({ start: args.start, end: args.end }, {
            duration: animation.duration / points.length
          }, function () {
            if (points[point.index + 1]) {
              fanAnimate(points[point.index + 1], args.end);
            }
            if (point.index === series.points.length - 1) {
              series.dataLabelsGroup.animate({ opacity: 1 }, void 0, function () {
                points.forEach(p => { p.opacity = 1; });
                series.update({ enableMouseTracking: true }, false);
                chart.update({
                  plotOptions: { pie: { innerSize: '45%', borderRadius: 8 } }
                });
              });
            }
          });
      }
    }

    if (init) {
      points.forEach(p => { p.opacity = 0; });
    } else {
      fanAnimate(points[0], startAngleRad);
    }
  };
}(Highcharts));

document.addEventListener('DOMContentLoaded', function () {
  const statusEl = document.querySelector('#status-chart');
  if (!statusEl || !window.Highcharts) return;

  const rawData = @json(
    $statusBreakdown->map(function ($row) {
      return [
        'name' => $row->status ?? 'Unknown',
        'y'    => (int) $row->total,
      ];
    })
  );

  const palette = [
    { from: '#34d399', to: '#10b981' },
    { from: '#fbbf24', to: '#f59e0b' },
    { from: '#f87171', to: '#ef4444' },
    { from: '#94a3b8', to: '#7c83ff' },
    { from: '#60a5fa', to: '#2563eb' },
  ];

  const seriesData = (rawData || [])
    .map((item, idx) => ({
      name: item.name || 'Unknown',
      y: Number(item.y) || 0,
      color: {
        linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
        stops: [
          [0, palette[idx % palette.length].from],
          [1, palette[idx % palette.length].to],
        ],
      },
      borderColor: '#eef2ff',
      borderWidth: 2,
    }))
    .filter(d => d.y > 0);

  if (!seriesData.length) {
    statusEl.innerHTML = '<div class="text-muted small">No payment data yet.</div>';
    return;
  }

  Highcharts.chart(statusEl, {
    chart: {
      type: 'pie',
      backgroundColor: 'transparent',
      height: 330,
      spacing: [10, 10, 10, 10]
    },
    title: { text: null },
    subtitle: { text: null },
    credits: { enabled: false },
    tooltip: {
      headerFormat: '',
      pointFormat: '<span style="color:{point.color}">\u25cf</span> {point.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        borderWidth: 2,
        cursor: 'pointer',
        innerSize: '40%',
        borderColor: '#eef2ff',
        dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
          distance: 22,
          style: { fontWeight: '700', color: '#0f172a' }
        }
      }
    },
    series: [{
      enableMouseTracking: false,
      animation: { duration: 1800 },
      colorByPoint: true,
      data: seriesData
    }]
  });
});
</script>
@endpush

@push('styles')
<style>
:root {
  --ink: #0f172a;
  --muted: #6b7280;
  --glass: rgba(255,255,255,0.85);
  --border-soft: rgba(255,255,255,0.35);
  --primary: #2563eb;
  --amber: #f59e0b;
  --card-shadow: 0 15px 50px rgba(15, 23, 42, 0.08);
}

.app-content {
  background: radial-gradient(circle at 20% 20%, rgba(37,99,235,0.12), transparent 35%),
              radial-gradient(circle at 80% 0%, rgba(16,185,129,0.12), transparent 32%),
              #f6f8fc;
  padding-bottom: 2rem;
}

.dashboard-shell {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}
.eyebrow {
  text-transform: uppercase;
  letter-spacing: .08em;
  font-size: .75rem;
  color: var(--muted);
  margin-bottom: .2rem;
}

.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1rem;
}
.glassy {
  background: var(--glass);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-soft);
  border-radius: 18px;
  box-shadow: var(--card-shadow);
}
.stat-card {
  display: grid;
  grid-template-columns: auto 1fr;
  align-items: center;
  gap: .75rem;
  padding: 1.1rem 1.2rem;
  border-radius: 18px;
  background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(245,248,255,0.92));
  border: 1px solid rgba(255,255,255,0.6);
  box-shadow: 0 12px 30px rgba(15,23,42,0.08);
  position: relative;
  overflow: hidden;
  transition: transform 180ms ease, box-shadow 180ms ease;
}
.stat-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 20% 20%, rgba(37,99,235,0.12), transparent 40%),
              radial-gradient(circle at 80% 30%, rgba(16,185,129,0.12), transparent 40%);
  opacity: 0;
  transition: opacity 200ms ease;
}
.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 40px rgba(15,23,42,0.14);
}
.stat-card:hover::before { opacity: 1; }
.stat-card > * { position: relative; z-index: 1; }
.icon-pill {
  width: 48px;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  font-size: 1.25rem;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.4);
  background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(16,185,129,0.14));
  transition: transform 150ms ease, box-shadow 150ms ease;
}
.stat-card:hover .icon-pill {
  transform: translateY(-2px);
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.6), 0 8px 20px rgba(15,23,42,0.18);
}
.icon-pill-amber {
  background: linear-gradient(135deg, #fcd34d, #f59e0b);
  color: #8c5b00;
}
.icon-pill-blue {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: #0b3d91;
}
.icon-pill-mint {
  background: linear-gradient(135deg, #34d399, #0ea967);
  color: #0a4f36;
}
.icon-pill-cyan {
  background: linear-gradient(135deg, #38bdf8, #0ea5e9);
  color: #0b4367;
}
.stat-label { text-transform: uppercase; letter-spacing: .04em; color: var(--muted); font-size: .8rem; margin-bottom: .1rem; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: var(--ink); }
.text-amber { color: var(--amber); }

.chart-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
}
@media (max-width: 992px) { .chart-grid { grid-template-columns: 1fr; } }
.chart-card {
  padding: 1.1rem;
  border-radius: 18px;
  box-shadow: var(--card-shadow);
  background: linear-gradient(145deg, rgba(255,255,255,0.95), rgba(245,248,255,0.9));
  transition: transform 180ms ease, box-shadow 180ms ease;
}
.revenue-card {
  color: #f8fbff;
  background: linear-gradient(130deg, #2d5bff, #12b5b0);
  border: 1px solid rgba(255,255,255,0.35);
}
.revenue-card .card-heading h5,
.revenue-card .card-heading .eyebrow,
.revenue-card .card-heading small,
.revenue-card .pill { color: #f8fbff; }
.revenue-card .pill { background: rgba(255,255,255,0.12); }
.revenue-card .eyebrow { opacity: 0.9; }
.revenue-card .card-heading { color: #f8fbff; }
.revenue-card .chart-area { filter: drop-shadow(0 10px 25px rgba(0,0,0,0.18)); }
.revenue-card .card-heading .text-muted,
.revenue-card .card-heading small { color: #f4f7ff !important; opacity: 0.92; }
.chart-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 40px rgba(15,23,42,0.18);
}
.card-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .75rem;
  margin-bottom: .6rem;
}
.pill, .pill-link {
  border-radius: 999px;
  padding: .35rem .75rem;
  background: rgba(15,23,42,0.06);
  font-size: .85rem;
  color: var(--ink);
  text-decoration: none;
}
.pill-link-ghost {
  background: rgba(37,99,235,0.12);
  color: #1d4ed8;
  font-weight: 700;
}
.pill-soft { background: rgba(37,99,235,0.12); color: #1d4ed8; }
.chart-area { min-height: 320px; }
.chart-area.apexcharts-canvas {
  transition: transform 180ms ease;
}
.chart-card:hover .apexcharts-canvas {
  transform: translateY(-4px);
}

.status-legend { margin-top: .25rem; display: grid; gap: .4rem; }
.legend-item { display: grid; grid-template-columns: auto 1fr auto auto; align-items: center; gap: .5rem; font-size: .9rem; padding: .45rem .55rem; border-radius: 10px; background: rgba(15,23,42,0.03); }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.legend-label { color: var(--ink); font-weight: 600; }
.legend-value { font-weight: 700; color: var(--ink); }

.recent-card {
  padding: 1rem 1.2rem;
  border-radius: 18px;
  box-shadow: var(--card-shadow);
}
.activity-list { display: flex; flex-direction: column; gap: .6rem; }
.activity-row {
  display: grid;
  grid-template-columns: 2fr 1.2fr 1.2fr 1fr 1.2fr;
  gap: .75rem;
  align-items: center;
  padding: .85rem 1rem;
  border-radius: 14px;
  background: rgba(255,255,255,0.9);
  border: 1px solid rgba(229,231,235,0.9);
  box-shadow: 0 6px 18px rgba(15,23,42,0.06);
  transition: transform 150ms ease, box-shadow 150ms ease, border-color 150ms ease;
}
.activity-row:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(15,23,42,0.12);
  border-color: rgba(37,99,235,0.25);
}
.activity-col .label {
  font-size: .78rem;
  text-transform: uppercase;
  letter-spacing: .04em;
  color: #6b7280;
}
.activity-col .value {
  font-weight: 600;
  color: #0f172a;
}
.activity-col.primary .tran-id {
  font-weight: 800;
  color: #0f172a;
}
.activity-col.primary .meta {
  color: #6b7280;
  font-size: .9rem;
}
.status-chip {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: .35rem .65rem;
  border-radius: 999px;
  font-size: .82rem;
  font-weight: 700;
  letter-spacing: .01em;
}
.status-chip-success { background: rgba(16,185,129,0.16); color: #0f766e; }
.status-chip-pending { background: rgba(245,158,11,0.22); color: #b45309; }
.status-chip-failed { background: rgba(239,68,68,0.18); color: #b91c1c; }
.status-chip-cancelled { background: rgba(107,114,128,0.16); color: #374151; }
.status-chip-info { background: rgba(59,130,246,0.16); color: #1d4ed8; }

.badge-soft-success { background: rgba(16,185,129,0.15); color: #0f766e; }
.badge-soft-amber { background: rgba(245,158,11,0.2); color: #b45309; }
.badge-soft-danger { background: rgba(239,68,68,0.18); color: #b91c1c; }
.badge-soft-secondary { background: rgba(107,114,128,0.18); color: #374151; }
.badge-soft-info { background: rgba(59,130,246,0.15); color: #1d4ed8; }

.tilt { transform-style: preserve-3d; }
.fade-in { opacity: 0; transform: translateY(8px); animation: fadeUp 500ms ease forwards; }
.delay-1 { animation-delay: .06s; }
.delay-2 { animation-delay: .12s; }
.delay-3 { animation-delay: .18s; }
.delay-4 { animation-delay: .24s; }

@keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@keyframes spin { to { transform: rotate(360deg); } }

/* Sidebar gradient + readability */
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
.app-sidebar .nav-link.active {
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

/* Navbar refresh */
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
</style>
@endpush
