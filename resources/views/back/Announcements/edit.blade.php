@extends('layouts.app')

@section('title', 'Edit Announcement')
@section('page_title', 'Edit Announcement')
@section('breadcrumb', 'Announcements')

@section('content')
  @include('back.partials.flash')

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Edit Announcement</h5>
      <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
        @method('PUT')
        @include('back.Announcements.form', ['submitLabel' => 'Update'])
      </form>
    </div>
  </div>
@endsection
