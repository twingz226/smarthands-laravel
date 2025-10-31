@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-sm-10 col-sm-offset-1">
    <h2>Edit Homepage Banner</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">Update Homepage Banner</div>
      </div>
      <div class="panel-body">
        <form method="POST" action="{{ route('admin.hero_media.update', $item->id) }}">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" name="title" value="{{ old('title', $item->title) }}" placeholder="Optional title">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Media Type</label>
                <select class="form-control" name="media_type" required>
                  <option value="video" {{ old('media_type', $item->media_type) === 'video' ? 'selected' : '' }}>Video</option>
                  <option value="image" {{ old('media_type', $item->media_type) === 'image' ? 'selected' : '' }}>Image</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Optional description">{{ old('description', $item->description) }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Media Path or URL</label>
                <input type="text" class="form-control" name="media_path" value="{{ old('media_path', $item->media_path) }}" placeholder="E.g., clean.mp4 or https://..." required>
                <p class="help-block small">Use a full URL or a public path relative to /public (e.g., videos/hero.mp4).</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Poster Image (optional)</label>
                <input type="text" class="form-control" name="poster_image" value="{{ old('poster_image', $item->poster_image) }}" placeholder="E.g., images/hero-poster.jpg or https://...">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Display Order</label>
                <input type="number" min="0" class="form-control" name="display_order" value="{{ old('display_order', $item->display_order) }}">
                <p class="help-block small">Lower numbers appear first.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Active</label>
                <div>
                  <label class="checkbox-inline">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}> Active
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="text-right">
            <a href="{{ route('admin.hero_media.index') }}" class="btn btn-default">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
