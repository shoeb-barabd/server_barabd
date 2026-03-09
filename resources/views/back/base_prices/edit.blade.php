@extends('layouts.app')
@section('title', isset($row->id)?'Edit Base Price':'New Base Price')
@section('page_title', isset($row->id)?'Edit Base Price':'New Base Price')
@section('breadcrumb','Base Prices / '.(isset($row->id)?'Edit':'New'))
@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">{{ isset($row->id)?'Edit':'Create' }}</h5></div>
  <div class="card-body">
    <form method="post" action="{{ isset($row->id) ? route('admin.base-prices.update',$row) : route('admin.base-prices.store') }}">
      @csrf @if(isset($row->id)) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Product</label>
          <select name="product_id" class="form-select" required>
            @foreach($products as $p)
              <option value="{{ $p->id }}" @selected(old('product_id',$row->product_id)==$p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
          @error('product_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Location</label>
          <select name="location_id" class="form-select" required>
            @foreach($locations as $l)
              <option value="{{ $l->id }}" @selected(old('location_id',$row->location_id)==$l->id)>{{ $l->name }}</option>
            @endforeach
          </select>
          @error('location_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Billing Cycle</label>
          <select name="billing_cycle_id" class="form-select" required>
            @foreach($cycles as $c)
              <option value="{{ $c->id }}" @selected(old('billing_cycle_id',$row->billing_cycle_id)==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
          @error('billing_cycle_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control" name="amount" value="{{ old('amount',$row->amount) }}" required>
        @error('amount')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-{{ isset($row->id)?'primary':'success' }}">{{ isset($row->id)?'Update':'Save' }}</button>
        <a href="{{ route('admin.base-prices.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
