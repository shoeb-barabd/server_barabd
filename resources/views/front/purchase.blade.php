<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Complete Payment — BARABD</title>
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    :root {
      --primary: #016A4D;
      --primary-light: #e8f5f0;
      --accent: #E63946;
      --bkash: #E2136E;
      --rocket: #8C3494;
      --bank: #1a56db;
      --international: #6366f1;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-600: #4b5563;
      --gray-800: #1f2937;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 50%, #f0f9ff 100%);
      min-height: 100vh;
      color: var(--gray-800);
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary) 0%, #014d38 100%);
      padding: 2rem 0;
      color: white;
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-weight: 800;
      margin: 0;
    }

    .page-header p {
      opacity: 0.9;
      margin: 0.5rem 0 0;
    }

    /* Invoice Card */
    .invoice-card {
      background: white;
      border-radius: 16px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      margin-bottom: 2rem;
    }

    .invoice-header {
      background: linear-gradient(135deg, var(--primary) 0%, #014d38 100%);
      color: white;
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .invoice-header h3 {
      margin: 0;
      font-weight: 700;
    }

    .invoice-status {
      background: rgba(255, 255, 255, 0.2);
      padding: 0.4rem 1rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .invoice-body {
      padding: 2rem;
    }

    .invoice-row {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--gray-100);
    }

    .invoice-row:last-child {
      border-bottom: none;
    }

    .invoice-label {
      color: var(--gray-600);
      font-size: 0.95rem;
    }

    .invoice-value {
      font-weight: 600;
    }

    .invoice-total {
      background: var(--primary-light);
      margin: 1rem -2rem -2rem;
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .invoice-total .label {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--primary);
    }

    .invoice-total .amount {
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--primary);
    }

    /* New Invoice Styles */
    .invoice-section {
      background: var(--gray-50);
      border-radius: 12px;
      padding: 1.25rem;
    }

    .invoice-section-title {
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }

    .invoice-detail {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 0;
      border-bottom: 1px dashed var(--gray-200);
    }

    .invoice-detail:last-child {
      border-bottom: none;
    }

    .invoice-detail .label {
      color: var(--gray-600);
      font-size: 0.9rem;
    }

    .invoice-detail .value {
      font-weight: 600;
      font-size: 0.9rem;
      text-align: right;
    }

    .invoice-address {
      color: var(--gray-600);
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .invoice-table {
      font-size: 0.9rem;
    }

    .invoice-table thead th {
      background: var(--gray-50);
      color: var(--gray-600);
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid var(--gray-200);
      padding: 0.75rem;
    }

    .invoice-table tbody td {
      padding: 1rem 0.75rem;
      border-bottom: 1px solid var(--gray-100);
      vertical-align: middle;
    }

    /* Section Title */
    .section-title {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
      font-weight: 700;
      font-size: 1.25rem;
    }

    .section-title .icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }

    .section-title.mobile .icon {
      background: linear-gradient(135deg, var(--bkash), var(--rocket));
      color: white;
    }

    .section-title.bank .icon {
      background: linear-gradient(135deg, var(--bank), #1e40af);
      color: white;
    }

    .section-title.international .icon {
      background: linear-gradient(135deg, var(--international), #4f46e5);
      color: white;
    }

    /* Payment Cards */
    .payment-grid {
      display: grid;
      gap: 1.5rem;
    }

    @media (min-width: 768px) {
      .payment-grid.two-col {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (min-width: 992px) {
      .payment-grid.three-col {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .payment-card {
      background: white;
      border-radius: 16px;
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .payment-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }

    .payment-card-header {
      padding: 1.25rem;
      color: white;
      text-align: center;
    }

    .payment-card-header.bkash {
      background: linear-gradient(135deg, var(--bkash), #c4105d);
    }

    .payment-card-header.rocket {
      background: linear-gradient(135deg, var(--rocket), #6b2574);
    }

    .payment-card-header.bank {
      background: linear-gradient(135deg, var(--bank), #1e40af);
    }

    .payment-card-header h5 {
      margin: 0;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .payment-card-header .number {
      font-size: 1.5rem;
      font-weight: 800;
      margin-top: 0.25rem;
      letter-spacing: 1px;
    }

    .payment-card-body {
      padding: 1.5rem;
      text-align: center;
    }

    .qr-code {
      width: 220px;
      height: auto;
      margin: 0 auto 1rem;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      background: white;
    }

    .qr-code img {
      width: 100%;
      height: auto;
      display: block;
    }

    .scan-text {
      font-size: 0.85rem;
      color: var(--gray-600);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .scan-text i {
      color: var(--primary);
    }

    /* Bank Card */
    .bank-card {
      background: white;
      border-radius: 16px;
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: transform 0.2s;
    }

    .bank-card:hover {
      transform: translateY(-2px);
    }

    .bank-card-header {
      background: linear-gradient(135deg, var(--bank), #1e40af);
      color: white;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .bank-card-header .bank-logo {
      width: 48px;
      height: 48px;
      background: white;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      color: var(--bank);
      font-size: 0.7rem;
      overflow: hidden;
      padding: 6px;
    }

    .bank-card-header .bank-logo img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .bank-card-header h5 {
      margin: 0;
      font-weight: 700;
    }

    .bank-card-header .branch {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .bank-card-body {
      padding: 1.5rem;
    }

    .bank-detail {
      display: flex;
      justify-content: space-between;
      padding: 0.6rem 0;
      border-bottom: 1px dashed var(--gray-200);
    }

    .bank-detail:last-child {
      border-bottom: none;
    }

    .bank-detail .label {
      color: var(--gray-600);
      font-size: 0.9rem;
    }

    .bank-detail .value {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .copy-btn {
      background: var(--gray-100);
      border: none;
      padding: 0.3rem 0.6rem;
      border-radius: 6px;
      font-size: 0.75rem;
      cursor: pointer;
      transition: background 0.2s;
    }

    .copy-btn:hover {
      background: var(--gray-200);
    }

    /* International Payment */
    .intl-card {
      background: white;
      border-radius: 16px;
      box-shadow: var(--shadow);
      padding: 2rem;
    }

    .intl-methods {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: center;
      margin-bottom: 1.5rem;
    }

    .intl-method {
      background: var(--gray-50);
      border: 2px solid var(--gray-200);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.2s;
      cursor: pointer;
    }

    .intl-method:hover {
      border-color: var(--international);
      background: #eef2ff;
    }

    .intl-method img {
      height: 28px;
    }

    .intl-method span {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .intl-note {
      background: linear-gradient(135deg, #fef3c7, #fde68a);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      font-size: 0.9rem;
    }

    .intl-note i {
      color: #d97706;
      font-size: 1.25rem;
      margin-top: 0.1rem;
    }

    /* Card Payment Form */
    .intl-method.card-toggle {
      cursor: pointer;
      transition: all 0.2s;
    }

    .intl-method.card-toggle:hover,
    .intl-method.card-toggle.active {
      border-color: var(--international);
      background: #eef2ff;
      transform: translateY(-2px);
    }

    .card-payment-form {
      background: white;
      border: 2px solid var(--gray-200);
      border-radius: 12px;
      margin-top: 1.5rem;
      overflow: hidden;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        max-height: 0;
      }

      to {
        opacity: 1;
        max-height: 500px;
      }
    }

    .card-form-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--gray-100);
      background: var(--gray-50);
    }

    .card-form-header h5 {
      margin: 0;
      font-weight: 600;
      font-size: 1rem;
    }

    .card-icons {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-icons img {
      height: 20px;
    }

    .btn-close-card {
      background: none;
      border: none;
      color: var(--gray-600);
      cursor: pointer;
      padding: 0.25rem;
      margin-left: 0.5rem;
    }

    .btn-close-card:hover {
      color: var(--gray-800);
    }

    .card-form {
      padding: 1.5rem;
    }

    .card-form .form-group {
      margin-bottom: 1rem;
    }

    .card-form label {
      display: block;
      font-size: 0.85rem;
      color: var(--gray-600);
      margin-bottom: 0.4rem;
      font-weight: 500;
    }

    .card-form .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--gray-200);
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .card-form .form-control:focus {
      outline: none;
      border-color: var(--international);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .card-form .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .btn-submit-card {
      width: 100%;
      padding: 0.875rem;
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      margin-top: 0.5rem;
    }

    .btn-submit-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    /* Help Box */
    .help-box {
      background: white;
      border-radius: 16px;
      box-shadow: var(--shadow);
      padding: 2rem;
      text-align: center;
      margin-top: 2rem;
    }

    .help-box h4 {
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .help-box p {
      color: var(--gray-600);
      margin-bottom: 1rem;
    }

    .help-box .contact-btns {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .help-box .btn {
      border-radius: 10px;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-in {
      animation: fadeInUp 0.5s ease forwards;
    }

    .delay-1 {
      animation-delay: 0.1s;
    }

    .delay-2 {
      animation-delay: 0.2s;
    }

    .delay-3 {
      animation-delay: 0.3s;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <div class="page-header">
    <div class="container">
      <h1><i class="bi bi-cash-stack me-2"></i>Complete Your Payment</h1>
      <p>Choose your preferred payment method and complete the transaction</p>
    </div>
  </div>

  <div class="container pb-5">
    @php $invoice = session('invoice', []); @endphp

    {{-- Show validation errors and flash messages --}}
    @if ($errors->any())
      <div class="alert alert-danger mb-4" style="border-radius: 12px;">
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Validation Error:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger mb-4" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
      </div>
    @endif

    <!-- Invoice Section -->
    <div class="invoice-card animate-in">
      <div class="invoice-header">
        <div>
          <h3 class="mb-1"><i class="bi bi-file-earmark-ruled-fill me-2"></i>Order Invoice</h3>
          <small class="opacity-75">Order #{{ $invoice['order_id'] ?? 'N/A' }} •
            {{ $invoice['date'] ?? now()->format('d M, Y') }}</small>
        </div>
        <span class="invoice-status"><i class="bi bi-clock me-1"></i>Awaiting Payment</span>
      </div>
      <div class="invoice-body">
        <div class="row">
          <!-- Left Column - Customer Info -->
          <div class="col-md-6 mb-4">
            <div class="invoice-section">
              <h6 class="invoice-section-title"><i class="bi bi-person me-2"></i>Customer Information</h6>
              <div class="invoice-detail">
                <span class="label">Name</span>
                <span class="value">{{ ($invoice['first_name'] ?? '') . ' ' . ($invoice['last_name'] ?? '') }}</span>
              </div>
              <div class="invoice-detail">
                <span class="label">Email</span>
                <span class="value">{{ $invoice['email'] ?? 'N/A' }}</span>
              </div>
              <div class="invoice-detail">
                <span class="label">Phone</span>
                <span class="value">{{ $invoice['phone'] ?? 'N/A' }}</span>
              </div>
            </div>
          </div>

          <!-- Right Column - Billing Address -->
          <div class="col-md-6 mb-4">
            <div class="invoice-section">
              <h6 class="invoice-section-title"><i class="bi bi-geo-alt me-2"></i>Billing Address</h6>
              <div class="invoice-address">
                @if($invoice['street_address'] ?? null)
                  <p class="mb-1">{{ $invoice['street_address'] }}</p>
                @endif
                @if($invoice['street_address_2'] ?? null)
                  <p class="mb-1">{{ $invoice['street_address_2'] }}</p>
                @endif
                <p class="mb-0">
                  {{ $invoice['city'] ?? '' }}{{ ($invoice['state'] ?? null) ? ', ' . $invoice['state'] : '' }}{{ ($invoice['postal_code'] ?? null) ? ' - ' . $invoice['postal_code'] : '' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-3">

        <!-- Order Details -->
        <div class="invoice-section mb-4">
          <h6 class="invoice-section-title"><i class="bi bi-box me-2"></i>Order Details</h6>
          <div class="table-responsive">
            <table class="table invoice-table mb-0">
              <thead>
                <tr>
                  <th>Description</th>
                  <th class="text-center">Location</th>
                  <th class="text-center">Billing Cycle</th>
                  <th class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <strong>{{ $invoice['product_name'] ?? 'Product' }}</strong>
                    @if($invoice['category'] ?? null)
                      <br><small class="text-muted">{{ $invoice['category'] }}</small>
                    @endif
                    @if($invoice['domain'] ?? null)
                      <br><small class="text-primary"><i class="bi bi-globe me-1"></i>{{ $invoice['domain'] }}</small>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-light text-dark">{{ $invoice['location'] ?? 'N/A' }}</span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-primary">{{ $invoice['billing_cycle'] ?? 'N/A' }}</span>
                  </td>
                  <td class="text-end align-middle fw-bold">
                    {{ $invoice['currency'] ?? 'BDT' }} {{ number_format($invoice['amount'] ?? 0, 2) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Total -->
        <div class="invoice-total">
          <div>
            <span class="label">Total Amount Due</span>
            <small class="d-block text-muted mt-1">Please pay using any method below</small>
          </div>
          <span class="amount">{{ $invoice['currency'] ?? 'BDT' }}
            {{ number_format($invoice['amount'] ?? 0, 2) }}</span>
        </div>
      </div>
    </div>

    <!-- Payment Banner -->
    <div class="animate-in delay-1 text-center" style="margin-top: 3rem; margin-bottom: 3rem;">
      <img src="{{ asset('front/images/payment.png') }}" alt="Payment Methods" style="max-width: 100%; height: auto; border-radius: 12px;">
    </div>

    <!-- Online Payment -->
    <div class="animate-in delay-1 text-center" style="margin-top: 2.5rem; margin-bottom: 3rem;">

      {{-- Hidden data for AJAX --}}
      <div id="sslPayData"
        data-product-id="{{ $invoice['product_id'] ?? '' }}"
        data-location-id="{{ $invoice['location_id'] ?? '' }}"
        data-billing-cycle-id="{{ $invoice['billing_cycle_id'] ?? '' }}"
        data-amount="{{ $invoice['amount'] ?? '' }}"
        data-currency="{{ $invoice['currency'] ?? 'BDT' }}"
        data-plan-title="{{ $invoice['product_name'] ?? '' }}"
        data-payment-detail-id="{{ $invoice['payment_detail_id'] ?? '' }}"
        data-url="{{ route('ssl.pay.ajax') }}">
      </div>

      <button type="button" id="sslcommerzPayBtn" onclick="openSSLCommerzPopup()"
        style="
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 0.75rem;
          padding: 1.5rem 4rem;
          background: linear-gradient(135deg, #0D6EFD 0%, #0B5ED7 100%);
          color: white;
          border: none;
          border-radius: 14px;
          font-size: 1.75rem;
          font-weight: 800;
          cursor: pointer;
          transition: transform 0.2s, box-shadow 0.2s;
          box-shadow: 0 6px 20px rgba(13, 110, 253, 0.35);
          text-decoration: none;
          letter-spacing: 0.5px;
        "
        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(13, 110, 253, 0.45)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(13, 110, 253, 0.35)';">
        <i class="bi bi-lightning-charge-fill" style="font-size: 1.5rem;"></i>
        Pay Now
        <i class="bi bi-arrow-right-circle-fill" style="font-size: 1.25rem;"></i>
      </button>

      {{-- Loading spinner --}}
      <div id="sslLoading" style="display:none; margin-top: 1.5rem;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2" style="color: var(--gray-600);">Preparing payment gateway...</p>
      </div>

      {{-- Error message --}}
      <div id="sslError" style="display:none; margin-top: 1rem;">
        <div class="alert alert-danger" style="border-radius: 10px;">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <span id="sslErrorText"></span>
        </div>
      </div>

      {{-- Popup status indicator --}}
      <div id="sslPopupStatus" style="display: none; margin-top: 1.5rem;">
        <div style="border: 2px solid #0D6EFD; border-radius: 12px; padding: 2rem; background: #f0f7ff; text-align: center;">
          <div class="spinner-border text-primary mb-3" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
          <h6 style="color: #0B5ED7; font-weight: 700;"><i class="bi bi-window-stack me-2"></i>Payment window is open</h6>
          <p style="color: var(--gray-600); margin-bottom: 1rem; font-size: 0.9rem;">
            Complete your payment in the popup window. Do not close this page.
          </p>
          <button type="button" onclick="focusSSLPopup()" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; margin-right: 0.5rem;">
            <i class="bi bi-box-arrow-up-right me-1"></i> Focus Payment Window
          </button>
          <button type="button" onclick="cancelSSLPopup()" class="btn btn-outline-danger btn-sm" style="border-radius: 8px;">
            <i class="bi bi-x-lg me-1"></i> Cancel Payment
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Banking Section -->
    <div class="mb-5 animate-in delay-1" style="margin-top: 2.5rem;">
      <div class="section-title mobile">
        <span class="icon"><i class="bi bi-phone"></i></span>
        <span>Mobile Banking (Bangladesh)</span>
      </div>
      <div class="payment-grid two-col">
        <!-- bKash -->
        <div class="payment-card">
          <div class="payment-card-header bkash">
            <h5><i class="bi bi-wallet2 me-2"></i>bKash Merchant</h5>
            <div class="number">01901883200</div>
          </div>
          <div class="payment-card-body">
            <div class="qr-code">
              <img src="{{ asset('front/images/bkash-qr.png') }}" alt="bKash QR Code">
            </div>
            <p class="scan-text"><i class="bi bi-qr-code-scan"></i>Scan QR code to pay instantly</p>
          </div>
        </div>

        <!-- Rocket -->
        <div class="payment-card">
          <div class="payment-card-header rocket">
            <h5><i class="bi bi-wallet2 me-2"></i>Rocket Send Money</h5>
            <div class="number">01975363707</div>
          </div>
          <div class="payment-card-body">
            <div class="qr-code">
              <img src="{{ asset('front/images/rocket-qr.png') }}" alt="Rocket QR Code">
            </div>
            <p class="scan-text"><i class="bi bi-qr-code-scan"></i>Scan QR code to pay instantly</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bank Transfer Section -->
    <div class="mb-5 animate-in delay-2">
      <div class="section-title bank">
        <span class="icon"><i class="bi bi-bank"></i></span>
        <span>Bank Transfer (Bangladesh)</span>
      </div>
      <div class="payment-grid three-col">
        <!-- City Bank -->
        <div class="bank-card">
          <div class="bank-card-header">
            <div class="bank-logo">
              <img src="{{ asset('front/images/citybank.png') }}" alt="City Bank">
            </div>
            <div>
              <h5>City Bank PLC</h5>
              <span class="branch">City Islamic Branch</span>
            </div>
          </div>
          <div class="bank-card-body">
            <div class="bank-detail">
              <span class="label">Account Name</span>
              <span class="value">Baktier Ahmed Rony and Associates Ltd.</span>
            </div>
            <div class="bank-detail">
              <span class="label">Account No</span>
              <span class="value">1781360002199 <button class="copy-btn" onclick="copyText('1781360002199')"><i
                    class="bi bi-copy"></i></button></span>
            </div>
            <div class="bank-detail">
              <span class="label">Routing No</span>
              <span class="value">225272868</span>
            </div>
          </div>
        </div>

        <!-- Prime Bank -->
        <div class="bank-card">
          <div class="bank-card-header">
            <div class="bank-logo">
              <img src="{{ asset('front/images/primebank.png') }}" alt="Prime Bank">
            </div>
            <div>
              <h5>Prime Bank PLC</h5>
              <span class="branch">Bashabo Branch</span>
            </div>
          </div>
          <div class="bank-card-body">
            <div class="bank-detail">
              <span class="label">Account Name</span>
              <span class="value">Baktier Ahmed Rony and Associates Ltd.</span>
            </div>
            <div class="bank-detail">
              <span class="label">Account No</span>
              <span class="value">2198134015882 <button class="copy-btn" onclick="copyText('2198134015882')"><i
                    class="bi bi-copy"></i></button></span>
            </div>
            <div class="bank-detail">
              <span class="label">Routing No</span>
              <span class="value">170270973</span>
            </div>
          </div>
        </div>

        <!-- Dutch Bangla Bank -->
        <div class="bank-card">
          <div class="bank-card-header">
            <div class="bank-logo">
              <img src="{{ asset('front/images/dutchbangla.png') }}" alt="DBBL">
            </div>
            <div>
              <h5>Dutch Bangla Bank PLC</h5>
              <span class="branch">Bijoy Nagar Branch</span>
            </div>
          </div>
          <div class="bank-card-body">
            <div class="bank-detail">
              <span class="label">Account Name</span>
              <span class="value">Baktier Ahmed Rony and Associates Ltd.</span>
            </div>
            <div class="bank-detail">
              <span class="label">Account No</span>
              <span class="value">1911100028832 <button class="copy-btn" onclick="copyText('1911100028832')"><i
                    class="bi bi-copy"></i></button></span>
            </div>
            <div class="bank-detail">
              <span class="label">Routing No</span>
              <span class="value">090271094</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Help Section -->
    <div class="help-box animate-in delay-3">
      <h4><i class="bi bi-headset me-2"></i>Need Help?</h4>
      <p>Our support team is available 24/7 to assist you with your payment</p>
      <div class="contact-btns">
        <a href="https://wa.me/8801975363707" class="btn btn-success" target="_blank">
          <i class="bi bi-whatsapp me-2"></i>WhatsApp
        </a>
        <a href="https://mail.google.com/mail/?view=cm&to=barabdinfo@gmail.com&su=Payment%20Support%20Request" class="btn btn-outline-primary" target="_blank">
          <i class="bi bi-envelope me-2"></i>Email Support
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function copyText(text) {
      navigator.clipboard.writeText(text).then(() => {
        alert('Copied: ' + text);
      });
    }

    // Close Card Payment Form
    function closeCardForm() {
      const form = document.getElementById('cardPaymentForm');
      const toggles = document.querySelectorAll('.card-toggle');
      form.style.display = 'none';
      toggles.forEach(t => t.classList.remove('active'));
    }

    // Toggle Card Payment Form
    function toggleCardForm(element) {
      const form = document.getElementById('cardPaymentForm');
      const toggles = document.querySelectorAll('.card-toggle');

      if (form.style.display === 'none') {
        form.style.display = 'block';
        // Only activate the clicked button
        toggles.forEach(t => t.classList.remove('active'));
        element.classList.add('active');
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        // If clicking the same button, close form
        if (element.classList.contains('active')) {
          form.style.display = 'none';
          toggles.forEach(t => t.classList.remove('active'));
        } else {
          // Switch active to clicked button
          toggles.forEach(t => t.classList.remove('active'));
          element.classList.add('active');
        }
      }
    }

    // Format card number with spaces
    function formatCardNumber(input) {
      let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
      input.value = formatted;
    }

    // Format expiry date MM/YY
    function formatExpiry(input) {
      let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
      if (value.length >= 2) {
        value = value.substring(0, 2) + ' / ' + value.substring(2, 4);
      }
      input.value = value;
    }

    // Handle card form submit
    function submitCardPayment(event) {
      event.preventDefault();
      alert('Card payment submitted! Our team will process your payment and contact you shortly.');
      // In production: send data to server
    }

    // SSLCommerz Popup Checkout
    let sslPopupWindow = null;
    let sslPopupChecker = null;

    function openSSLCommerzPopup() {
      const data = document.getElementById('sslPayData');
      const btn = document.getElementById('sslcommerzPayBtn');
      const loading = document.getElementById('sslLoading');
      const errorDiv = document.getElementById('sslError');
      const statusDiv = document.getElementById('sslPopupStatus');

      errorDiv.style.display = 'none';
      statusDiv.style.display = 'none';
      btn.style.display = 'none';
      loading.style.display = 'block';

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch(data.dataset.url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          product_id: data.dataset.productId,
          location_id: data.dataset.locationId,
          billing_cycle_id: data.dataset.billingCycleId,
          amount: data.dataset.amount,
          currency: data.dataset.currency,
          plan_title: data.dataset.planTitle,
          payment_detail_id: data.dataset.paymentDetailId,
        }),
      })
      .then(res => res.json())
      .then(result => {
        loading.style.display = 'none';
        if (result.success && result.gateway_url) {
          const w = 500, h = 700;
          const left = (screen.width - w) / 2;
          const top = (screen.height - h) / 2;
          sslPopupWindow = window.open(
            result.gateway_url,
            'SSLCommerzPayment',
            'width=' + w + ',height=' + h + ',top=' + top + ',left=' + left + ',scrollbars=yes,resizable=yes'
          );

          if (!sslPopupWindow || sslPopupWindow.closed) {
            btn.style.display = 'inline-flex';
            document.getElementById('sslErrorText').innerHTML = 'Popup blocked by your browser. Please allow popups for this site, or <a href="' + result.gateway_url + '" target="_blank" style="color:#0D6EFD;text-decoration:underline;">click here to pay in a new tab</a>.';
            errorDiv.style.display = 'block';
            return;
          }

          statusDiv.style.display = 'block';
          statusDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

          sslPopupChecker = setInterval(function() {
            if (sslPopupWindow && sslPopupWindow.closed) {
              clearInterval(sslPopupChecker);
              sslPopupChecker = null;
              sslPopupWindow = null;
              statusDiv.style.display = 'none';
              btn.style.display = 'inline-flex';
            }
          }, 500);
        } else {
          btn.style.display = 'inline-flex';
          document.getElementById('sslErrorText').textContent = result.message || 'Failed to initialize payment gateway.';
          errorDiv.style.display = 'block';
        }
      })
      .catch(function(err) {
        loading.style.display = 'none';
        btn.style.display = 'inline-flex';
        document.getElementById('sslErrorText').textContent = 'Network error. Please try again.';
        errorDiv.style.display = 'block';
        console.error('SSLCommerz error:', err);
      });
    }

    function focusSSLPopup() {
      if (sslPopupWindow && !sslPopupWindow.closed) {
        sslPopupWindow.focus();
      }
    }

    function cancelSSLPopup() {
      if (sslPopupWindow && !sslPopupWindow.closed) {
        sslPopupWindow.close();
      }
      if (sslPopupChecker) {
        clearInterval(sslPopupChecker);
        sslPopupChecker = null;
      }
      sslPopupWindow = null;
      document.getElementById('sslPopupStatus').style.display = 'none';
      document.getElementById('sslcommerzPayBtn').style.display = 'inline-flex';
    }
  </script>
</body>

</html>
