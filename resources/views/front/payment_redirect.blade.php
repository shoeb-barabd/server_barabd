<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment</title>
    <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container text-center">
        <div class="card mx-auto shadow-sm" style="max-width: 520px;">
            <div class="card-body py-4">
                <div class="mb-3">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <h5 class="fw-semibold mb-2">Redirecting to payment</h5>
                <p class="text-muted mb-3">Please wait while we initialize your payment.</p>
                <div class="text-muted small mb-3">
                    Amount: <strong>{{ $payload['currency'] }} {{ number_format((float)$payload['amount'], 2) }}</strong>
                </div>
                <form id="pay-forward-form" method="POST" action="{{ route('ssl.pay') }}">
                    @csrf
                    @foreach($payload as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
                <button class="btn btn-primary" onclick="document.getElementById('pay-forward-form').submit();">
                    Continue
                </button>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', () => {
            document.getElementById('pay-forward-form').submit();
        });
    </script>
</body>
</html>
