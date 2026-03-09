@extends('layouts.app')
@section('title','Domain Orders')
@section('page_title','Domain Orders')
@section('breadcrumb','Domain Orders')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center flex-wrap gap-2">
    <form class="d-flex gap-2 flex-grow-1" method="get" action="{{ route('admin.domain-orders.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search domain, tran_id, email…" value="{{ $q }}" style="width:clamp(250px,40vw,500px)">
      <select name="status" class="form-select" style="width:150px">
        <option value="">All Status</option>
        @foreach(['pending','paid','active','expired','cancelled','failed'] as $s)
          <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
      @if($q || $status) <a class="btn btn-outline-secondary" href="{{ route('admin.domain-orders.index') }}">Reset</a> @endif
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Domain</th>
            <th>Action</th>
            <th>Years</th>
            <th>Amount</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Tran ID</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $i => $row)
            <tr>
              <td>{{ $orders->firstItem() + $i }}</td>
              <td><strong>{{ $row->domain_name }}</strong></td>
              <td><span class="badge bg-info">{{ ucfirst($row->action) }}</span></td>
              <td>{{ $row->years }}</td>
              <td>{{ number_format($row->amount, 2) }} {{ $row->currency }}</td>
              <td>{{ $row->user?->email ?? '-' }}</td>
              <td>
                @php
                  $colors = ['active'=>'success','paid'=>'primary','pending'=>'warning','failed'=>'danger','cancelled'=>'secondary','expired'=>'dark'];
                @endphp
                <span class="badge bg-{{ $colors[$row->status] ?? 'secondary' }}">{{ ucfirst($row->status) }}</span>
              </td>
              <td><small>{{ $row->tran_id }}</small></td>
              <td><small>{{ $row->created_at?->format('d M Y') }}</small></td>
              <td>
                <a href="{{ route('admin.domain-orders.show', $row) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                <form action="{{ route('admin.domain-orders.destroy', $row) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this order?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="10" class="text-center text-muted py-4">No domain orders yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($orders->hasPages())
    <div class="card-footer">{{ $orders->links() }}</div>
  @endif
</div>
@endsection
