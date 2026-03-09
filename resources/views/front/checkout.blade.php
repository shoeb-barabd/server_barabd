<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Checkout — BARABD</title>
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('front/css/checkout.css') }}">
</head>
<body>
@php
    $featureItems = $product->features
        ->take(6)
        ->map(function ($feature) {
            $label = $feature->label ?: \Illuminate\Support\Str::title(str_replace('_', ' ', $feature->key));
            $rawValue = $feature->min ?? $feature->value ?? null;
            if (is_numeric($rawValue)) {
                $rawValue = rtrim(rtrim(number_format((float) $rawValue, 2), '0'), '.');
            }
            $unit = $feature->unit ? ' '.$feature->unit : '';
            return trim($label . ($rawValue !== null ? ' '.$rawValue : '') . $unit);
        })
        ->filter()
        ->values();

    $discountPercent = (float) ($product->save_text ?? 0);
    $formatCheckoutAmount = function ($value) {
        $number = number_format((float) $value, 2, '.', ',');
        return rtrim(rtrim($number, '0'), '.');
    };
    $showYearlyDiscount = $discountPercent > 0 && (
        (($selectedPrice['key'] ?? null) === 'yearly') ||
        (($selectedPrice['months'] ?? 0) >= 12)
    );
    $discountedSubtotalAmount = $showYearlyDiscount
        ? $selectedPrice['amount'] * (1 - $discountPercent / 100)
        : $selectedPrice['amount'];
    $discountedSubtotalFormatted = $formatCheckoutAmount($discountedSubtotalAmount);
@endphp

<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
      <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="40">
      <span class="fw-bold"><span class="text-danger">bara</span><span style="color:#016A4D">bd Data Center</span>
    </a>
    <a class="btn back-home-btn d-flex align-items-center gap-2" href="{{ url('/#pricing') }}">
      <span>←</span> <span>Back to Pricing</span>
    </a>
  </div>
</nav>

<section class="promo-strip text-white text-center mx-auto mt-4">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
      <div>
        <div class="text-uppercase small fw-semibold opacity-75"></div>
        <h4 class="mb-0 fw-bold">Huge deals on {{ $selectedPrice['months'] }}-month plans</h4>
      </div>
      <div class="badge bg-light text-dark rounded-pill px-4 py-2 fw-semibold">Save more with yearly billing</div>
    </div>
  </div>
</section>

