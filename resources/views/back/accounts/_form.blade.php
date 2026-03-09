@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name',$account->name) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">Country</label>
    <select name="country_id" class="form-select">
      <option value="">—</option>
      @foreach($countries as $c)
        <option value="{{ $c->id }}" @selected(old('country_id',$account->country_id) == $c->id)>
          {{ $c->name }} ({{ $c->iso2 }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-2">
    <label class="form-label">Type</label>
    <select name="type" class="form-select">
      <option value="individual" @selected(old('type',$account->type)==='individual')>Individual</option>
      <option value="business"   @selected(old('type',$account->type)==='business')>Business</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="active"    @selected(old('status',$account->status)==='active')>Active</option>
      <option value="suspended" @selected(old('status',$account->status)==='suspended')>Suspended</option>
      <option value="prospect"  @selected(old('status',$account->status)==='prospect')>Prospect</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email',$account->email) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone',$account->phone) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Website</label>
    <input type="text" name="website" class="form-control" value="{{ old('website',$account->website) }}">
  </div>

  <div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" rows="2" class="form-control">{{ old('address',$account->address) }}</textarea>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Active?</label>
    </div>
  </div>

  <div class="col-md-3">
    <label class="form-label">Display Name</label>
    <input type="text" name="display_name" class="form-control" value="{{ old('display_name',$account->display_name) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Tax ID</label>
    <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id',$account->tax_id) }}">
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">Cancel</a>
</div>
