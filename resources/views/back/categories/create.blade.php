@extends('layouts.app')
@section('title','New Category')
@section('page_title','New Category')
@section('breadcrumb','Categories / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Create Category</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.categories.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}" required>
        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active', $category->is_active))>Yes</option>
          <option value="0" @selected(!old('is_active', $category->is_active))>No</option>
        </select>
        @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