<main class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card shadow-sm h-100">
          <div class="card-body p-4 p-lg-5">
            <p class="text-uppercase text-muted small mb-2">Your cart</p>
            <div class="d-flex flex-wrap align-items-center gap-3">
              <div class="plan-avatar d-flex align-items-center justify-content-center">
                @if(!empty($product->icon_class))
                  <i class="{{ $product->icon_class }}"></i>
                @else
                  <i class="bi bi-hdd-stack"></i>
                @endif
              </div>
              <div class="flex-grow-1">
                <h2 class="fw-bold mb-1">{{ $product->name }}</h2>
                <div class="text-muted small">
                  {{ $category?->name ?? 'Category' }} • {{ $location->name }} ({{ $location->currency_code }})
                </div>
              </div>
              @if(!empty($product->save_text))
                <span class="badge bg-pink text-white rounded-pill px-3 py-2 {{ $showYearlyDiscount ? '' : 'd-none' }}" data-checkout="save-badge">{{ $product->save_text }}% OFF</span>
              @endif
            </div>

            <div class="row align-items-center mt-4 g-4">
              <div class="col-md-5">
                <label class="form-label fw-semibold text-muted mb-1">Period</label>
                <select class="form-select" data-checkout-cycle>
                  @foreach($priceOptions as $key => $option)
                    <option value="{{ $key }}" data-months="{{ $option['months'] }}" {{ $selectedCycle === $key ? 'selected' : '' }}>
                      {{ $option['label'] }} ({{ $option['months'] }} {{ \Illuminate\Support\Str::plural('month', $option['months']) }})
                    </option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-2" data-checkout="cycle-description">
                  {{ $selectedPrice['description'] }}
                </small>
              </div>
              <div class="col-md-7">
                <div class="price-highlight rounded-4 p-4 h-100">
                  <div class="text-success small fw-semibold mb-1">
                    {{-- {{ $product->save_text ?? 'Best deal: lock the yearly discount today' }} --}}
                  </div>
                  <div class="d-flex align-items-baseline gap-2 flex-wrap">
                    <div class="display-5 fw-bold text-ink mb-0">
                      <span data-checkout="price-symbol">{{ $selectedPrice['symbol'] }}</span>
                      <span data-checkout="price-per-month">{{ $selectedPrice['per_month_formatted'] }}</span>
                    </div>
                    <span class="text-muted">/month</span>
                  </div>
                  <div class="text-muted small mt-2" data-checkout="due-text">
                    Billed {{ $selectedPrice['months'] }} {{ \Illuminate\Support\Str::plural('month', $selectedPrice['months']) }}
                    at {{ $selectedPrice['symbol'] }}<span data-checkout="price-total">{{ $selectedPrice['amount_formatted'] }}</span>. Renews automatically.
                  </div>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <div class="row g-3">
              <div class="col-md-4">
                <div class="spec-box h-100">
                  <div class="spec-label">Category</div>
                  <div class="spec-value">{{ $category?->name ?? 'N/A' }}</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="spec-box h-100">
                  <div class="spec-label">Location</div>
                  <div class="spec-value">{{ $location->name }}</div>
                  <div class="hint mb-0">Currency: {{ $location->currency_code }}</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="spec-box h-100">
                  <div class="spec-label mb-2">Taxes & fees</div>
                  <div class="tax-grid">
                    <div class="tax-item">
                      <div class="spec-label">VAT TAX</div>
                      <div class="spec-value">0%</div>
                    </div>
                    <div class="tax-item">
                      <div class="spec-label">TDS</div>
                      <div class="spec-value">0%</div>
                    </div>
                    <div class="tax-item">
                      <div class="spec-label">OTHERS</div>
                      <div class="spec-value">0%</div>
                    </div>
                  </div>
                  <div class="hint mb-0">Calculated at checkout</div>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <h6 class="fw-semibold mb-3">What’s included</h6>
              @if($featureItems->count())
                <ul class="tnc-list text-muted">
                  @foreach($featureItems as $item)
                    <li>{{ $item }}</li>
                  @endforeach
                </ul>
              @else
                <p class="text-muted small">Feature information for this plan will be added soon.</p>
              @endif
            </div>

            <div class="alert alert-info rounded-4 border-0 mt-4">
              <div class="fw-semibold mb-1">Great news!</div>
              Hosting in {{ $location->name }} gives you 24/7 support, DDOS protection and instant provisioning included for free.
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm order-card sticky-lg-top" id="summary">
          <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Order summary</h5>

            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="fw-semibold">{{ $product->name }}</div>
                <div class="text-muted small">
                  <span data-checkout="summary-cycle">{{ $priceOptions[$selectedCycle]['label'] }}</span> • {{ $location->name }}
                </div>
              </div>
              <div class="text-end fw-semibold">
                <span data-checkout="summary-symbol">{{ $selectedPrice['symbol'] }}</span>
                <span data-checkout="summary-total">{{ $discountedSubtotalFormatted }}</span>
              </div>
            </div>

            <div class="pay-box mb-3">
              <div class="d-flex justify-content-between small mb-2">
                <span>Plan price</span>
                <span>
                  <span data-checkout="plan-symbol">{{ $selectedPrice['symbol'] }}</span>
                  <span data-checkout="plan-amount">{{ $selectedPrice['amount_formatted'] }}</span>
                </span>
              </div>
              <div class="d-flex justify-content-between small text-muted mb-2 {{ $showYearlyDiscount ? '' : 'd-none' }}" data-checkout="discount-row">
                <span>Discount</span>
                <span data-checkout="discount-percent">{{ $product->save_text }}%</span>
              </div>
              <div class="d-flex justify-content-between fw-bold">
                <span>Subtotal</span>
                <span>
                  <span data-checkout="subtotal-symbol">{{ $selectedPrice['symbol'] }}</span>
                  <span data-checkout="subtotal-amount">{{ $discountedSubtotalFormatted }}</span>
                </span>
              </div>
            </div>
            {{-- //kskasjfkasj --}}

              <form id="sslPayForm" method="POST" action="{{ route('checkout.paymentDetails.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="location_id" value="{{ $location->id }}">
                <input type="hidden" name="billing_cycle_id" value="{{ $selectedPrice['billing_cycle_id'] ?? $prices->first()->billing_cycle_id }}">
                <input type="hidden" name="plan_title" value="{{ $product->name }}">
                <input type="hidden" name="amount" value="{{ $discountedSubtotalAmount }}">
                <input type="hidden" name="currency" value="{{ $location->currency_code }}">
                <button type="submit" class="btn btn-gradient w-100 mb-3">Continue</button>
              </form>
              <p class="text-center text-muted small mb-0">
                <i class="bi bi-shield-check me-1"></i>30-day money-back guarantee
              </p>
          </div>
        </div>

        <x-terms-and-conditions />

      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const pricingOptions = @json($priceOptions);
        const cycleSelect = document.querySelector('[data-checkout-cycle]');
        const discountPercent = {{ $discountPercent }};

        const formatAmount = (value) => {
            const number = Number(value) || 0;
            const formatted = number.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            return formatted.replace(/(\.\d*?)0+$/, '$1').replace(/\.$/, '');
        };

        const updateViews = (key) => {
            const option = pricingOptions[key];
            if (!option) {
                return;
            }

            const amount = Number(option.amount) || 0;
            const months = Number(option.months) || 0;
            const keyName = (option.key || '').toString().toLowerCase();
            const isDiscountEligible = discountPercent > 0 && (
                keyName === 'yearly' ||
                months >= 12
            );
            const discountAmount = isDiscountEligible ? (amount * (discountPercent / 100)) : 0;
            const subtotalAmount = amount - discountAmount;
            const subtotalFormatted = formatAmount(subtotalAmount);

            document.querySelectorAll('[data-checkout="price-symbol"]').forEach(el => el.textContent = option.symbol || '');
            document.querySelectorAll('[data-checkout="price-per-month"]').forEach(el => el.textContent = option.per_month_formatted || '0');
            document.querySelectorAll('[data-checkout="price-total"]').forEach(el => el.textContent = option.amount_formatted || '0');
            document.querySelectorAll('[data-checkout="plan-symbol"]').forEach(el => el.textContent = option.symbol || '');
            document.querySelectorAll('[data-checkout="plan-amount"]').forEach(el => el.textContent = option.amount_formatted || '0');
            document.querySelectorAll('[data-checkout="subtotal-symbol"]').forEach(el => el.textContent = option.symbol || '');
            document.querySelectorAll('[data-checkout="summary-symbol"]').forEach(el => el.textContent = option.symbol || '');
            document.querySelectorAll('[data-checkout="subtotal-amount"]').forEach(el => el.textContent = subtotalFormatted);
            document.querySelectorAll('[data-checkout="summary-total"]').forEach(el => el.textContent = subtotalFormatted);
            document.querySelectorAll('[data-checkout="summary-cycle"]').forEach(el => el.textContent = option.label || '');
            document.querySelectorAll('[data-checkout="cycle-description"]').forEach(el => el.textContent = option.description || '');
            document.querySelectorAll('[data-checkout="discount-percent"]').forEach(el => el.textContent = `${discountPercent}%`);
            document.querySelectorAll('[data-checkout="discount-row"]').forEach(el => el.classList.toggle('d-none', !isDiscountEligible));
            document.querySelectorAll('[data-checkout="save-badge"]').forEach(el => el.classList.toggle('d-none', !isDiscountEligible));

            const dueText = document.querySelector('[data-checkout="due-text"]');
            if (dueText) {
                const plural = months > 1 ? 'months' : 'month';
                dueText.textContent = `Billed ${months} ${plural} at ${option.symbol}${option.amount_formatted}. Renews automatically.`;
            }

            const billingInput = document.querySelector('input[name="billing_cycle_id"]');
            if (billingInput && option.billing_cycle_id) {
                billingInput.value = option.billing_cycle_id;
            }

            const amountInput = document.querySelector('input[name="amount"]');
            const currencyInput = document.querySelector('input[name="currency"]');
            if (amountInput) amountInput.value = subtotalAmount ?? 0;
            if (currencyInput) currencyInput.value = option.currency_code || currencyInput.value;
        };

        cycleSelect?.addEventListener('change', (event) => {
            updateViews(event.target.value);
        });

        updateViews(cycleSelect?.value || '{{ $selectedCycle }}');
    })();
</script>
</body>
</html>
