@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">Base Currency</label>
    <select name="base_currency_code" class="form-select" required>
      <option value="">-- select --</option>
      @foreach($currencies as $c)
        <option value="{{ $c->code }}" @selected(old('base_currency_code', $rate->base_currency_code) == $c->code)>{{ $c->code }} — {{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Quote Currency</label>
    <select name="quote_currency_code" class="form-select" required>
      <option value="">-- select --</option>
      @foreach($currencies as $c)
        <option value="{{ $c->code }}" @selected(old('quote_currency_code', $rate->quote_currency_code) == $c->code)>{{ $c->code }} — {{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Rate (1 base = ? quote)</label>
    <input type="number" name="rate" step="0.00000001" class="form-control" value="{{ old('rate',$rate->rate) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Valid From</label>
    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from',$rate->valid_from) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Valid To (optional)</label>
    <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to',$rate->valid_to) }}">
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.exchange-rates.index') }}" class="btn btn-secondary">Cancel</a>
</div>
