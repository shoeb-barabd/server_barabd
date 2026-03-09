@extends('layouts.app')
@section('title', 'New Add-on Price')
@section('page_title', 'New Add-on Price')
@section('breadcrumb', 'Add-on Prices / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="m-0">Create New Add-on Price</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.add-on-prices.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Add-on</label>
                    <select name="add_on_id" class="form-select" required>
                        <option value="">Select Add-on</option>
                        @foreach($addons as $addon)
                            <option value="{{ $addon->id }}" @selected(old('add_on_id') == $addon->id)>{{ $addon->label }} ({{ $addon->key }})</option>
                        @endforeach
                    </select>
                    @error('add_on_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select" required>
                        <option value="">Select Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @selected(old('location_id') == $location->id)>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Billing Cycle</label>
                    <select name="billing_cycle_id" class="form-select" required>
                        <option value="">Select Billing Cycle</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}" @selected(old('billing_cycle_id') == $cycle->id)>{{ $cycle->name }}</option>
                        @endforeach
                    </select>
                    @error('billing_cycle_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control" value="{{ old('unit_price') }}" required>
                    @error('unit_price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-success">Save Add-on Price</button>
                <a href="{{ route('admin.add-on-prices.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
