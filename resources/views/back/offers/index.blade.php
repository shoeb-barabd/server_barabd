@extends('layouts.app')
@section('title','Offer URL')
@section('page_title','Offer URL')
@section('breadcrumb','Offers')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline mb-4">
  <div class="card-header">
    <h5 class="m-0">Set Special Offer URL</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.offers.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Offer URL</label>
        <input type="url" name="url" class="form-control" value="{{ old('url') }}" placeholder="https://example.com/your-offer">
        <small class="text-muted">Link used by the front-page “Claim Offer” button.</small>
        @error('url') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Package Name</label>
        <input type="text" name="package_name" class="form-control" value="{{ old('package_name') }}" placeholder="e.g., Premium Hosting">
        @error('package_name') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Discount Percent</label>
        <input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control" value="{{ old('discount_percent') }}" placeholder="e.g., 50">
        @error('discount_percent') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active', 1) == 1)>Yes</option>
          <option value="0" @selected(old('is_active') === '0')>No</option>
        </select>
        @error('is_active') <small class="text-danger d-block">{{ $message }}</small> @enderror
      </div>

      <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save Offer</button>
    </form>
  </div>
</div>

<div class="card card-outline">
  <div class="card-header">
    <h5 class="m-0">Recent Offers</h5>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>URL</th>
            <th>Package</th>
            <th>Discount</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($offers as $i => $offer)
            <tr>
              <td>{{ $offers->firstItem() + $i }}</td>
              <td class="text-break">{{ $offer->url ?? '—' }}</td>
              <td>{{ $offer->package_name ?? '—' }}</td>
              <td>{{ $offer->discount_percent !== null ? rtrim(rtrim(number_format($offer->discount_percent, 2), '0'), '.') . '%' : '—' }}</td>
              <td>
                @if($offer->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td>{{ $offer->created_at?->format('Y-m-d H:i') }}</td>
              <td class="d-flex gap-2 flex-wrap">
                <form method="post" action="{{ route('admin.offers.update', $offer) }}" class="d-inline-flex align-items-center">
                  @csrf @method('PUT')
                  <input type="hidden" name="url" value="{{ $offer->url }}">
                  <input type="hidden" name="package_name" value="{{ $offer->package_name }}">
                  <input type="hidden" name="discount_percent" value="{{ $offer->discount_percent }}">
                  <input type="hidden" name="is_active" value="1">
                  <button class="btn btn-sm btn-outline-primary">Set Active</button>
                </form>
                <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-secondary">Edit</a>
                <form method="post" action="{{ route('admin.offers.destroy', $offer) }}" onsubmit="return confirm('Delete this offer?')" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No offers yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($offers->hasPages())
    <div class="card-footer">{{ $offers->links() }}</div>
  @endif
</div>
@endsection
