@extends('layouts.app')
@section('title','Accounts')
@section('page_title','Accounts')
@section('breadcrumb','Accounts')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.accounts.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search name/email/phone"
             value="{{ $q }}" style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.accounts.index') }}">Reset</a> @endif
    </form>

    <a href="{{ route('admin.accounts.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Account
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Name</th>
            <th>Country</th>
            <th>Type</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Active</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($accounts as $i=>$row)
          <tr>
            <td>{{ $accounts->firstItem()+$i }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->country->name ?? '—' }}</td>
            <td>{{ $row->type ?? '—' }}</td>
            <td>{{ $row->email ?? '—' }}</td>
            <td>{{ $row->phone ?? '—' }}</td>
            <td>{{ $row->status ?? '—' }}</td>
            <td>{!! ($row->is_active ?? true) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
            <td>
              <a href="{{ route('admin.accounts.edit',$row) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.accounts.destroy',$row) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this account?')">
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

  @if($accounts->hasPages())
    <div class="card-footer">{{ $accounts->links() }}</div>
  @endif
</div>
@endsection
