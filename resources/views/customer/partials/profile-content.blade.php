@php
  $user = $user ?? auth()->user();
  $account = $user?->account;
  $billing = $account?->billing_address ?? [];
  $addressLine1 = $billing['line1'] ?? ($billing['line_1'] ?? ($account?->address));
  $addressLine2 = $billing['line2'] ?? ($billing['line_2'] ?? '');
  $city = $billing['city'] ?? '';
  $state = $billing['state'] ?? '';
  $postalCode = $billing['postal_code'] ?? ($billing['zip'] ?? '');
  $countryName = $account?->country?->name ?? 'Bangladesh';
  $phoneNumber = $user?->phone ?? $account?->phone;
  $companyName = $account?->display_name ?? $account?->name;
  $primaryContact = $account?->primaryContact;
  $sectionId = $sectionId ?? 'profile-section';
  $hidden = $hidden ?? false;
@endphp

@pushOnce('styles')
<style>
  .profile-card {
    border: 1px solid #e8ecf2;
    border-radius: 14px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.05);
  }
  .profile-card h4 { font-weight: 700; }
  .profile-section-title {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 700;
  }
  .profile-divider { border-top: 1px dashed #e5e7eb; margin: 24px 0; }
  .btn-soft-primary {
    background: #e7f1ff;
    color: #0d6efd;
    border: none;
  }
</style>
@endPushOnce

<div id="{{ $sectionId }}" class="{{ $hidden ? 'd-none' : '' }}">
  <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
    <div>

      <h4 class="fw-semibold mb-1">Account Details</h4>
      <div class="text-muted">Keep your personal and billing info current.</div>
    </div>
    <div class="d-flex gap-2 mt-2">
      <a href="#" class="btn btn-soft-primary btn-sm" data-section-toggle="dashboard">
        <i class="bi bi-speedometer2 me-1"></i> Back to Dashboard
      </a>
      <span class="pill">Last updated: {{ optional($user?->updated_at)->format('M d, Y') }}</span>
    </div>
  </div>

  <div class="card profile-card p-4 mb-4">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form class="row g-3" method="POST" action="{{ route('user.profile.update') }}">
      @csrf
      <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user?->first_name) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user?->last_name) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Company Name</label>
        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $companyName) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user?->email) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Address 1</label>
        <input type="text" name="address_1" class="form-control" value="{{ old('address_1', $addressLine1) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Address 2</label>
        <input type="text" name="address_2" class="form-control" value="{{ old('address_2', $addressLine2) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $city) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">State/Region</label>
        <input type="text" name="state" class="form-control" value="{{ old('state', $state) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Zip Code</label>
        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $postalCode) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Country</label>
        <select class="form-select">
          <option selected>{{ $countryName }}</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Phone Number</label>
        <div class="input-group">
          <span class="input-group-text">+880</span>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $phoneNumber) }}">
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Language</label>
        <select class="form-select">
          <option selected>{{ $user?->preferred_language ?? 'English' }}</option>
          <option>Bangla</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Payment Method</label>
        <select class="form-select">
          <option selected>Use Default (Set Per Order)</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Default Billing Contact</label>
        <select class="form-select">
          <option selected>{{ $primaryContact?->name ?? 'Use Default Contact (Details Above)' }}</option>
        </select>
      </div>
      <div class="col-12 d-flex align-items-center gap-3 mt-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('user.dashboard') }}" class="text-muted" data-section-toggle="dashboard">Cancel</a>
      </div>
    </form>
  </div>

  {{-- <div class="card profile-card p-4">
    <h5 class="fw-semibold mb-3">Email Preferences</h5>
    <div class="email-pref">
      <label><input type="checkbox" checked> General Emails - All account related emails</label>
      <label><input type="checkbox" checked> Invoice Emails - New invoices, reminders, & overdue notices</label>
      <label><input type="checkbox" checked> Support Emails - Receive a CC of all support ticket communications</label>
      <label><input type="checkbox" checked> Product Emails - Welcome, suspensions & lifecycle notifications</label>
      <label><input type="checkbox" checked> Domain Emails - Registration/Transfer confirmation & renewal notices</label>
      <label><input type="checkbox" checked> Affiliate Emails - Receive affiliate notifications</label>
    </div>
  </div> --}}
</div>
