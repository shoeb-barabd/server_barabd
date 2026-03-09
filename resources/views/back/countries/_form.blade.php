@csrf
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $country->name) }}" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">ISO2 <span class="text-danger">*</span></label>
    <input type="text" name="iso2" class="form-control" value="{{ old('iso2', $country->iso2) }}" maxlength="2" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">ISO3</label>
    <input type="text" name="iso3" class="form-control" value="{{ old('iso3', $country->iso3) }}" maxlength="3">
  </div>
  <div class="col-md-2">
    <label class="form-label">Phone code</label>
    <input type="text" name="phone_code" class="form-control" value="{{ old('phone_code', $country->phone_code) }}">
  </div>
  <div class="col-md-3">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="is_active" value="1"
             id="is_active" {{ old('is_active', $country->is_active) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active"> Active</label>
    </div>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">Cancel</a>
</div>
