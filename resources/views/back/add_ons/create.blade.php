@extends('layouts.app')
@section('title','New Add-on')
@section('page_title','New Add-on')
@section('breadcrumb','Add-ons / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">Create Add-on</h5></div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.add-ons.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select" required>
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Key <span class="text-danger">*</span></label>
        <input type="text" name="key" class="form-control" value="{{ old('key',$addon->key) }}" placeholder="e.g., dedicated_ip" required>
        @error('key') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Label <span class="text-danger">*</span></label>
        <input type="text" name="label" class="form-control" value="{{ old('label',$addon->label) }}" placeholder="e.g., Dedicated IP" required>
        @error('label') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Optional">{{ old('description',$addon->description) }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Billing Type <span class="text-danger">*</span></label>
          <select name="unit_type" class="form-select" required>
            @foreach(['recurring','one_time'] as $t)
              <option value="{{ $t }}" @selected(old('unit_type',$addon->unit_type)===$t)>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
            @endforeach
          </select>
          @error('unit_type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Quantity Based? <span class="text-danger">*</span></label>
          <select name="is_qty_based" class="form-select" required>
            <option value="1" @selected(old('is_qty_based',$addon->is_qty_based))>Yes</option>
            <option value="0" @selected(!old('is_qty_based',$addon->is_qty_based))>No</option>
          </select>
          @error('is_qty_based') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Max Qty <span class="text-danger">*</span></label>
          <input type="number" name="max_qty" class="form-control" value="{{ old('max_qty',$addon->max_qty ?? 1) }}" min="1" max="65535" required>
          @error('max_qty') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="form-label">Active?</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active',$addon->is_active))>Yes</option>
          <option value="0" @selected(!old('is_active',$addon->is_active))>No</option>
        </select>
        @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
        <a href="{{ route('admin.add-ons.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
