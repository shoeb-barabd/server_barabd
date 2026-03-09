@extends('layouts.app')
@section('title','Customize Features')
@section('page_title','Customize Features')
@section('breadcrumb','Customize Features')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3 align-items-center" method="get" action="{{ route('admin.customize-features.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search key/label…" value="{{ $q ?? '' }}"
             style="width:clamp(280px,35vw,480px)">

      <select name="category_id" class="form-select" style="max-width:260px">
        <option value="">All categories</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected((string)$categoryId === (string)$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>

      <select name="input_type" class="form-select" style="max-width:200px">
        <option value="">All input types</option>
        @foreach(['number','boolean','select'] as $t)
          <option value="{{ $t }}" @selected($inputType===$t)>{{ ucfirst($t) }}</option>
        @endforeach
      </select>

      <button class="btn btn-primary">Filter</button>
      @if(!empty($q) || !empty($categoryId) || !empty($inputType))
        <a class="btn btn-outline-secondary" href="{{ route('admin.customize-features.index') }}">Reset</a>
      @endif
    </form>

    <a href="{{ route('admin.customize-features.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Feature
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Category</th>
            <th>Key</th>
            <th>Label</th>
            <th>Input</th>
            <th>Unit</th>
            <th>Range/Step</th>
            <th>Required</th>
            <th style="width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($features as $i => $row)
            <tr>
              <td>{{ $features->firstItem() + $i }}</td>
              <td>{{ $row->category->name ?? '—' }}</td>
              <td><code>{{ $row->key }}</code></td>
              <td>{{ $row->label }}</td>
              <td><span class="badge bg-dark">{{ $row->input_type }}</span></td>
              <td>{{ $row->unit ?? '—' }}</td>
              <td>
                @php
                  $min = $row->min !== null ? rtrim(rtrim(number_format($row->min,2,'.',''), '0'),'.') : null;
                  $max = $row->max !== null ? rtrim(rtrim(number_format($row->max,2,'.',''), '0'),'.') : null;
                  $step= $row->step!== null ? rtrim(rtrim(number_format($row->step,2,'.',''), '0'),'.') : null;
                @endphp
                @if($min!==null || $max!==null || $step!==null)
                  {{ $min ?? '—' }} – {{ $max ?? '—' }}, step {{ $step ?? '—' }}
                @else
                  —
                @endif
              </td>
              <td>
                @if($row->is_required)
                  <span class="badge bg-success">Yes</span>
                @else
                  <span class="badge bg-secondary">No</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.customize-features.edit', $row) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.customize-features.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this feature?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
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

  @if($features->hasPages())
    <div class="card-footer">{{ $features->links() }}</div>
  @endif
</div>
@endsection
