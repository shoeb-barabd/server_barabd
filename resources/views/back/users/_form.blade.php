@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">First name</label>
    <input type="text" name="first_name" class="form-control" required value="{{ old('first_name', $user->first_name) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Last name</label>
    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">{{ $user->exists ? 'New Password (leave blank to keep existing)' : 'Password' }}</label>
    <input type="password" name="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
  </div>
  <div class="col-md-4">
    <label class="form-label">Confirm Password</label>
    <input type="password" name="password_confirmation" class="form-control" {{ $user->exists ? '' : 'required' }}>
  </div>

  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
        <input type="hidden" name="mark_verified" value="0">
        <input class="form-check-input" type="checkbox" id="mark_verified" name="mark_verified" value="1"
            {{ old('mark_verified', $user->email_verified_at ? 1 : 0) ? 'checked' : '' }}>
        <label class="form-check-label" for="mark_verified">Mark email as verified</label>
    </div>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</div>
