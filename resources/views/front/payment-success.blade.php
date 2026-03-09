<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payment Successful - BARABD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-sm mx-auto" style="max-width: 520px;">
    <div class="card-body text-center">
      <h3 class="text-success mb-3">Payment Successful 🎉</h3>
      <p class="mb-2">Thank you! Your payment has been received.</p>
      @isset($payment)
        <p class="small text-muted mb-1">Transaction ID: <strong>{{ $payment->tran_id }}</strong></p>
        <p class="small text-muted mb-3">Amount: <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></p>
      @endisset
      <a href="{{ route('customize') }}" class="btn btn-primary">Back to Customize</a>
    </div>
  </div>
</div>
</body>
</html>
