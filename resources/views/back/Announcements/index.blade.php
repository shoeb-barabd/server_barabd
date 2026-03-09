@extends('layouts.app')

@section('title', 'Hosting Announcements')
@section('page_title', 'Hosting Announcements')
@section('breadcrumb', 'Announcements')

@section('content')
  @include('back.partials.flash')

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Announcements</h5>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i> New Announcement
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 35%;">Title</th>
              <th>Description</th>
              <th style="width: 16%;">Published At</th>
              <th style="width: 14%;">Actions</th>
            </tr>
          </thead>
          <tbody>
          @forelse($announcements as $announcement)
            <tr>
              <td class="fw-semibold">{{ $announcement->title }}</td>
              <td class="text-truncate" style="max-width: 420px;">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->description), 120) }}</td>
              <td>{{ $announcement->published_at ? $announcement->published_at->format('d M Y, H:i') : 'Unpublished' }}</td>
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No announcements found.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($announcements->hasPages())
      <div class="card-footer">
        {{ $announcements->links() }}
      </div>
    @endif
  </div>
@endsection
