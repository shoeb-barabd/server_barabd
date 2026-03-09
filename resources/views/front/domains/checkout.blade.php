<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Domain Checkout — BARABD</title>
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
    .order-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
    .domain-highlight { background: linear-gradient(135deg, #016A4D, #023E2E); color: #fff; border-radius: 12px 12px 0 0; padding: 24px; }
    .domain-highlight h3 { font-weight: 700; margin: 0; }
    .section-title { font-weight: 600; font-size: 1rem; color: #016A4D; border-bottom: 2px solid #e0e0e0; padding-bottom: 8px; }
  </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
      <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="40">
      <span class="fw-bold"><span class="text-danger">bara</span><span style="color:#016A4D">bd Data Center</span></span>
    </a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('domains.index') }}">
      <i class="bi bi-arrow-left"></i> Back to Search
    </a>
  </div>
</nav>

<main class="py-5">
  <div class="container" style="max-width: 800px;">

    @if($errors->any())
      <div class="alert alert-danger mb-3">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <div class="order-card">
      <div class="domain-highlight text-center">
        <div class="text-uppercase small opacity-75 mb-1">{{ $action === 'transfer' ? 'Domain Transfer' : 'Domain Registration' }}</div>
        <h3>{{ $sld }}{{ $tld }}</h3>
        <div class="mt-2 fs-4 fw-bold">৳{{ number_format($price, 2) }} <small class="fw-normal opacity-75">/year</small></div>
      </div>

      <div class="p-4">
        <form method="POST" action="{{ route('domains.purchase') }}">
          @csrf
          <input type="hidden" name="sld" value="{{ $sld }}">
          <input type="hidden" name="tld" value="{{ $tld }}">
          <input type="hidden" name="action" value="{{ $action }}">

          <!-- Duration -->
          <div class="mb-4">
            <div class="section-title mb-3">Registration Period</div>
            <select name="years" class="form-select" id="yearsSelect">
              @for($y = 1; $y <= 5; $y++)
                <option value="{{ $y }}" data-price="{{ $price * $y }}">
                  {{ $y }} {{ $y === 1 ? 'Year' : 'Years' }} — ৳{{ number_format($price * $y, 2) }}
                </option>
              @endfor
            </select>
          </div>

          @if($action === 'transfer')
          <div class="mb-4">
            <div class="section-title mb-3">Transfer Details</div>
            <div class="mb-3">
              <label class="form-label">Auth/EPP Code <span class="text-danger">*</span></label>
              <input type="text" name="auth_code" class="form-control" value="{{ old('auth_code') }}" required>
              <small class="text-muted">Get this from your current domain registrar</small>
            </div>
          </div>
          @endif

          <!-- Contact Info -->
          <div class="section-title mb-3">Registrant Information</div>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()?->first_name) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()?->last_name) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()?->email) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone <span class="text-danger">*</span></label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="+8801XXXXXXXXX">
            </div>
            <div class="col-12">
              <label class="form-label">Street Address <span class="text-danger">*</span></label>
              <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">City <span class="text-danger">*</span></label>
              <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">State</label>
              <input type="text" name="state" class="form-control" value="{{ old('state') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">Postal Code <span class="text-danger">*</span></label>
              <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" required>
            </div>
            <input type="hidden" name="country_code" value="BD">
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-lg text-white fw-semibold" style="background: #016A4D;">
              <i class="bi bi-lock"></i> Pay ৳<span id="totalPrice">{{ number_format($price, 2) }}</span> via SSLCommerz
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('yearsSelect').addEventListener('change', function() {
    const price = this.options[this.selectedIndex].dataset.price;
    document.getElementById('totalPrice').textContent = Number(price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
});
</script>
</body>
</html>
