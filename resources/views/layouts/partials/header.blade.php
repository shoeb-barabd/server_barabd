<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>

      {{-- Messages --}}
      {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          @foreach([1,2,3] as $i)
            <a href="#" class="dropdown-item">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <img src="{{ asset(url('back/img/user1-128x128.jpg')) }}" alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                </div>
                <div class="flex-grow-1">
                  <h3 class="dropdown-item-title">
                    User {{ $i }}
                    <span class="float-end fs-7 text-{{ $i===1?'danger':($i===2?'secondary':'warning') }}">
                      <i class="bi bi-star-fill"></i>
                    </span>
                  </h3>
                  <p class="fs-7">Sample message…</p>
                  <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                </div>
              </div>
            </a>
            @if($i<3)<div class="dropdown-divider"></div>@endif
          @endforeach
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li> --}}

      {{-- Notifications --}}
      @php
        $paymentNotifications = $paymentNotifications ?? collect();
        $paymentNotificationsUnreadCount = $paymentNotificationsUnreadCount ?? $paymentNotifications->count();
        $authUser = auth()->user();
        $authName = trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? '')) ?: ($authUser->name ?? 'User');
        $authRole = $authUser->role ?? null;
      @endphp
      <li class="nav-item dropdown">
        <a id="paymentNotificationsToggle" class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          @if($paymentNotificationsUnreadCount > 0)
            <span class="navbar-badge badge text-bg-warning">
              {{ $paymentNotificationsUnreadCount > 99 ? '99+' : $paymentNotificationsUnreadCount }}
            </span>
          @endif
        </a>
        <div id="paymentNotificationsMenu" class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">
            {{ $paymentNotificationsUnreadCount }} Notification{{ $paymentNotificationsUnreadCount === 1 ? '' : 's' }}
          </span>
          @forelse($paymentNotifications as $payment)
            <div class="dropdown-divider"></div>
            <a href="{{ route('admin.payments.show', $payment) }}"
               class="dropdown-item payment-notification-item {{ $payment->is_unread ?? false ? 'bg-body-secondary' : '' }}"
               data-payment-id="{{ $payment->id }}">
              <div class="d-flex align-items-start">
                <i class="bi bi-credit-card {{ ($payment->is_unread ?? false) ? 'text-warning' : 'text-secondary' }} me-2 mt-1"></i>
                <div class="flex-grow-1">
                  <div class="fw-semibold">
                    New payment of {{ $payment->currency ?? 'BDT' }} {{ number_format((float) $payment->amount, 2) }}
                  </div>
                  <div class="text-secondary fs-7">
                    @php
                      $payerName = $payment->user?->name ?: $payment->user?->email ?: 'Guest customer';
                      $timestamp = $payment->paid_at ?? $payment->created_at;
                    @endphp
                    {{ $payerName }}
                    @if($timestamp) - {{ $timestamp->diffForHumans() }} @endif
                  </div>
                </div>
              </div>
            </a>
          @empty
            <div class="dropdown-divider"></div>
            <span class="dropdown-item text-secondary">No payments yet</span>
          @endforelse
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.payments.index') }}" class="dropdown-item dropdown-footer">See all payments</a>
        </div>
      </li>

      {{-- Fullscreen --}}
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
        </a>
      </li>

      {{-- User --}}
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

          <span class="d-none d-md-inline">{{ $authName }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">

            <p>{{ $authName }}@if($authRole) - {{ ucfirst($authRole) }}@endif</p>
          </li>

        </ul>
      </li>
    </ul>
  </div>
</nav>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('paymentNotificationsToggle');
    const menu = document.getElementById('paymentNotificationsMenu');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!toggle || !token || !menu) return;

    function updateBadge(decrement = 0) {
      const badge = toggle.querySelector('.navbar-badge');
      if (!badge) return;
      let current = parseInt((badge.textContent || '0').replace(/\D/g, ''), 10) || 0;
      current = Math.max(0, current - decrement);
      if (current <= 0) {
        badge.remove();
      } else {
        badge.textContent = current > 99 ? '99+' : current;
      }
    }

    menu.addEventListener('click', function (evt) {
      const link = evt.target.closest('.payment-notification-item');
      if (!link) return;
      const paymentId = link.getAttribute('data-payment-id');
      if (!paymentId || link.dataset.marked === '1') return;
      evt.preventDefault();

      fetch("{{ route('admin.notifications.payments.seen.item', ['payment' => 'PAYMENT_ID']) }}".replace('PAYMENT_ID', paymentId), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json',
        },
      }).then(() => {
        link.dataset.marked = '1';
        link.classList.remove('bg-body-secondary');
        const icon = link.querySelector('i.bi-credit-card');
        if (icon) {
          icon.classList.remove('text-warning');
          icon.classList.add('text-secondary');
        }
        updateBadge(1);
        window.location.href = link.href;
      }).catch(() => {
        window.location.href = link.href;
      });
    });
  });
</script>
@endpush
