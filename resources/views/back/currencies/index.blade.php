@extends('layouts.app')
@section('title','Currencies')
@section('page_title','Currencies')
@section('breadcrumb','Currencies')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.currencies.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search code/name" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.currencies.index') }}">Reset</a> @endif
    </form>
    <a href="{{ route('admin.currencies.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Currency
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th><th>Code</th><th>Name</th><th>Symbol</th><th>Decimals</th><th>Active</th><th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($currencies as $i=>$row)
          <tr>
            <td>{{ $currencies->firstItem()+$i }}</td>
            <td><span class="badge bg-dark">{{ $row->code }}</span></td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->symbol }}</td>
            <td>{{ $row->decimal_places }}</td>
            <td>{!! $row->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
            <td>
              <a href="{{ route('admin.currencies.edit',$row) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.currencies.destroy',$row) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this currency?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($currencies->hasPages())
    <div class="card-footer">{{ $currencies->links() }}</div>
  @endif
</div>
@endsection
