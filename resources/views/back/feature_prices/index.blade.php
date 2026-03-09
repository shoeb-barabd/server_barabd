@extends('layouts.app')
@section('title','Customize Feature Prices')
@section('page_title','Customize Feature Prices')
@section('breadcrumb','Customize Feature Prices')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.feature-prices.index') }}">
      <select name="customize_feature_id" class="form-select" style="max-width:320px">
        <option value="">All features</option>
        @foreach($features as $f)
          <option value="{{ $f->id }}" @selected($featureId==$f->id)">
            {{ $f->category->name ?? '—' }} — {{ $f->label }} ({{ $f->key }})
          </option>
        @endforeach
      </select>
      <select name="location_id" class="form-select" style="max-width:220px">
        <option value="">All locations</option>
        @foreach($locations as $l)
          <option value="{{ $l->id }}" @selected($locationId==$l->id)>{{ $l->name }}</option>
        @endforeach
      </select>
      <select name="billing_cycle_id" class="form-select" style="max-width:200px">
        <option value="">All cycles</option>
        @foreach($cycles as $c)
          <option value="{{ $c->id }}" @selected($cycleId==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.feature-prices.index') }}" class="btn btn-outline-secondary">Reset</a>
    </form>
    <a href="{{ route('admin.feature-prices.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-circle"></i> Add</a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead><tr>
          <th>#</th><th>Feature</th><th>Location</th><th>Cycle</th>
          <th class="text-end">Included</th><th class="text-end">Step</th><th class="text-end">Price/Step</th>
          <th style="width:140px">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($rows as $i=>$r)
          <tr>
            <td>{{ $rows->firstItem()+$i }}</td>
            <td>{{ ($r->feature->category->name ?? '—').' — '.($r->feature->label ?? '') }}</td>
            <td>{{ $r->location->name ?? '—' }}</td>
            <td>{{ $r->billingCycle->name ?? '—' }}</td>
            <td class="text-end">{{ $r->included_value ?? '—' }}</td>
            <td class="text-end">{{ $r->step }}</td>
            <td class="text-end">{{ number_format($r->price_per_step,2) }}</td>
            <td>
              <a class="btn btn-sm btn-primary" href="{{ route('admin.feature-prices.edit',$r) }}"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.feature-prices.destroy',$r) }}" method="post" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE') <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($rows->hasPages()) <div class="card-footer">{{ $rows->links() }}</div> @endif
</div>
@endsection
