@extends('layouts.app')
@section('title','Tax Rules')
@section('page_title','Tax Rules')
@section('breadcrumb','Tax Rules')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.tax-rules.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search country/tax name" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.tax-rules.index') }}">Reset</a> @endif
    </form>
    <a href="{{ route('admin.tax-rules.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Rule
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr><th>#</th><th>Country</th><th>Tax</th><th>Rate %</th><th>Inclusive?</th><th>From</th><th>To</th><th style="width:140px">Actions</th></tr>
        </thead>
        <tbody>
          @forelse($rules as $i=>$r)
          <tr>
            <td>{{ $rules->firstItem()+$i }}</td>
            <td>{{ $r->country->name }} ({{ $r->country->iso2 }})</td>
            <td>{{ $r->tax_name }}</td>
            <td>{{ number_format($r->rate_percent,2) }}</td>
            <td>{!! $r->is_inclusive ? '<span class="badge bg-info">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
            <td>{{ $r->effective_from }}</td>
            <td>{{ $r->effective_to ?? '—' }}</td>
            <td>
              <a href="{{ route('admin.tax-rules.edit',$r) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.tax-rules.destroy',$r) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this rule?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
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

  @if($rules->hasPages())
    <div class="card-footer">{{ $rules->links() }}</div>
  @endif
</div>
@endsection
