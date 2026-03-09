@extends('layouts.app')
@section('title','Domain TLDs')
@section('page_title','Domain TLDs')
@section('breadcrumb','TLD Pricing')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <form class="d-flex gap-2 flex-grow-1 me-3" method="get" action="{{ route('admin.domain-tlds.index') }}">
      <input type="text" name="q" class="form-control" placeholder="Search TLD (e.g. .com)" value="{{ $q }}" style="width:clamp(250px,35vw,400px)">
      <button class="btn btn-primary">Search</button>
      @if($q !== '') <a class="btn btn-outline-secondary" href="{{ route('admin.domain-tlds.index') }}">Reset</a> @endif
    </form>
    <a href="{{ route('admin.domain-tlds.create') }}" class="btn btn-success ms-auto">
      <i class="bi bi-plus-circle"></i> Add TLD
    </a>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>TLD</th>
            <th>Register</th>
            <th>Renew</th>
            <th>Transfer</th>
            <th>Currency</th>
            <th>Status</th>
            <th>Order</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tlds as $i => $row)
            <tr>
              <td>{{ $tlds->firstItem() + $i }}</td>
              <td><strong>{{ $row->tld }}</strong></td>
              <td>{{ number_format($row->register_price, 2) }}</td>
              <td>{{ number_format($row->renew_price, 2) }}</td>
              <td>{{ number_format($row->transfer_price, 2) }}</td>
              <td>{{ $row->currency }}</td>
              <td>
                @if($row->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td>{{ $row->sort_order }}</td>
              <td>
                <a href="{{ route('admin.domain-tlds.edit', $row) }}" class="btn btn-sm btn-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.domain-tlds.destroy', $row) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this TLD?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No TLDs added yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($tlds->hasPages())
    <div class="card-footer">{{ $tlds->links() }}</div>
  @endif
</div>
@endsection
