@csrf
<div class="mb-3">
  <label class="form-label">Title <span class="text-danger">*</span></label>
  <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
</div>

<div class="mb-3">
  <label class="form-label">Description <span class="text-danger">*</span></label>
  <textarea name="description" class="form-control" rows="6" required>{{ old('description', $announcement->description) }}</textarea>
</div>

<div class="mb-3">
  <label class="form-label">Publish Date/Time</label>
  <input type="datetime-local" name="published_at" class="form-control"
         value="{{ old('published_at', optional($announcement->published_at)->format('Y-m-d\\TH:i')) }}">
  <small class="text-muted">Leave blank to keep unpublished.</small>
</div>

<div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary">{{ $submitLabel ?? 'Save' }}</button>
  <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
