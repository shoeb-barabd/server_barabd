@extends('layouts.app')
@section('title','Domain Order Details')
@section('page_title','Domain Order Details')
@section('breadcrumb','Domain Orders / View')

@section('content')
<div class="card card-primary card-outline">
  <div class="card-header d-flex justify-content-between">
    <h5 class="m-0">{{ $order->domain_name }}</h5>
    <a href="{{ route('admin.domain-orders.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
  </div>
  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-3">Domain Info</h6>
        <table class="table table-borderless table-sm">
          <tr><th width="40%">Domain</th><td>{{ $order->domain_name }}</td></tr>
          <tr><th>SLD</th><td>{{ $order->sld }}</td></tr>
          <tr><th>TLD</th><td>{{ $order->tld }}</td></tr>
          <tr><th>Action</th><td><span class="badge bg-info">{{ ucfirst($order->action) }}</span></td></tr>
          <tr><th>Years</th><td>{{ $order->years }}</td></tr>
          <tr><th>Status</th><td>
            @php $colors = ['active'=>'success','paid'=>'primary','pending'=>'warning','failed'=>'danger','cancelled'=>'secondary','expired'=>'dark']; @endphp
            <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
          </td></tr>
          <tr><th>Registration</th><td>{{ $order->registration_date?->format('d M Y') ?? '-' }}</td></tr>
          <tr><th>Expiry</th><td>{{ $order->expiry_date?->format('d M Y') ?? '-' }}</td></tr>
          <tr><th>Nameservers</th><td>{{ implode(', ', $order->nameservers ?? []) ?: '-' }}</td></tr>
          <tr><th>OP Domain ID</th><td>{{ $order->op_domain_id ?? '-' }}</td></tr>
          <tr><th>OP Status</th><td>{{ $order->op_status ?? '-' }}</td></tr>
        </table>
      </div>

      <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-3">Payment & Customer</h6>
        <table class="table table-borderless table-sm">
          <tr><th width="40%">Amount</th><td>{{ number_format($order->amount, 2) }} {{ $order->currency }}</td></tr>
          <tr><th>Tran ID</th><td>{{ $order->tran_id ?? '-' }}</td></tr>
          <tr><th>Customer</th><td>{{ $order->user?->email ?? 'Guest' }}</td></tr>
          <tr><th>Ordered</th><td>{{ $order->created_at?->format('d M Y H:i') }}</td></tr>
        </table>

        @if($order->registrant)
          <h6 class="fw-bold text-muted mb-3 mt-3">Registrant Contact</h6>
          <table class="table table-borderless table-sm">
            @foreach($order->registrant as $key => $val)
              <tr><th width="40%">{{ ucwords(str_replace('_',' ', $key)) }}</th><td>{{ $val }}</td></tr>
            @endforeach
          </table>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
