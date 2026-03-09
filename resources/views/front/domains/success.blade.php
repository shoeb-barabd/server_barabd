<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Domain Registration Successful — BARABD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>body{font-family:'Poppins',sans-serif;background:#f5f7fa;}</style>
</head>
<body>
<div class="container py-5">
  <div class="card shadow-sm mx-auto" style="max-width: 560px;">
    <div class="card-body text-center py-5">
      <div class="mb-3"><i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i></div>
      <h3 class="text-success fw-bold mb-3">Domain Registered Successfully!</h3>
      <p class="mb-2">Your domain has been registered and is being set up.</p>
      @isset($order)
        <div class="bg-light rounded p-3 mb-3 text-start" style="max-width:380px;margin:0 auto;">
          <div class="mb-1"><strong>Domain:</strong> {{ $order->domain_name }}</div>
          <div class="mb-1"><strong>Transaction ID:</strong> {{ $order->tran_id }}</div>
          <div class="mb-1"><strong>Amount:</strong> ৳{{ number_format($order->amount, 2) }}</div>
          <div class="mb-1"><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($order->status) }}</span></div>
          @if($order->expiry_date)
            <div><strong>Expires:</strong> {{ $order->expiry_date->format('d M Y') }}</div>
          @endif
        </div>
        <div class="text-muted small mb-3">
          <i class="bi bi-info-circle"></i> Nameservers: {{ implode(', ', config('openprovider.default_ns', ['ns1.barabdonline.xyz', 'ns2.barabdonline.xyz'])) }}
        </div>
      @endisset
      <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('domains.index') }}" class="btn btn-outline-secondary">Search More Domains</a>
        @auth
          <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Dashboard</a>
        @endauth
      </div>
    </div>
  </div>
</div>
</body>
</html>
