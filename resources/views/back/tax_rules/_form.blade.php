@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Country</label>
    <select name="country_id" class="form-select" required>
      <option value="">-- select --</option>
      @foreach($countries as $c)
        <option value="{{ $c->id }}" @selected(old('country_id', $rule->country_id) == $c->id)>
          {{ $c->name }} ({{ $c->iso2 }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Tax Name</label>
    <input type="text" name="tax_name" class="form-control"
           value="{{ old('tax_name', $rule->tax_name ?? 'VAT') }}" required>
  </div>

  <div class="col-md-2">
    <label class="form-label">Rate %</label>
    <input type="number" step="0.01" min="0" max="99.99" name="rate_percent" class="form-control"
           value="{{ old('rate_percent', $rule->rate_percent) }}" required>
  </div>

  <div class="col-md-2 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="is_inclusive" name="is_inclusive" value="1"
             {{ old('is_inclusive', $rule->is_inclusive) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_inclusive">Inclusive pricing</label>
    </div>
  </div>

  <div class="col-md-3">
    <label class="form-label">Effective From</label>
    <input type="date" name="effective_from" class="form-control"
           value="{{ old('effective_from', $rule->effective_from) }}" required>
  </div>

  <div class="col-md-3">
    <label class="form-label">Effective To (optional)</label>
    <input type="date" name="effective_to" class="form-control"
           value="{{ old('effective_to', $rule->effective_to) }}">
  </div>

  <div class="col-12">
    <label class="form-label">Notes</label>
    <textarea name="notes" rows="2" class="form-control">{{ old('notes', $rule->notes) }}</textarea>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.tax-rules.index') }}" class="btn btn-secondary">Cancel</a>
</div>
