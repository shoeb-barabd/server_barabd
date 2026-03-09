@extends('layouts.app')
@section('title','Coupons')
@section('page_title','Coupons')
@section('breadcrumb','Coupons')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex flex-wrap gap-2 align-items-center me-3" method="get" action="{{ route('admin.coupons.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search code/title..."
             value="{{ $q }}" style="min-width:220px">

      <select name="status" class="form-select" style="min-width:150px">
        <option value="">All status</option>
        <option value="active" @selected($status === 'active')>Active</option>
        <option value="inactive" @selected($status === 'inactive')>Inactive</option>
      </select>

      <button class="btn btn-primary">Filter</button>
      @if($q || $status)
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Reset</a>
      @endif
    </form>

    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> New Coupon
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
        <tr>
          <th>#</th>
          <th>Code</th>
          <th>Title</th>
          <th>Discount</th>
          <th>Validity</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($coupons as $i => $coupon)
          <tr>
            <td>{{ $coupons->firstItem() + $i }}</td>
            <td><code>{{ $coupon->code }}</code></td>
            <td>{{ $coupon->title ?? '—' }}</td>
            <td>{{ number_format($coupon->discount_amount, 2) }}</td>
            <td class="small">
              <div>From: {{ optional($coupon->starts_at)->format('d M Y H:i') ?? 'Any' }}</div>
              <div>To: {{ optional($coupon->ends_at)->format('d M Y H:i') ?? 'Any' }}</div>
            </td>
            <td>
              @if($coupon->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="post" class="d-inline"
                    onsubmit="return confirm('Delete this coupon?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No coupons found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($coupons->hasPages())
    <div class="card-footer">
      {{ $coupons->links() }}
    </div>
  @endif
</div>
@endsection
