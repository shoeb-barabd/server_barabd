<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Customize - BARABD</title>
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('front/css/customize.css') }}">


</head>
<body>
<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
      <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="40">
      <span class="fw-bold">
        <span style="color:#e63946;">bara</span><span style="color:#016A4D;">bd Data Center</span>
      </span>
    </a>
    <a class="btn btn-success rounded-pill back-home-btn d-flex align-items-center gap-2" href="{{ route('index') }}">
      <i class="bi bi-arrow-left"></i>
      Back to Home
    </a>
  </div>
</nav>

<main id="custom-builder" class="py-4">
  <div class="container">

    {{-- Dynamic title --}}
    <div class="text-center mb-4">
      <h2 class="fw-bold mb-1" id="builderTitle">
        Build Your Custom {{ $categories->first()?->name ?? 'Hosting' }} Plan
      </h2>
      <p class="text-muted mb-0">Configure resources according to your specific needs.</p>
    </div>

    {{-- Category pills --}}
    @if($categories->count())
      <div class="cat-pill-tray mb-4">
        @foreach($categories as $cat)
          <button
            type="button"
            class="btn btn-sm custom-cat-pill {{ $loop->first ? 'active' : '' }}"
            data-group="{{ $cat->slug }}"
            data-title="Build Your Custom {{ $cat->name }} Plan"
            data-name="{{ $cat->name }}">
            {{ $cat->name }}
          </button>
        @endforeach
      </div>
    @endif

    <div class="row g-4 align-items-start">
      <div class="col-lg-8">
        <div class="card soft-card mb-3 builder-shell">
          <div class="card-body">

            <div class="cart-card-head builder-head">
              <div>
                <p class="eyebrow text-uppercase mb-1">Custom builder</p>
                <h3 class="mb-1" id="cartPlanTitle">{{ $categories->first()?->name ?? 'Custom Plan' }}</h3>
                <span class="text-muted small">Fine-tune every resource before checkout.</span>
              </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3 builder-controls">
              <div class="builder-location">
                <span class="label">Location</span>
                <div class="location-select-wrap">
                  <select id="locationSelect" class="form-select form-select-sm location-select">
                    @foreach($locations as $loc)
                      <option value="{{ $loc->id }}"
                              data-currency="{{ $loc->currency_code }}"
                              @selected($loc->id == $defaultLocationId)>
                        {{ $loc->name }} ({{ $loc->currency_code }})
                      </option>
                    @endforeach
                  </select>
                  <span class="location-caret"><i class="bi bi-caret-down-fill"></i></span>
                </div>
              </div>
              <div class="btn-group btn-group-sm d-flex gap-2" role="group">
                <input type="radio" class="btn-check" name="billing_cycle" id="billingMonthly" value="monthly" checked>
                <label class="btn btn-outline-secondary " for="billingMonthly">Monthly</label>

                <input type="radio" class="btn-check" name="billing_cycle" id="billingAnnual" value="annual">
                <label class="btn btn-outline-secondary" for="billingAnnual">Annual</label>
              </div>
            </div>

            <div class="cart-meta">
              <div class="cart-chip">
                <span class="text-muted small d-block">Category</span>
                <span class="fw-semibold" id="chipCategory">{{ $categories->first()?->name ?? 'Category' }}</span>
              </div>
              <div class="cart-chip">
                <span class="text-muted small d-block">Location</span>
                <span class="fw-semibold" id="chipLocation">{{ $locations->firstWhere('id', $defaultLocationId)?->name ?? 'Location' }}</span>
              </div>
              <div class="cart-chip">
                <span class="text-muted small d-block">Billing</span>
                <span class="fw-semibold" id="chipCycle">Monthly Billing</span>
              </div>
            </div>

            {{-- Categories --}}
            @foreach($categories as $category)
              @php
                $customFeatures = $category->customizeFeatures;
                $categoryAddOns = $addOns->filter(function($addon) use ($category) {
                    return (int)$addon->category_id === (int)$category->id || is_null($addon->category_id);
                });
              @endphp
              @if($customFeatures->isNotEmpty())
                <div class="group-card {{ $loop->first ? 'active' : 'd-none' }}"
                     data-group="{{ $category->slug }}"
                     data-category-name="{{ $category->name }}">
                  <h5 class="fw-semibold mb-3 text-teal">
                    Configure {{ $category->name }} Resources
                  </h5>

                  <div class="row gy-3 mb-3">
                    @foreach($customFeatures as $feature)
                      <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                          <span class="text-teal fw-medium small">{{ $feature->label }}</span>
                          @if($feature->input_type == 'number')
                            <span class="badge bg-light text-dark border">
                              <span class="qty-val">{{ $feature->min }}</span> {{ $feature->unit }}
                            </span>
                          @endif
                        </div>

                        @if($feature->input_type == 'number')
                          <div class="qty-box"
                               data-group="{{ $category->slug }}"
                               data-key="{{ $feature->key }}"
                               data-label="{{ $feature->label }}"
                               data-unit="{{ $feature->unit }}"
                               data-step="{{ $feature->step }}"
                               data-min="{{ $feature->min }}"
                               data-max="{{ $feature->max }}">
                            <button class="btn-qty" data-act="dec">-</button>
                            <input type="range" class="form-range flex-grow-1 kawaii-range"
                                   min="{{ $feature->min }}"
                                   max="{{ $feature->max }}"
                                   step="{{ $feature->step }}"
                                   value="{{ $feature->min }}">
                            <span class="qty-unit">{{ $feature->unit }}</span>
                            <button class="btn-qty" data-act="inc">+</button>
                          </div>
                        @elseif($feature->input_type == 'boolean')
                          <div class="form-check form-switch">
                            <input class="form-check-input feature-check" type="checkbox"
                                   data-group="{{ $category->slug }}"
                                   data-feature="{{ $feature->key }}"
                                   data-label="{{ $feature->label }}">
                            <label class="form-check-label small text-muted">
                              Enable {{ $feature->label }}
                            </label>
                          </div>
                        @elseif($feature->input_type == 'select')
                          @php $opts = is_array($feature->options_json) ? $feature->options_json : []; @endphp
                          <select class="form-select form-select-sm feature-select"
                                  data-group="{{ $category->slug }}"
                                  data-key="{{ $feature->key }}"
                                  data-label="{{ $feature->label }}">
                            @foreach($opts as $option)
                              <option value="{{ $option['value'] ?? '' }}"
                                      {{ $loop->first ? 'selected' : '' }}
                                      data-price="{{ $option['price'] ?? 0 }}">
                                {{ $option['label'] ?? 'Option' }}
                              </option>
                            @endforeach
                          </select>
                        @endif
                      </div>
                    @endforeach
                  </div>

                  @if($categoryAddOns->isNotEmpty())
                    <h6 class="fw-semibold mt-2 mb-2">Additional Features & Services</h6>
                    <div class="row g-2">
                      @foreach($categoryAddOns as $addon)
                        <div class="col-md-6">
                          <label class="addon-pill">
                            <div class="d-flex align-items-center gap-2">
                              <input type="checkbox" class="addon-svc"
                                     data-key="{{ $addon->key }}"
                                     data-label="{{ $addon->label }}"
                                     data-category-id="{{ $addon->category_id }}">
                              <span class="title">{{ $addon->label }}</span>
                            </div>
                            <span class="price" data-price-text>-</span>
                          </label>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endif
            @endforeach

          </div>
        </div>

        <div class="coupon-box">
          <div>
            <h5>Have a coupon?</h5>
            <p class="mb-2">Apply your promo code and lock in extra savings instantly.</p>
          </div>
          <div class="input-group input-group-lg">
            <input type="text" id="couponCodeInput" class="form-control" placeholder="Enter coupon code">
            <button class="btn btn-outline-dark" type="button" id="couponApplyBtn">Apply</button>
          </div>
          <div id="couponMessage" class="coupon-message small"></div>
          <small>Coupons are available for logged-in customers during special campaigns.</small>
        </div>
        <div class="coupon-note">Lock this price before the promotion ends.</div>

      </div>



      <div class="col-lg-4">
        <div class="summary-card" style="top: 90px;">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-uppercase small text-muted mb-1">Order Summary</p>
              <h4 class="fw-bold mb-1" id="summaryPlanTitle">{{ $categories->first()?->name ?? 'Custom Plan' }}</h4>
              <span class="badge text-bg-light text-secondary fw-semibold" id="summaryCycleTag">Monthly Billing</span>
            </div>
            <div class="text-end small">
              <div class="text-muted">Location</div>
              <div class="fw-semibold" id="summary-location">
                {{ $locations->firstWhere('id', $defaultLocationId)?->name ?? 'N/A' }}
              </div>
            </div>
          </div>

          <div class="summary-breakdown mb-3">
            <div class="text-uppercase small text-muted mb-2">Plan details</div>
            <div id="summary-lines" class="vstack gap-1 small">
              <div class="text-secondary text-center">Select category & change values.</div>
            </div>
          </div>

          <div class="summary-breakdown">
            <div class="line">
              <span>Subtotal</span>
              <span id="summary-subtotal">0.00</span>
            </div>
            <div class="line d-none" id="summary-discount-row">
              <span id="summary-discount-label">Discount</span>
              <span id="summary-discount-value">-0.00</span>
            </div>
            <div class="line">
              <strong>Total Due</strong>
              <strong id="summary-total">0.00</strong>
            </div>
          </div>

 <form id="orderForm" method="POST" action="{{ route('checkout.paymentDetails.store') }}">
  @csrf
  <input type="hidden" name="product_id" id="orderProductId">
  <input type="hidden" name="category_id" id="orderCategoryId">
  <input type="hidden" name="location_id" id="orderLocationId">
  <input type="hidden" name="billing_cycle_id" id="orderBillingCycleId">
  <input type="hidden" name="plan_title" id="orderPlanTitle">
  <input type="hidden" name="features_json" id="orderFeaturesJson">
  <input type="hidden" name="add_ons_json" id="orderAddonsJson">
  <input type="hidden" name="amount" id="orderAmount">
  <input type="hidden" name="currency" id="orderCurrency">

  <button type="submit"
          class="btn btn-primary w-100 mt-3"
          id="orderNowBtn"
          style="padding: 14px; font-size: 16px; font-weight: 600;">
    Continue
  </button>
