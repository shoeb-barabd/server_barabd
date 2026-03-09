@extends('layouts.app')
@section('title','Edit Billing Cycle')
@section('page_title','Edit Billing Cycle')
@section('breadcrumb','Billing Cycles / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Edit Billing Cycle: {{ $billingCycle->name }}</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.billing-cycles.update', $billingCycle) }}">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $billingCycle->name) }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Key <span class="text-danger">*</span></label>
        <input type="text" name="key" class="form-control" value="{{ old('key', $billingCycle->key) }}" required>
        @error('key') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Months (e.g., 1 for monthly, 3 for quarterly) <span class="text-danger">*</span></label>
        <input type="number" name="months" class="form-control" value="{{ old('months', $billingCycle->months) }}" required>
        @error('months') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active', $billingCycle->is_active))>Yes</option>
          <option value="0" @selected(!old('is_active', $billingCycle->is_active))>No</option>
        </select>
        @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">WHMCS Cycle</label>
        <select name="whmcs_cycle" class="form-select">
          <option value="">-- Select --</option>
          @foreach(['monthly','quarterly','semiannually','annually','biennially','triennially'] as $c)
            <option value="{{ $c }}" @selected(old('whmcs_cycle', $billingCycle->whmcs_cycle) === $c)>{{ ucfirst($c) }}</option>
          @endforeach
        </select>
        <small class="text-muted">WHMCS এ এই billing cycle কোন নামে আছে সেটা select করুন।</small>
        @error('whmcs_cycle') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
        <a href="{{ route('admin.billing-cycles.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
