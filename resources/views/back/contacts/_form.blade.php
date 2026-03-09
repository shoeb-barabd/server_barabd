@csrf
<div class="row g-3">

  <div class="col-md-4">
    <label class="form-label">Account</label>
    <select name="account_id" class="form-select" required>
      <option value="">-- select --</option>
      @foreach($accounts as $a)
        <option value="{{ $a->id }}" @selected(old('account_id', $contact->account_id) == $a->id)>
          {{ $a->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">First name</label>
    <input type="text" name="first_name" class="form-control" required
           value="{{ old('first_name', $contact->first_name) }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">Last name</label>
    <input type="text" name="last_name" class="form-control"
           value="{{ old('last_name', $contact->last_name) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required
           value="{{ old('email', $contact->email) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $contact->phone) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">Title / Role</label>
    <input type="text" name="designation" class="form-control"
           value="{{ old('designation', $contact->designation) }}">
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1"
             {{ old('is_primary', $contact->is_primary ?? false) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_primary">Primary</label>
    </div>
  </div>

  <div class="col-md-9 d-flex align-items-end">
    <div class="d-flex gap-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="nf_billing"
               name="notify_flags[billing]" value="1"
               {{ old('notify_flags.billing', data_get($contact->notify_flags,'billing', false)) ? 'checked' : '' }}>
        <label class="form-check-label" for="nf_billing">Notify for Billing</label>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="nf_support"
               name="notify_flags[support]" value="1"
               {{ old('notify_flags.support', data_get($contact->notify_flags,'support', false)) ? 'checked' : '' }}>
        <label class="form-check-label" for="nf_support">Notify for Support</label>
      </div>
    </div>
  </div>

</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Cancel</a>
</div>
