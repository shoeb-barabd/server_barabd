@extends('layouts.app')
@section('title','New Product')
@section('page_title','New Product')
@section('breadcrumb','Products / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Create Product</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.products.store') }}">
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
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
      </div>
      {{--  new field class_icon  --}}
        <div class="mb-3">
            <label class="form-label">Icon Class</label>
            <input type="text" name="icon_class" class="form-control" value="{{ old('icon_class') }}">
            @error('icon_class') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        {{--  new field save_text  --}}
        <div class="mb-3">
            <label class="form-label">Save Text</label>
            <input type="text" name="save_text" class="form-control" value="{{ old('save_text') }}">
            @error('save_text') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

      <div class="mb-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active') == 1)>Yes</option>
          <option value="0" @selected(old('is_active') == 0)>No</option>
        </select>
        @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">WHMCS Product ID</label>
        <input type="number" name="whmcs_product_id" class="form-control" value="{{ old('whmcs_product_id') }}" placeholder="WHMCS এর Product/Service ID (optional)">
        <small class="text-muted">WHMCS Admin → Setup → Products/Services থেকে Product ID দিন। দিলে payment success এ auto-provision হবে।</small>
        @error('whmcs_product_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
