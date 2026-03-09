<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment {{ $payment->tran_id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 18px 22px 24px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }

        .muted { color: #666; font-size: 11px; }

        .section { margin-top: 16px; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
        }

        .no-border td,
        .no-border th {
            border: none;
            padding: 0;
        }

        .text-right { text-align: right; }
        .text-muted { color: #6b7280; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-success {
            background: #ecfdf3;
            color: #166534;
        }
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .invoice-title {
            font-size: 20px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 14px 0 10px;
        }
    </style>
</head>
<body>
<div class="invoice-wrapper">

    {{-- HEADER WITH LOGO --}}
    <table class="no-border">
        <tr>
            <td>
                <img
                    src="{{ public_path('companyLogo.jpeg') }}"
                    alt="Company Logo"
                    style="height: 45px; margin-bottom: 6px;"
                >
                <div class="text-muted" style="font-size: 11px;">
                    Baktier Ahmed Rony & Associates Ltd.<br>
                    {{ config('app.url') }}
                </div>
            </td>
            <td class="text-right">
                <div class="invoice-title">PAYMENT RECEIPT</div>
                <div class="muted">
                    Generated {{ now()->format('Y-m-d H:i') }}
                </div>
                <div style="margin-top: 6px;">
                    <strong>Transaction ID:</strong> {{ $payment->tran_id }}<br>
                    <strong>Reference:</strong> {{ $payment->val_id ?? 'N/A' }}
                </div>

                @php
                    $status = strtolower($payment->status ?? '');
                    $badgeClass = 'badge-pending';
                    if (in_array($status, ['paid', 'success', 'completed'])) {
                        $badgeClass = 'badge-success';
                    } elseif (in_array($status, ['failed', 'canceled', 'cancelled'])) {
                        $badgeClass = 'badge-failed';
                    }
                @endphp

                <div style="margin-top: 8px;">
                    <span class="badge {{ $badgeClass }}">
                        {{ strtoupper($payment->status) }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- PAYMENT SUMMARY SECTION --}}
    <div class="section">
        <h1 style="margin-bottom: 8px;">Payment Details</h1>
        <table>
            <tbody>
            <tr>
                <th style="width: 180px;">Amount</th>
                <td>
                    <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong>
                </td>
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
                <th>User</th>
                <td>{{ $payment->user?->name ?? 'Guest' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $payment->location?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Billing Cycle</th>
                <td>{{ $payment->billingCycle?->name ?? $payment->billingCycle?->code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Paid At</th>
                <td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Created</th>
                <td>{{ $payment->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <th>Updated</th>
                <td>{{ $payment->updated_at?->format('Y-m-d H:i') }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="section">
        <div class="muted" style="font-size: 10px; text-align: center; margin-top: 12px;">
            This is a system generated receipt. No signature is required.<br>
            For billing queries, contact: support@yourdomain.com
        </div>
    </div>
</div>
</body>
</html>