</form>
<div class="text-muted text-center small mt-2">30-day money-back guarantee</div>
        </div>


        <div class="card soft-card mt-3">
          <div class="card-body">
            <h4 class="h6 fw-bold mb-3 text-center">Terms & Conditions</h4>
            <ol class="tc-list small">
              <li><strong>Service Activation.</strong> Activated after payment verification.</li>
              <li><strong>Fair Usage.</strong> No illegal/abusive content or activity.</li>
              <li><strong>Refunds.</strong> 7-day refund if we fail to deliver (gateway fees non-refundable).</li>
              <li><strong>Billing Cycle.</strong> Monthly unless stated otherwise.</li>
              <li><strong>Backups.</strong> Keep your own backups; paid backup available.</li>
              <li><strong>Support.</strong> 24/7 outage support; business-hour general queries.</li>
              <li><strong>Abuse & Suspension.</strong> Violations may be suspended.</li>
              <li><strong>Policy Changes.</strong> Continued use means acceptance.</li>
              <li><strong>VAT,TAX,SD and other revenue charge may apply.It depends on Government law.</strong></li>
            </ol>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




<script>
  window.CUSTOMIZE = {
    defaultLocationId: @json($defaultLocationId),
    locations: @json($locations),
    billingCycles: @json($billingCycles),
    categories: @json($categoriesPayload ?? []),
    addOns: @json($addOns),
    symbols: { BDT: 'Tk', USD: '$', EUR: 'EUR', AED: 'AED', SLE: 'Le', GBP: 'GBP', INR: 'INR' },
    isAuthenticated: @json(auth()->check()),
    routes: {
      claimCoupon: @json(route('coupons.claim')),
      login: @json(route('login')),
    }
  };
</script>

<script src="{{ asset('front/js/customize.js') }}"></script>

</body>
</html>
