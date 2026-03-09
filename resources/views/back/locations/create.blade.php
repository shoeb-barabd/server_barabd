@extends('layouts.app')
@section('title','New Location')
@section('page_title','New Location')
@section('breadcrumb','Locations / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Create Location</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.locations.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $location->name) }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Currency Code <span class="text-danger">*</span></label>
          <input type="text" name="currency_code" class="form-control" maxlength="3"
                 value="{{ old('currency_code', $location->currency_code) }}" required>
          @error('currency_code') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
          <input type="number" name="tax_rate_percent" step="0.01" min="0" max="100" class="form-control"
                 value="{{ old('tax_rate_percent', $location->tax_rate_percent) }}" required>
          @error('tax_rate_percent') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Active</label>
          <select name="is_active" class="form-select">
            <option value="1" @selected(old('is_active', $location->is_active))>Yes</option>
            <option value="0" @selected(!old('is_active', $location->is_active))>No</option>
          </select>
          @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      {{-- Optional: keep country_id hidden for now --}}
      <input type="hidden" name="country_id" value="{{ old('country_id', $location->country_id) }}">

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
