@extends('layouts.app')
@section('title','Exchange Rates')
@section('page_title','Exchange Rates')
@section('breadcrumb','Exchange Rates')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.exchange-rates.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search base/quote (e.g., USD, BDT)" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.exchange-rates.index') }}">Reset</a> @endif
    </form>
    <a href="{{ route('admin.exchange-rates.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Rate
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th><th>Pair</th><th>Rate</th><th>Valid From</th><th>Valid To</th><th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rates as $i=>$row)
          <tr>
            <td>{{ $rates->firstItem()+$i }}</td>
            <td><span class="badge bg-dark">{{ $row->base_currency_code }}</span> → <span class="badge bg-secondary">{{ $row->quote_currency_code }}</span></td>
            <td>{{ $row->rate }}</td>
            <td>{{ $row->valid_from }}</td>
            <td>{{ $row->valid_to ?? '—' }}</td>
            <td>
              <a href="{{ route('admin.exchange-rates.edit',$row) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.exchange-rates.destroy',$row) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this rate?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
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

  @if($rates->hasPages())
    <div class="card-footer">{{ $rates->links() }}</div>
  @endif
</div>
@endsection
