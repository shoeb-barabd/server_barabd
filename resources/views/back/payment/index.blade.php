@extends('layouts.app')
@section('title','Payments')
@section('page_title','Payments')
@section('breadcrumb','Payments')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.payments.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search tran id / product name" value="{{ $q }}"
             style="width:clamp(280px,40vw,520px)">
      <select name="status" class="form-select" style="max-width:180px">
        <option value="">All Status</option>
        @foreach($statuses as $key => $label)
          <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
      @if($q !== '' || $status !== '')
        <a class="btn btn-outline-secondary" href="{{ route('admin.payments.index') }}">Reset</a>
      @endif
    </form>

    <a href="{{ route('admin.payments.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add Payment
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Transaction</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Status</th>
            <th>User</th>
            <th>Created</th>
            <th style="width:220px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $i => $row)
            <tr>
              <td>{{ $payments->firstItem() + $i }}</td>
              <td>
                <div class="fw-semibold">{{ $row->tran_id }}</div>
                <small class="text-muted">Val: {{ $row->val_id ?? '—' }}</small>
              </td>
              <td>{{ $row->product_name ?? $row->product?->name ?? 'N/A' }}</td>
              <td>{{ number_format($row->amount, 2) }} {{ $row->currency }}</td>
              <td>
                @php
                  $statusClass = match($row->status) {
                    'SUCCESS' => 'bg-success',
                    'PENDING' => 'bg-warning text-dark',
                    'FAILED' => 'bg-danger',
                    'CANCELLED' => 'bg-secondary',
                    default => 'bg-info text-dark',
                  };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $row->status }}</span>
              </td>
              <td>{{ $row->user?->name ?? 'Guest' }}</td>
              <td>{{ $row->created_at?->format('Y-m-d H:i') }}</td>
              <td class="d-flex flex-wrap gap-1">
                <a href="{{ route('admin.payments.show', $row) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-eye"></i> View
                </a>
                <a href="{{ route('admin.payments.edit', $row) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('admin.payments.destroy', $row) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this payment?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No payments found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
  @endif
</div>
@endsection
