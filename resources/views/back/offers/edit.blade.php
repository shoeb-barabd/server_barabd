@extends('layouts.app')
@section('title','Edit Offer')
@section('page_title','Edit Offer')
@section('breadcrumb','Offers / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Edit Offer</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.offers.update', $offer) }}">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">Offer URL</label>
        <input type="url" name="url" class="form-control" value="{{ old('url', $offer->url) }}" placeholder="https://example.com/your-offer">
        <small class="text-muted">Link used by the front-page “Claim Offer” button.</small>
        @error('url') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Package Name</label>
        <input type="text" name="package_name" class="form-control" value="{{ old('package_name', $offer->package_name) }}" placeholder="e.g., Premium Hosting">
        @error('package_name') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Discount Percent</label>
        <input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control" value="{{ old('discount_percent', $offer->discount_percent) }}" placeholder="e.g., 50">
        @error('discount_percent') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active', $offer->is_active) == 1)>Yes</option>
          <option value="0" @selected(old('is_active', $offer->is_active) == 0)>No</option>
        </select>
        @error('is_active') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
        <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
