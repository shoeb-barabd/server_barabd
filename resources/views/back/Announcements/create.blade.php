@extends('layouts.app')

@section('title', 'New Announcement')
@section('page_title', 'New Announcement')
@section('breadcrumb', 'Announcements')

@section('content')
  @include('back.partials.flash')

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Create Announcement</h5>
      <form method="POST" action="{{ route('admin.announcements.store') }}">
        @include('back.Announcements.form', ['submitLabel' => 'Create'])
      </form>
    </div>
  </div>
@endsection
