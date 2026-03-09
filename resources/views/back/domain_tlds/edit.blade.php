@extends('layouts.app')
@section('title','Edit TLD')
@section('page_title','Edit TLD')
@section('breadcrumb','Domain TLDs / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">Edit {{ $tld->tld }}</h5></div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.domain-tlds.update', $tld) }}">
      @csrf @method('PUT')

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">TLD <span class="text-danger">*</span></label>
          <input type="text" name="tld" class="form-control" value="{{ old('tld', $tld->tld) }}" required>
          @error('tld') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Currency</label>
          <input type="text" name="currency" class="form-control" value="{{ old('currency', $tld->currency) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $tld->sort_order) }}" min="0">
        </div>
      </div>

      <div class="row g-3 mt-2">
        <div class="col-md-4">
          <label class="form-label">Register Price <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="register_price" class="form-control" value="{{ old('register_price', $tld->register_price) }}" required>
          @error('register_price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Renew Price <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="renew_price" class="form-control" value="{{ old('renew_price', $tld->renew_price) }}" required>
          @error('renew_price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Transfer Price</label>
          <input type="number" step="0.01" name="transfer_price" class="form-control" value="{{ old('transfer_price', $tld->transfer_price) }}">
        </div>
      </div>

      <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $tld->is_active))>
        <label class="form-check-label" for="is_active">Active</label>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
        <a href="{{ route('admin.domain-tlds.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
