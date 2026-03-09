@extends('layouts.app')
@section('title','Categories')
@section('page_title','Categories')
@section('breadcrumb','Categories')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.categories.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search name/slug (e.g., Shared Hosting)" value="{{ $q }}"
             style="width:clamp(320px,45vw,640px)">
      <button class="btn btn-primary">Search</button>
      @if($q !== '') <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Reset</a> @endif
    </form>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Category
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Status</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $i => $row)
            <tr>
              <td>{{ $categories->firstItem() + $i }}</td>
              <td>{{ $row->name }}</td>
              <td>{{ $row->slug }}</td>
              <td>
                @if($row->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.categories.edit', $row) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this category?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
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

  @if($categories->hasPages())
    <div class="card-footer">{{ $categories->links() }}</div>
  @endif
</div>
@endsection
