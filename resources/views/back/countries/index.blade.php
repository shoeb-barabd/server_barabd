@extends('layouts.app')

@section('title','Countries')
@section('page_title','Countries')
@section('breadcrumb','Countries')

@section('content')

@include('back.partials.flash')

<div class="card card-primary card-outline">
    <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.countries.index') }}">
        <input
        type="text"
        name="q"
        class="form-control"
        placeholder="Search name/ISO/phone"
        value="{{ $q }}"
        style="width:clamp(320px, 45vw, 640px)"
        >
        <button class="btn btn-primary">Search</button>
        @if($q !== '')
        <a class="btn btn-outline-secondary" href="{{ route('admin.countries.index') }}">Reset</a>
        @endif
    </form>

    <a href="{{ route('admin.countries.create') }}" class="btn btn-success ms-auto">
        <i class="bi bi-plus-circle"></i> Add Country
    </a>
    </div>


  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width: 50px">#</th> 
            <th>Name</th>
            <th>ISO2</th>
            <th>ISO3</th>
            <th>Phone</th>
            <th>Active</th>
            <th style="width: 140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($countries as $i => $row)
            <tr>
              <td>{{ $countries->firstItem() + $i }}</td>
              <td>{{ $row->name }}</td>
              <td><span class="badge bg-secondary">{{ $row->iso2 }}</span></td>
              <td>{{ $row->iso3 }}</td>
              <td>{{ $row->phone_code }}</td>
              <td>
                @if($row->is_active)
                  <span class="badge bg-success">Yes</span>
                @else
                  <span class="badge bg-secondary">No</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.countries.edit', $row) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.countries.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this country?')">
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

  @if($countries->hasPages())
    <div class="card-footer">
      {{ $countries->links() }}
    </div>
  @endif
</div>
@endsection
