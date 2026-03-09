@extends('layouts.app')
@section('title','Billing Cycles')
@section('page_title','Billing Cycles')
@section('breadcrumb','Billing Cycles')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.billing-cycles.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search (e.g., Monthly, Quarterly)" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q !== '') <a class="btn btn-outline-secondary" href="{{ route('admin.billing-cycles.index') }}">Reset</a> @endif
    </form>

    <a href="{{ route('admin.billing-cycles.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Billing Cycle
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Key</th>
            <th>Months</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($billingCycles as $i => $row)
            <tr>
              <td>{{ $billingCycles->firstItem() + $i }}</td>
              <td>{{ $row->name }}</td>
              <td>{{ $row->key }}</td>
              <td>{{ $row->months }}</td>
              <td>
                @if($row->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.billing-cycles.edit', $row) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.billing-cycles.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this billing cycle?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
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

  @if($billingCycles->hasPages())
    <div class="card-footer">{{ $billingCycles->links() }}</div>
  @endif
</div>
@endsection
