@extends('layouts.app')
@section('title','Contacts')
@section('page_title','Contacts')
@section('breadcrumb','Contacts')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.contacts.index') }}">
      <input type="text" name="q" class="form-control"
             placeholder="Search name/email/phone/account" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q!=='') <a class="btn btn-outline-secondary" href="{{ route('admin.contacts.index') }}">Reset</a> @endif
    </form>

    <a href="{{ route('admin.contacts.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Contact
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Account</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Title</th>
            <th>Primary</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($contacts as $i=>$row)
          <tr>
            <td>{{ $contacts->firstItem() + $i }}</td>
            <td>{{ $row->account->name ?? '—' }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->phone ?? '—' }}</td>
            <td>{{ $row->designation ?? '—' }}</td>
            <td>
              @if($row->is_primary)
                <span class="badge bg-success">Yes</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.contacts.edit',$row) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.contacts.destroy',$row) }}" method="post" class="d-inline"
                    onsubmit="return confirm('Delete this contact?')">
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

  @if($contacts->hasPages())
    <div class="card-footer">{{ $contacts->links() }}</div>
  @endif
</div>
@endsection
