@extends('layouts.app')
@section('title','New Product Feature')
@section('page_title','New Product Feature')
@section('breadcrumb','Product Features / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">Create Feature</h5></div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.product-features.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Product <span class="text-danger">*</span></label>
        <select name="product_id" class="form-select" required>
          <option value="">Select product</option>
          @foreach($products as $p)
            <option value="{{ $p->id }}" @selected(old('product_id')==$p->id)>{{ $p->name }}</option>
          @endforeach
        </select>
        @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Key <span class="text-danger">*</span></label>
        <input type="text" name="key" class="form-control" value="{{ old('key',$feature->key) }}" placeholder="e.g., storage_gb" required>
        @error('key') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Label <span class="text-danger">*</span></label>
        <input type="text" name="label" class="form-control" value="{{ old('label',$feature->label) }}" placeholder="e.g., SSD Storage" required>
        @error('label') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Input Type <span class="text-danger">*</span></label>
          <select name="input_type" class="form-select" required>
            @foreach(['number','boolean','text','select'] as $t)
              <option value="{{ $t }}" @selected(old('input_type',$feature->input_type)===$t)>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
          @error('input_type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Unit</label>
          <input type="text" name="unit" class="form-control" value="{{ old('unit',$feature->unit) }}" placeholder="e.g., GB">
          @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Step</label>
          <input type="number" step="0.01" name="step" class="form-control" value="{{ old('step',$feature->step) }}">
          @error('step') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <label class="form-label">Min</label>
          <input type="number" step="0.01" name="min" class="form-control" value="{{ old('min',$feature->min) }}">
          @error('min') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Max</label>
          <input type="number" step="0.01" name="max" class="form-control" value="{{ old('max',$feature->max) }}">
          @error('max') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="form-label">Options (JSON)</label>
        <textarea name="options_json" class="form-control" rows="3" placeholder='e.g., {"choices":["A","B","C"]}'>{{ old('options_json',$feature->options_json) }}</textarea>
        <small class="text-muted">Only used for <strong>select</strong> or custom input types.</small>
        @error('options_json') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Required?</label>
        <select name="is_required" class="form-select">
          <option value="1" @selected(old('is_required',$feature->is_required))>Yes</option>
          <option value="0" @selected(!old('is_required',$feature->is_required))>No</option>
        </select>
        @error('is_required') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
        <a href="{{ route('admin.product-features.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
