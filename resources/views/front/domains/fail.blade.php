<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Domain Payment Failed — BARABD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light" style="font-family:'Poppins',sans-serif;">
<div class="container py-5">
  <div class="card shadow-sm mx-auto" style="max-width: 520px;">
    <div class="card-body text-center py-5">
      <div class="mb-3"><i class="bi bi-x-circle-fill text-danger" style="font-size:3rem;"></i></div>
      <h3 class="text-danger fw-bold mb-3">Payment Failed</h3>
      <p>Your domain payment could not be processed.</p>
      @isset($order)
        <p class="small text-muted">Domain: <strong>{{ $order->domain_name }}</strong></p>
      @endisset
      <a href="{{ route('domains.index') }}" class="btn btn-primary mt-3">Try Again</a>
    </div>
  </div>
</div>
</body>
</html>
