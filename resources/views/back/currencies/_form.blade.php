@csrf
<div class="row g-3">
  <div class="col-md-2">
    <label class="form-label">Code (ISO 4217)</label>
    <input type="text" name="code" maxlength="3" class="form-control" value="{{ old('code',$currency->code) }}" required>
  </div>
  <div class="col-md-5">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$currency->name) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Symbol</label>
    <input type="text" name="symbol" class="form-control" value="{{ old('symbol',$currency->symbol) }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">Decimals</label>
    <input type="number" name="decimal_places" class="form-control" min="0" max="6" value="{{ old('decimal_places',$currency->decimal_places ?? 2) }}">
  </div>
  <div class="col-md-3">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active',$currency->is_active) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active"> Active</label>
    </div>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">Cancel</a>
</div>
