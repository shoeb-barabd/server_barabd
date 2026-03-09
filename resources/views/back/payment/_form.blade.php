@csrf
@if(!empty($isEdit))
  @method('PUT')
@endif

<div class="row">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Transaction ID</label>
      <input type="text" name="tran_id" class="form-control" value="{{ old('tran_id', $payment->tran_id) }}" placeholder="Auto-generated if blank">
      @error('tran_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-3">
    <div class="mb-3">
      <label class="form-label">Amount <span class="text-danger">*</span></label>
      <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $payment->amount) }}" required>
      @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-3">
    <div class="mb-3">
      <label class="form-label">Currency <span class="text-danger">*</span></label>
      <input type="text" name="currency" class="form-control" value="{{ old('currency', $payment->currency) }}" required>
      @error('currency') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Status <span class="text-danger">*</span></label>
      <select name="status" class="form-select" required>
        @foreach($statuses as $key => $label)
          <option value="{{ $key }}" @selected(old('status', $payment->status) == $key)>{{ $label }}</option>
        @endforeach
      </select>
      @error('status') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Val ID</label>
      <input type="text" name="val_id" class="form-control" value="{{ old('val_id', $payment->val_id) }}">
      @error('val_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Paid At</label>
      <input type="datetime-local" name="paid_at" class="form-control"
             value="{{ old('paid_at', $payment->paid_at ? $payment->paid_at->format('Y-m-d\\TH:i') : '') }}">
      @error('paid_at') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">User</label>
      <select name="user_id" class="form-select">
        <option value="">-- Unassigned --</option>
        @foreach($lookups['users'] as $user)
          <option value="{{ $user->id }}" @selected(old('user_id', $payment->user_id) == $user->id)>
            {{ $user->name }} ({{ $user->email }})
          </option>
        @endforeach
      </select>
      @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $payment->product_name) }}">
      @error('product_name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($lookups['categories'] as $category)
          <option value="{{ $category->id }}" @selected(old('category_id', $payment->category_id) == $category->id)>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
      @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Product</label>
      <select name="product_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($lookups['products'] as $product)
          <option value="{{ $product->id }}" @selected(old('product_id', $payment->product_id) == $product->id)>
            {{ $product->name }}
          </option>
        @endforeach
      </select>
      @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Billing Cycle</label>
      <select name="billing_cycle_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($lookups['billingCycles'] as $cycle)
          <option value="{{ $cycle->id }}" @selected(old('billing_cycle_id', $payment->billing_cycle_id) == $cycle->id)>
            {{ $cycle->name ?? $cycle->code }}
          </option>
        @endforeach
      </select>
      @error('billing_cycle_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Location</label>
      <select name="location_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($lookups['locations'] as $location)
          <option value="{{ $location->id }}" @selected(old('location_id', $payment->location_id) == $location->id)>
            {{ $location->name }}
          </option>
        @endforeach
      </select>
      @error('location_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Gateway Response (raw)</label>
      <textarea name="gateway_response" class="form-control" rows="3" placeholder="Optional raw JSON or message">{{ old('gateway_response', $payment->gateway_response) }}</textarea>
      @error('gateway_response') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Config (JSON)</label>
      <textarea name="config" class="form-control" rows="4" placeholder='e.g. {"features":[],"add_ons":[]}'>{!! old('config', $payment->config ? json_encode($payment->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') !!}</textarea>
      @error('config') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Line Items (JSON)</label>
      <textarea name="line_items" class="form-control" rows="4" placeholder='e.g. [{"title":"Base","amount":9.99}]'>{!! old('line_items', $payment->line_items ? json_encode($payment->line_items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') !!}</textarea>
      @error('line_items') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
  <div class="col-md-4">
    <div class="mb-3">
      <label class="form-label">Meta (JSON)</label>
      <textarea name="meta" class="form-control" rows="4" placeholder='e.g. {"notes":"..."}'>{!! old('meta', $payment->meta ? json_encode($payment->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') !!}</textarea>
      @error('meta') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-success"><i class="bi bi-check2-circle"></i> {{ $submitLabel ?? 'Save' }}</button>
  <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Back</a>
</div>
