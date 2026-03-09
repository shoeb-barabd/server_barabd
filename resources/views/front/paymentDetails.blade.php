<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details | {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- intl-tel-input CSS --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background: radial-gradient(circle at top left, #e6f2ff, #f7f9fb); }
        .card { border: none; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); }
        .section-title { font-weight: 700; font-size: 18px; }
        .badge-soft { background: #eaf6ff; color: #0b5ed7; border-radius: 999px; padding: 6px 12px; font-weight: 600; }
        .summary-card .label { color: #6c757d; text-transform: uppercase; letter-spacing: .04em; font-size: 12px; }
        .due-box { background: linear-gradient(135deg, #0d6efd, #5ac8fa); color: #fff; border-radius: 14px; padding: 18px; }
        .form-label { font-weight: 600; color: #243b53; }
    </style>
</head>
<body>
@php
    $amount = number_format((float) ($paymentDetail->amount ?? 0), 2);
    $currency = $paymentDetail->currency ?? 'BDT';
    $symbolMap = ['BDT' => 'Tk', 'USD' => '$', 'EUR' => 'EUR', 'GBP' => 'GBP', 'INR' => 'INR', 'AED' => 'AED', 'SLE' => 'SLE'];
    $symbol = $symbolMap[$currency] ?? $currency;
    $checkoutUrl = $paymentDetail->product_id ? route('checkout', $paymentDetail->product_id) : url('/');

    // phone field prefill এর জন্য
    $oldPhoneFull = old('phone_full', $paymentDetail->phone_full ?? '');
    $oldPhoneCountryCode = old('phone_country_code', $paymentDetail->phone_country_code ?? '');
    $oldPhoneLocal = old('phone', $paymentDetail->phone ?? '');
@endphp

<nav class="navbar navbar-light bg-white border-bottom">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="40">
            <span class="fw-bold"><span class="text-danger">bara</span><span class="text-success">bd</span> Data Center</span>
        </a>
        <a class="btn btn-outline-secondary rounded-pill btn-sm d-flex align-items-center gap-2" href="{{ $checkoutUrl }}">
            <i class="bi bi-arrow-left"></i>
            Back to Checkout
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="mb-4">
        <p class="text-uppercase text-muted small mb-1">Step 2</p>
        <h2 class="fw-bold mb-1">Payment Details</h2>
        <p class="text-muted mb-0">Confirm who and where we are billing before completing your order.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Please fix the errors below:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4 align-items-start">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('checkout.paymentDetails.update', $paymentDetail) }}">
                @csrf

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="section-title mb-3">Domain</div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Domain Name</label>
                                <input type="text" name="domain_name" class="form-control"
                                       value="{{ old('domain_name', $paymentDetail->domain_name) }}"
                                       placeholder="yourdomain">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TLD</label>
                                <select name="tld" class="form-select">
                                    @php $tld = old('tld', $paymentDetail->tld); @endphp
                                    @foreach(['.com','.net','.org','.xyz','.info','.co','.bd','.shop','.online'] as $option)
                                        <option value="{{ $option }}" @selected($tld === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="section-title mb-0">Billing Contact</div>
                                <small class="text-muted">Who should we reach out to about this order?</small>
                            </div>
                            <span class="badge-soft">Order #{{ $paymentDetail->id }}</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name', $prefillFirst) }}"
                                       placeholder="John" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name', $prefillLast) }}"
                                       placeholder="Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $prefillEmail) }}"
                                       placeholder="you@example.com" required>
                            </div>

                            {{-- PHONE FIELD WITH AUTO COUNTRY CODE --}}
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input id="phone" type="tel" class="form-control"
                                       placeholder="Enter phone number" required>

                                {{-- hidden fields: এগুলোই database এ যাবে --}}
                                <input type="hidden" name="phone_country_code" id="phone_country_code"
                                       value="{{ $oldPhoneCountryCode }}">
                                <input type="hidden" name="phone" id="phone_number"
                                       value="{{ $oldPhoneLocal }}">
                                <input type="hidden" name="phone_full" id="phone_full"
                                       value="{{ $oldPhoneFull }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="section-title mb-3">Billing Address</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                <input type="text" name="street_address" class="form-control"
                                       value="{{ old('street_address', $paymentDetail->street_address) }}"
                                       placeholder="House, road" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Street Address 2 (Optional)</label>
                                <input type="text" name="street_address_2" class="form-control"
                                       value="{{ old('street_address_2', $paymentDetail->street_address_2) }}"
                                       placeholder="Apartment, suite, etc.">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control"
                                       value="{{ old('city', $paymentDetail->city) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control"
                                       value="{{ old('state', $paymentDetail->state) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control"
                                       value="{{ old('postal_code', $paymentDetail->postal_code) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-check-circle me-2"></i>Save & Continue
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card summary-card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="label mb-1">Plan</div>
                            <div class="fw-bold">{{ $paymentDetail->plan_title }}</div>
                            <div class="text-muted small">{{ $paymentDetail->billingCycle->name ?? 'Billing cycle' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold h4 mb-0">{{ $symbol }}{{ $amount }}</div>
                            <div class="text-muted small">{{ $currency }}</div>
                        </div>
                    </div>
                    <div class="mb-2 text-muted small">
                        Location: {{ $paymentDetail->location->name ?? 'Not set' }}
                    </div>
                    <div class="due-box">
                        <div class="small text-white-50">Total due today</div>
                        <div class="fs-3 fw-bold mb-0">{{ $symbol }}{{ $amount }}</div>
                        <div class="small">{{ $currency }} payment via selected method</div>
                    </div>
                    <div class="d-flex gap-2 align-items-center mt-3 text-muted small">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>Secure checkout. 30-day money-back guarantee.</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-info-circle text-primary"></i>
                        <div>
                            <div class="fw-semibold">Need help?</div>
                            <div class="text-muted small mb-1">Our team can assist with payment or domain questions.</div>
                            <a href="mailto:support@yourdomain.com" class="small">support@yourdomain.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- intl-tel-input JS --}}
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.querySelector("#phone");
        const hiddenCountry = document.querySelector("#phone_country_code");
        const hiddenLocal   = document.querySelector("#phone_number");
        const hiddenFull    = document.querySelector("#phone_full");
        const form          = document.querySelector("form");

        const iti = window.intlTelInput(input, {
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                fetch("https://ipapi.co/json/")
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        callback((data && data.country_code) ? data.country_code.toLowerCase() : "bd");
                    })
                    .catch(function () {
                        callback("bd");
                    });
            },
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });

        // পুরোনো value থাকলে (edit / validation error), সেটাকে সেট করো
        const initialNumber = @json($oldPhoneFull);
        if (initialNumber) {
            iti.setNumber(initialNumber);
            input.value = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
        } else if (hiddenLocal.value) {
            // শুধু local part থাকলে (আগের data), সেটাও ধরে রাখি
            input.value = hiddenLocal.value;
        }

        form.addEventListener("submit", function () {
            const countryData = iti.getSelectedCountryData();

            hiddenCountry.value = countryData.dialCode ? "+" + countryData.dialCode : "";
            hiddenFull.value    = iti.getNumber(); // international format (+88017...)
            hiddenLocal.value   = input.value.trim();
        });
    });
</script>
</body>
</html>
