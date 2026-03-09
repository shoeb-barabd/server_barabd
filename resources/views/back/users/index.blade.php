@extends('layouts.app')
@section('title','Users')
@section('page_title','Users')
@section('breadcrumb','Users')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.users.index') }}">
      <input type="text" name="q" class="form-control"
             placeholder="Search name or email" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Reset</a> @endif
    </form>

    <a href="{{ route('admin.users.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add User
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Verified</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($users as $i=>$row)
          <tr>
            <td>{{ $users->firstItem() + $i }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>
              @if($row->email_verified_at)
                <span class="badge bg-success">Yes</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.users.edit',$row) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.users.destroy',$row) }}" method="post" class="d-inline"
                    onsubmit="return confirm('Delete this user?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">No data</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($users->hasPages())
    <div class="card-footer">{{ $users->links() }}</div>
  @endif
</div>
@endsection
