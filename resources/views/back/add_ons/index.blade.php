@extends('layouts.app')
@section('title','Add-ons')
@section('page_title','Add-ons')
@section('breadcrumb','Add-ons')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3 align-items-center" method="get" action="{{ route('admin.add-ons.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search key/label/description…" value="{{ $q ?? '' }}"
             style="width:clamp(280px,35vw,520px)">

      <select name="unit_type" class="form-select" style="max-width:200px">
        <option value="">All billing types</option>
        @foreach(['recurring','one_time'] as $t)
          <option value="{{ $t }}" @selected($unitType===$t)>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
        @endforeach
      </select>

      <select name="category_id" class="form-select" style="max-width:200px">
        <option value="">All categories</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @selected($categoryId == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>

      <div class="form-check ms-2">
        <input class="form-check-input" type="checkbox" name="only_active" value="1" id="onlyActive" @checked($status)>
        <label class="form-check-label" for="onlyActive">Only active</label>
      </div>

      <button class="btn btn-primary ms-2">Filter</button>
      @if(!empty($q) || !empty($unitType) || $status)
        <a class="btn btn-outline-secondary" href="{{ route('admin.add-ons.index') }}">Reset</a>
      @endif
    </form>

    <a href="{{ route('admin.add-ons.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Add-on
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Key</th>
            <th>Label</th>
            <th>Category</th>
            <th>Billing</th>
            <th>Qty-based</th>
            <th>Max Qty</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($addons as $i => $row)
            <tr>
              <td>{{ $addons->firstItem() + $i }}</td>
              <td><code>{{ $row->key }}</code></td>
              <td>
                <div class="fw-semibold">{{ $row->label }}</div>
                @if($row->description)
                  <div class="text-muted small">{{ Str::limit($row->description, 80) }}</div>
                @endif
              </td>
              <td>{{ $row->category->name ?? 'N/A' }}</td>
              <td><span class="badge bg-dark">{{ ucfirst(str_replace('_',' ',$row->unit_type)) }}</span></td>
              <td>
                @if($row->is_qty_based) <span class="badge bg-primary">Yes</span>
                @else <span class="badge bg-secondary">No</span> @endif
              </td>
              <td>{{ $row->max_qty }}</td>
              <td>
                @if($row->is_active) <span class="badge bg-success">Active</span>
                @else <span class="badge bg-secondary">Inactive</span> @endif
              </td>
              <td>
                <a href="{{ route('admin.add-ons.edit', $row) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('admin.add-ons.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this add-on?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($addons->hasPages())
    <div class="card-footer">{{ $addons->links() }}</div>
  @endif
</div>
@endsection
