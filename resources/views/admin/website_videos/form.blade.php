@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $video->exists ? 'Edit video' : 'Add video' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('video-gallery.index') }}">Video gallery</a></li>
                    <li class="breadcrumb-item active">{{ $video->exists ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <form method="POST" action="{{ $video->exists ? route('video-gallery.update', $video) : route('video-gallery.store') }}">
                @csrf
                @if($video->exists)
                    @method('PUT')
                @endif
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label>YouTube link</label>
                        <input type="text" name="youtube_url" class="form-control" value="{{ old('youtube_url', $video->youtube_url) }}" required maxlength="500" placeholder="https://www.youtube.com/shorts/... or https://www.youtube.com/watch?v=...">
                        <small class="form-text text-muted">Shorts or watch link. The site reads the video id from it.</small>
                    </div>
                    <div class="form-group">
                        <label>Sort</label>
                        <input type="number" name="sort" class="form-control" value="{{ old('sort', $video->sort) }}" min="0" max="9999">
                        <small class="form-text text-muted">Lower numbers show first. The first active video is the large reel.</small>
                    </div>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            @php
                                $active = old('is_active', $video->is_active);
                                $checked = $active === true || $active === 1 || $active === '1';
                            @endphp
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $checked ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Show on the website</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('video-gallery.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
