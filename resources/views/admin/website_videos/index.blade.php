@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Video gallery</h1>
                <div class="text-muted mt-1">YouTube Shorts and watch links shown on the website homepage.</div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Video gallery</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Videos</h3>
                    <a href="{{ route('video-gallery.create') }}" class="btn btn-primary">Add video</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px">Sort</th>
                            <th>YouTube</th>
                            <th style="width: 90px">Status</th>
                            <th style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td>{{ $video->sort }}</td>
                                <td>
                                    <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener">{{ $video->youtube_url }}</a>
                                </td>
                                <td>
                                    @if($video->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td class="d-flex">
                                    <a href="{{ route('video-gallery.edit', $video) }}" class="btn btn-sm btn-primary mr-1"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('video-gallery.destroy', $video) }}" method="POST" onsubmit="return confirm('Remove this video?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">No videos yet. The homepage keeps its sample reels until you add one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
