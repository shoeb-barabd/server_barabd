@extends('layouts.app')
@section('title', isset($row->id)?'Edit Preset':'New Preset')
@section('page_title', isset($row->id)?'Edit Preset':'New Preset')
@section('breadcrumb','Presets / '.(isset($row->id)?'Edit':'New'))

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header"><h5 class="m-0">{{ isset($row->id)?'Edit':'Create' }}</h5></div>
  <div class="card-body">
    <form method="post" action="{{ isset($row->id) ? route('admin.presets.update',$row) : route('admin.presets.store') }}">
      @csrf @if(isset($row->id)) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Product</label>
          <select name="product_id" class="form-select" required>
            @foreach($products as $p)
              <option value="{{ $p->id }}" @selected(old('product_id',$row->product_id)==$p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
          @error('product_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" name="name" value="{{ old('name',$row->name) }}" required>
          @error('name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Slug</label>
          <input type="text" class="form-control" name="slug" value="{{ old('slug',$row->slug) }}" placeholder="auto if blank">
          @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <label class="form-label">Config (JSON)</label>
          <textarea name="config" class="form-control" rows="6" placeholder='{"features":{"storage_gb":50,"bandwidth_gb":1000}}'>{{ old('config', json_encode($row->config ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)) }}</textarea>
          @error('config')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Included Add-ons (JSON)</label>
          <textarea name="included_addons" class="form-control" rows="6" placeholder='[{"add_on_id":1,"qty":1}]'>{{ old('included_addons', json_encode($row->included_addons ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)) }}</textarea>
          <small class="text-muted">Tip: You can also keep this blank and add later.</small>
          @error('included_addons')<small class="text-danger">{{ $message }}</small>@enderror

          <div class="mt-2">
            <div class="small text-muted">Active add-ons:</div>
            <ul class="small mb-0">
              @foreach($addons as $a)
                <li>{{ $a->label }} (<code>{{ $a->key }}</code>)</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-4">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order',$row->sort_order ?? 0) }}">
          @error('sort_order')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label">Featured?</label>
          <select name="is_featured" class="form-select">
            <option value="1" @selected(old('is_featured',$row->is_featured))>Yes</option>
            <option value="0" @selected(!old('is_featured',$row->is_featured))>No</option>
          </select>
          @error('is_featured')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-{{ isset($row->id)?'primary':'success' }}">{{ isset($row->id)?'Update':'Save' }}</button>
        <a href="{{ route('admin.presets.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
