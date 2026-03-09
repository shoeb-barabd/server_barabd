<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Domain Registration — BARABD</title>
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f5f7fa; }
    .hero-domain { background: linear-gradient(135deg, #016A4D 0%, #023E2E 100%); color: #fff; padding: 60px 0 50px; }
    .hero-domain h1 { font-size: 2.2rem; font-weight: 800; }
    .hero-domain p { opacity: .85; font-size: 1.05rem; }
    .domain-search-box { max-width: 680px; margin: 0 auto; }
    .domain-search-box .input-group { background: #fff; border-radius: 50px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.15); }
    .domain-search-box .form-control { border: none; padding: 16px 24px; font-size: 1.05rem; }
    .domain-search-box .form-control:focus { box-shadow: none; }
    .domain-search-box .btn-search { border: none; background: #e53935; color: #fff; padding: 12px 32px; font-weight: 600; border-radius: 50px !important; margin: 4px; }
    .domain-search-box .btn-search:hover { background: #c62828; }
    .tld-badge { display: inline-block; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); border-radius: 20px; padding: 5px 14px; margin: 4px; font-size: .85rem; }
    .pricing-card { background: #fff; border-radius: 12px; padding: 24px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,.06); transition: transform .2s; }
    .pricing-card:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
    .pricing-card .tld-name { font-size: 1.6rem; font-weight: 700; color: #016A4D; }
    .pricing-card .price { font-size: 1.3rem; font-weight: 600; color: #333; }
    .pricing-card .price small { font-size: .75rem; color: #888; font-weight: 400; }
    .result-item { background: #fff; border-radius: 10px; padding: 16px 20px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
    .result-item.available { border-left: 4px solid #4caf50; }
    .result-item.taken { border-left: 4px solid #ef5350; opacity: .7; }
    .result-domain { font-size: 1.1rem; font-weight: 600; }
    .result-price { font-size: 1rem; font-weight: 600; color: #016A4D; }
    .badge-available { background: #e8f5e9; color: #2e7d32; }
    .badge-taken { background: #ffebee; color: #c62828; }

    #searchResults { display: none; }
    #searchSpinner { display: none; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
      <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="40">
      <span class="fw-bold"><span class="text-danger">bara</span><span style="color:#016A4D">bd Data Center</span></span>
    </a>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="{{ url('/') }}">
        <i class="bi bi-arrow-left"></i> Home
      </a>
      @auth
        <a class="btn btn-outline-primary btn-sm" href="{{ route('user.dashboard') }}">Dashboard</a>
      @else
        <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
      @endauth
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero-domain">
  <div class="container text-center">
    <h1>Find Your Perfect Domain Name</h1>
    <p class="mb-4">Search and register your ideal domain name starting from just ৳{{ number_format($tlds->min('register_price') ?? 0) }}/year</p>

    <div class="domain-search-box">
      <div class="input-group">
        <input type="text" id="domainInput" class="form-control" placeholder="Type your desired domain name..." autocomplete="off">
        <button class="btn btn-search" id="searchBtn" type="button">
          <i class="bi bi-search"></i> Search
        </button>
      </div>
    </div>

    <div class="mt-3">
      @foreach($tlds->take(8) as $t)
        <span class="tld-badge">{{ $t->tld }} ৳{{ number_format($t->register_price) }}</span>
      @endforeach
    </div>
  </div>
</section>

<!-- SEARCH RESULTS -->
<section class="py-4">
  <div class="container" style="max-width: 800px;">
    <div id="searchSpinner" class="text-center py-4">
      <div class="spinner-border text-success" role="status"></div>
      <p class="mt-2 text-muted">Checking availability...</p>
    </div>

    <div id="searchResults"></div>
  </div>
</section>

<!-- TLD PRICING GRID -->
<section class="py-5">
  <div class="container">
    <h3 class="text-center fw-bold mb-4">Domain Pricing</h3>
    <div class="row g-3">
      @foreach($tlds as $t)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="pricing-card">
            <div class="tld-name">{{ $t->tld }}</div>
            <div class="price mt-2">
              ৳{{ number_format($t->register_price) }} <small>/yr</small>
            </div>
            <div class="text-muted small mt-1">Renew: ৳{{ number_format($t->renew_price) }}/yr</div>
            @if($t->transfer_price > 0)
              <div class="text-muted small">Transfer: ৳{{ number_format($t->transfer_price) }}</div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4">
  <div class="container text-center">
    <p class="mb-0 small">&copy; {{ date('Y') }} BarabdOnline.xyz — All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('domainInput');
    const btn = document.getElementById('searchBtn');
    const resultsDiv = document.getElementById('searchResults');
    const spinner = document.getElementById('searchSpinner');

    function doSearch() {
        const domain = input.value.trim();
        if (domain.length < 2) return;

        spinner.style.display = 'block';
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';

        fetch("{{ route('domains.search') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ domain: domain }),
        })
        .then(r => r.json())
        .then(data => {
            spinner.style.display = 'none';
            resultsDiv.style.display = 'block';

            if (data.error) {
                resultsDiv.innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
                return;
            }

            let html = '<h5 class="fw-bold mb-3">Results for "<span class="text-success">' + data.sld + '</span>"</h5>';

            (data.results || []).forEach(function(r) {
                const isFree = (r.status === 'free');
                const cls = isFree ? 'available' : 'taken';
                const badge = isFree
                    ? '<span class="badge badge-available rounded-pill px-3 py-1">Available</span>'
                    : '<span class="badge badge-taken rounded-pill px-3 py-1">Taken</span>';

                const price = r.register_price
                    ? '৳' + Number(r.register_price).toLocaleString() + '/yr'
                    : '';

                const actionBtn = isFree
                    ? '<a href="{{ route("domains.checkout") }}?domain=' + encodeURIComponent(data.sld) + '&tld=' + encodeURIComponent(r.tld) + '&action=register" class="btn btn-sm btn-success">Register</a>'
                    : '<a href="{{ route("domains.checkout") }}?domain=' + encodeURIComponent(data.sld) + '&tld=' + encodeURIComponent(r.tld) + '&action=transfer" class="btn btn-sm btn-outline-secondary btn-sm">Transfer</a>';

                html += '<div class="result-item ' + cls + '">'
                    + '<div><span class="result-domain">' + r.domain + '</span> ' + badge + '</div>'
                    + '<div class="d-flex align-items-center gap-3">'
                    + '<span class="result-price">' + price + '</span>'
                    + actionBtn
                    + '</div></div>';
            });

            resultsDiv.innerHTML = html;
        })
        .catch(err => {
            spinner.style.display = 'none';
            resultsDiv.style.display = 'block';
            resultsDiv.innerHTML = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
        });
    }

    btn.addEventListener('click', doSearch);
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') doSearch();
    });
});
</script>
</body>
</html>
