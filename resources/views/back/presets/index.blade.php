@extends('layouts.app')
@section('title','Presets')
@section('page_title','Presets')
@section('breadcrumb','Presets')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.presets.index') }}">
      <select name="product_id" class="form-select" style="max-width:260px">
        <option value="">All products</option>
        @foreach($products as $p)
          <option value="{{ $p->id }}" @selected($productId==$p->id)>{{ $p->name }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.presets.index') }}" class="btn btn-outline-secondary">Reset</a>
    </form>
    <a href="{{ route('admin.presets.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-circle"></i> Add</a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead><tr>
          <th>#</th><th>Product</th><th>Name</th><th>Slug</th><th>Featured</th><th>Sort</th><th style="width:140px">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($rows as $i=>$r)
          <tr>
            <td>{{ $rows->firstItem()+$i }}</td>
            <td>{{ $r->product->name ?? '—' }}</td>
            <td>{{ $r->name }}</td>
            <td><code>{{ $r->slug }}</code></td>
            <td>@if($r->is_featured)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
            <td>{{ $r->sort_order }}</td>
            <td>
              <a class="btn btn-sm btn-primary" href="{{ route('admin.presets.edit',$r) }}"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.presets.destroy',$r) }}" method="post" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE') <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
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

  @if($rows->hasPages()) <div class="card-footer">{{ $rows->links() }}</div> @endif
</div>
@endsection
