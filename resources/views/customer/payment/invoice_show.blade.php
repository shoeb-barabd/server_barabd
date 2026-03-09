<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $payment->tran_id }} | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background: #f8f9fa; }

        .invoice-card {
            max-width: 820px;
            margin: 32px auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 6px 18px rgba(0,0,0,.06);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-header { padding: 18px 24px; border-bottom: 1px solid #e5e7eb; background: #ffffff; }
        .invoice-body { padding: 24px; }
        .brand-logo { height: 45px; width: auto; display: block; }

        table th {
            width: 180px;
            color: #495057;
            background: #f8f9fa;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            .invoice-header { padding: 16px; }
            .invoice-body { padding: 16px; }
            .invoice-header .btn-group-sm { width: 100%; }
        }
    </style>
</head>
<body>

@php
    $status = strtolower($payment->status ?? '');
    $badgeClass = 'bg-secondary';

    if (in_array($status, ['paid','success','completed'])) {
        $badgeClass = 'bg-success';
    } elseif (in_array($status, ['pending','processing'])) {
        $badgeClass = 'bg-warning text-dark';
    } elseif (in_array($status, ['failed','canceled','cancelled'])) {
        $badgeClass = 'bg-danger';
    }

    $backUrl = url()->previous();
    if (! $backUrl || $backUrl === url()->current()) {
        $backUrl = route('user.dashboard');
    }
@endphp

<div class="invoice-card">
    <div class="invoice-header d-flex flex-wrap align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('companyLogo.jpeg') }}" alt="Company Logo" class="brand-logo">
            <div>
                <div class="text-muted" style="font-size: 11px;">
                    Baktier Ahmed Rony & Associates Ltd.<br>
                    {{ config('app.url') }}
                </div>
                <small class="text-muted">
                    Generated: {{ now()->format('Y-m-d H:i') }}
                </small>
            </div>
        </div>

        <div class="ms-auto d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ $backUrl }}">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
            <a class="btn btn-primary btn-sm"
               href="{{ route('user.payments.invoice', ['payment' => $payment->id, 'download' => 1]) }}">
                <i class="bi bi-download"></i>
                Download PDF
            </a>
        </div>
    </div>

    <div class="invoice-body">
        <table class="table table-bordered mb-0 align-middle">
            <tbody>
            <tr>
                <th>Invoice #</th>
                <td>{{ $payment->tran_id }}</td>
            </tr>
            <tr>
                <th>User</th>
                <td>{{ $user->name }} ({{ $user->email }})</td>
            </tr>
            <tr>
                <th>Product</th>
                <td>{{ $payment->product_name ?? $payment->product?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Category</th>
                <td>{{ $payment->category?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Billing Cycle</th>
                <td>{{ $payment->billingCycle?->name ?? $payment->billingCycle?->code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $payment->location?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge {{ $badgeClass }} px-3 py-2">
                        {{ strtoupper($payment->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>
                    <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong>
                </td>
            </tr>
            <tr>
                <th>Paid At</th>
                <td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Created</th>
                <td>{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
