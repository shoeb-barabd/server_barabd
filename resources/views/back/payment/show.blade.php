@extends('layouts.app')
@section('title','View Payment')
@section('page_title','Payment Details')
@section('breadcrumb','Payments / Details')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <h5 class="m-0">Payment {{ $payment->tran_id }}</h5>
    <div class="ms-auto d-flex gap-2">
      <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">Back</a>
      <a href="{{ route('admin.payments.pdf', $payment) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-download"></i> Download PDF
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="row">
      <div class="col-lg-6">
        <table class="table table-sm">
          <tbody>
            <tr><th style="width:180px">Transaction ID</th><td>{{ $payment->tran_id }}</td></tr>
            <tr><th>Val ID</th><td>{{ $payment->val_id ?? '—' }}</td></tr>
            <tr><th>Status</th><td>{{ $payment->status }}</td></tr>
            <tr><th>Amount</th><td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td></tr>
            <tr><th>Product</th><td>{{ $payment->product_name ?? $payment->product?->name ?? 'N/A' }}</td></tr>
            <tr><th>Category</th><td>{{ $payment->category?->name ?? '—' }}</td></tr>
            <tr><th>User</th><td>{{ $payment->user?->name ?? 'Guest' }}</td></tr>
            <tr><th>Location</th><td>{{ $payment->location?->name ?? '—' }}</td></tr>
            <tr><th>Billing Cycle</th><td>{{ $payment->billingCycle?->name ?? $payment->billingCycle?->code ?? '—' }}</td></tr>
            <tr><th>Paid At</th><td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? '—' }}</td></tr>
            <tr><th>Created</th><td>{{ $payment->created_at?->format('Y-m-d H:i') }}</td></tr>
            <tr><th>Updated</th><td>{{ $payment->updated_at?->format('Y-m-d H:i') }}</td></tr>
          </tbody>
        </table>
      </div>

      <div class="col-lg-6">

    {{-- CONFIG --}}
@php
    $config = is_array($payment->config)
        ? $payment->config
        : (json_decode($payment->config, true) ?? []);
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Config</label>
    <div class="bg-body-secondary p-3 rounded small mb-0">
        @if(!empty($config))
            <dl class="row mb-0">
                @foreach($config as $key => $value)
                    <dt class="col-sm-4 text-capitalize">
                        {{ str_replace('_', ' ', $key) }}
                    </dt>
                    <dd class="col-sm-8 mb-1">
                        @if(is_array($value))
                            {{-- array hole vitore abar key => value show --}}
                            <ul class="mb-0 ps-3">
                                @foreach($value as $subKey => $subVal)
                                    <li>
                                        <span class="text-muted">
                                            {{ is_int($subKey) ? 'Item '.$loop->iteration : str_replace('_', ' ', $subKey) }}:
                                        </span>
                                        @if(is_array($subVal))
                                            {{ collect($subVal)->implode(', ') }}
                                        @elseif(is_bool($subVal))
                                            {{ $subVal ? 'Yes' : 'No' }}
                                        @else
                                            {{ $subVal }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @elseif(is_bool($value))
                            {{ $value ? 'Yes' : 'No' }}
                        @else
                            {{ $value }}
                        @endif
                    </dd>
                @endforeach
            </dl>
        @else
            <p class="text-muted mb-0">No config data</p>
        @endif
    </div>
</div>


    {{-- LINE ITEMS --}}
    @php
        $lineItems = is_array($payment->line_items)
            ? $payment->line_items
            : (json_decode($payment->line_items, true) ?? []);
    @endphp
    <div class="mb-3">
        <label class="form-label fw-semibold">Line Items</label>
        <div class="bg-body-secondary p-3 rounded small mb-0">
            @if(!empty($lineItems))
                @foreach($lineItems as $index => $item)
                    <div class="mb-2 pb-2 @if(!$loop->last) border-bottom @endif">
                        <div class="fw-semibold mb-1">Item {{ $index + 1 }}</div>
                        @if(is_array($item))
                            <dl class="row mb-0">
                                @foreach($item as $key => $value)
                                    <dt class="col-sm-4 text-capitalize">
                                        {{ str_replace('_', ' ', $key) }}
                                    </dt>
                                    <dd class="col-sm-8 mb-1">
                                        @if(is_array($value))
                                            {{ collect($value)->implode(', ') }}
                                        @elseif(is_bool($value))
                                            {{ $value ? 'Yes' : 'No' }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </dd>
                                @endforeach
                            </dl>
                        @else
                            <p class="mb-0">{{ $item }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">No line items</p>
            @endif
        </div>
    </div>

    {{-- META --}}
    @php
        $meta = is_array($payment->meta)
            ? $payment->meta
            : (json_decode($payment->meta, true) ?? []);
    @endphp
    <div class="mb-3">
        <label class="form-label fw-semibold">Meta</label>
        <div class="bg-body-secondary p-3 rounded small mb-0">
            @if(!empty($meta))
                <dl class="row mb-0">
                    @foreach($meta as $key => $value)
                        <dt class="col-sm-4 text-capitalize">
                            {{ str_replace('_', ' ', $key) }}
                        </dt>
                        <dd class="col-sm-8 mb-1">
                            @if(is_array($value))
                                {{ collect($value)->implode(', ') }}
                            @elseif(is_bool($value))
                                {{ $value ? 'Yes' : 'No' }}
                            @else
                                {{ $value }}
                            @endif
                        </dd>
                    @endforeach
                </dl>
            @else
                <p class="text-muted mb-0">No meta data</p>
            @endif
        </div>
    </div>

    {{-- GATEWAY RESPONSE --}}
    @php
        $gateway = json_decode($payment->gateway_response, true);
    @endphp
    <div>
        <label class="form-label fw-semibold">Gateway Response</label>
        <div class="bg-body-secondary p-3 rounded small mb-0">
            @if(is_array($gateway) && !empty($gateway))
                <dl class="row mb-0">
                    @foreach($gateway as $key => $value)
                        <dt class="col-sm-4 text-capitalize">
                            {{ str_replace('_', ' ', $key) }}
                        </dt>
                        <dd class="col-sm-8 mb-1">
                            @if(is_array($value))
                                {{ collect($value)->implode(', ') }}
                            @elseif(is_bool($value))
                                {{ $value ? 'Yes' : 'No' }}
                            @else
                                {{ $value }}
                            @endif
                        </dd>
                    @endforeach
                </dl>
            @else
                {{-- যদি valid JSON না হয়, তখন raw text দেখাই --}}
                <p class="mb-0">{{ $payment->gateway_response }}</p>
            @endif
        </div>
    </div>

</div>

    </div>
  </div>
</div>
@endsection
