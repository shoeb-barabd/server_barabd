@extends('layouts.app')
@section('title', isset($row->id)?'Edit Feature Price':'New Feature Price')
@section('page_title', isset($row->id)?'Edit Feature Price':'New Feature Price')
@section('breadcrumb','Feature Prices / '.(isset($row->id)?'Edit':'New'))
@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">{{ isset($row->id)?'Edit':'Create' }}</h5></div>
  <div class="card-body">
    <form method="post" action="{{ isset($row->id) ? route('admin.feature-prices.update',$row) : route('admin.feature-prices.store') }}">
      @csrf @if(isset($row->id)) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Customize Feature</label>
          <select name="customize_feature_id" class="form-select" required>
            @foreach($features as $f)
              <option value="{{ $f->id }}" @selected(old('customize_feature_id',$row->customize_feature_id)==$f->id)>
                {{ $f->category->name ?? '—' }} — {{ $f->label }} ({{ $f->key }})
              </option>
            @endforeach
          </select>
          @error('customize_feature_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">Location</label>
          <select name="location_id" class="form-select" required>
            @foreach($locations as $l)
              <option value="{{ $l->id }}" @selected(old('location_id',$row->location_id)==$l->id)>{{ $l->name }}</option>
            @endforeach
          </select>
          @error('location_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">Cycle</label>
          <select name="billing_cycle_id" class="form-select" required>
            @foreach($cycles as $c)
              <option value="{{ $c->id }}" @selected(old('billing_cycle_id',$row->billing_cycle_id)==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
          @error('billing_cycle_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-4">
          <label class="form-label">Included Value</label>
          <input type="number" step="0.0001" name="included_value" class="form-control" value="{{ old('included_value',$row->included_value) }}">
          @error('included_value')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Step</label>
          <input type="number" step="0.0001" name="step" class="form-control" value="{{ old('step',$row->step ?? 1) }}" required>
          @error('step')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Price per Step</label>
          <input type="number" step="0.01" name="price_per_step" class="form-control" value="{{ old('price_per_step',$row->price_per_step) }}" required>
          @error('price_per_step')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-{{ isset($row->id)?'primary':'success' }}">{{ isset($row->id)?'Update':'Save' }}</button>
        <a href="{{ route('admin.feature-prices.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
