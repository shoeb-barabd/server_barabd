@extends('layouts.app')
@section('title','Add-on Prices')
@section('page_title','Add-on Prices')
@section('breadcrumb','Add-on Prices')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.add-on-prices.index') }}">
      <select name="add_on_id" class="form-select" style="max-width:320px">
        <option value="">All add-ons</option>
        @foreach($addons as $a)
          <option value="{{ $a->id }}" @selected($addonId==$a->id)>{{ $a->label }} ({{ $a->key }})</option>
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
      <a href="{{ route('admin.add-on-prices.index') }}" class="btn btn-outline-secondary">Reset</a>
    </form>
    <a href="{{ route('admin.add-on-prices.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-circle"></i> Add</a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead><tr>
          <th>#</th><th>Add-on</th><th>Location</th><th>Cycle</th><th class="text-end">Unit Price</th><th style="width:140px">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($rows as $i=>$r)
          <tr>
            <td>{{ $rows->firstItem()+$i }}</td>
            <td>{{ $r->addOn->label ?? '—' }}</td>
            <td>{{ $r->location->name ?? '—' }}</td>
            <td>{{ $r->billingCycle->name ?? '—' }}</td>
            <td class="text-end">{{ number_format($r->unit_price,2) }}</td>
            <td>
              <a class="btn btn-sm btn-primary" href="{{ route('admin.add-on-prices.edit',$r) }}"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.add-on-prices.destroy',$r) }}" method="post" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE') <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($rows->hasPages()) <div class="card-footer">{{ $rows->links() }}</div> @endif
</div>
@endsection
