@php
  $sectionId = $sectionId ?? 'password-section';
  $hidden = $hidden ?? false;
@endphp

@pushOnce('styles')
<style>
  .password-card {
    border: 1px solid #e8ecf2;
    border-radius: 14px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.05);
  }
  .form-note {
    font-size: 0.9rem;
    color: #6c757d;
  }
</style>
@endPushOnce

<div id="{{ $sectionId }}" class="{{ $hidden ? 'd-none' : '' }}">
  <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
    <div>
      <h4 class="fw-semibold mb-1">Change Password</h4>
      <div class="text-muted">Use a strong password to keep your account secure.</div>
    </div>
    <div class="d-flex gap-2 mt-2">
      <a href="#" class="btn btn-soft-primary btn-sm" data-section-toggle="dashboard">
        <i class="bi bi-speedometer2 me-1"></i> Back to Dashboard
      </a>
    </div>
  </div>

  <div class="card password-card p-4">
    @if(session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    @php
      $passwordErrors = collect($errors->get('current_password'))
        ->merge($errors->get('password'))
        ->merge($errors->get('password_confirmation'));
    @endphp
    @if($passwordErrors->isNotEmpty())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0 ps-3">
          @foreach($passwordErrors as $message)
            <li>{{ $message }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('user.password.update') }}" class="row g-3">
      @csrf
      <div class="col-12">
        <label class="form-label">Current Password</label>
        <input
          type="password"
          name="current_password"
          class="form-control"
          required
          autocomplete="current-password"
          placeholder="Enter current password">
      </div>
      <div class="col-md-6">
        <label class="form-label">New Password</label>
        <input
          type="password"
          name="password"
          class="form-control"
          required
          minlength="8"
          autocomplete="new-password"
          placeholder="At least 8 characters">
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirm New Password</label>
        <input
          type="password"
          name="password_confirmation"
          class="form-control"
          required
          minlength="8"
          autocomplete="new-password"
          placeholder="Retype new password">
      </div>
      <div class="col-12 form-note">
        Password must be at least 8 characters and different from your current password.
      </div>
      <div class="col-12 d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Update Password</button>
        <a href="#" class="text-muted" data-section-toggle="dashboard">Cancel</a>
      </div>
    </form>
  </div>
</div>
