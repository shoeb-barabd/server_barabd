@csrf

<div class="mb-3">
  <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
  <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required maxlength="50">
  @error('code') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
  <label class="form-label">Title</label>
  <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}">
  @error('title') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
  <label class="form-label">Description</label>
  <textarea name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
  @error('description') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Discount Amount <span class="text-danger">*</span></label>
    <div class="input-group">
      <span class="input-group-text">Tk</span>
      <input type="number" name="discount_amount" class="form-control" step="0.01" min="0"
             value="{{ old('discount_amount', $coupon->discount_amount) }}" required>
    </div>
    @error('discount_amount') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Starts At</label>
    <input type="datetime-local" name="starts_at" class="form-control"
           value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}">
    @error('starts_at') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Ends At</label>
    <input type="datetime-local" name="ends_at" class="form-control"
           value="{{ old('ends_at', optional($coupon->ends_at)->format('Y-m-d\TH:i')) }}">
    @error('ends_at') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
</div>

<div class="mb-3 mt-3">
  <label class="form-label">Status <span class="text-danger">*</span></label>
  <select name="is_active" class="form-select" required>
    <option value="1" @selected(old('is_active', $coupon->is_active) == true)>Active</option>
    <option value="0" @selected(old('is_active', $coupon->is_active) == false)>Inactive</option>
  </select>
  @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-success">
    <i class="bi bi-check2-circle"></i> Save
  </button>
  <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
</div>
