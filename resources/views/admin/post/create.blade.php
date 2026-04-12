@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{$title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($url.'.index') }}">{{$type}} list</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="">{{$title}}</h3>
                            <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{$type}} List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route($url.'.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="post_type" value="{{$type}}">
                                    <div class="card-body">
                                        @include('includes.errors')
                                        <div class="form-group">
                                            <label for="post_title">Title</label>
                                            <input type="text" name="post_title" value="{{ old('post_title') }}" class="form-control" placeholder="Enter title">
                                        </div>
                                        @if ( $type == 'university')
                                        <div class="form-group">
                                            <label for="link">Application Portal Link</label>
                                            <input type="text" name="meta[link]" value="{{ old('link') }}" class="form-control" placeholder="Enter">
                                        </div>
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input type="text" name="meta[user]" value="{{ old('user') }}" class="form-control" placeholder="Enter">
                                        </div><div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="text" name="meta[password]" value="{{ old('password') }}" class="form-control" placeholder="Enter">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="categories">Subjects / Major</label>
                                            <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                                                @foreach($subjects as $subject)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="subjects[]" type="checkbox" id="subject{{ $subject->id}}" value="{{ $subject->id }}">
                                                    <label for="subject{{ $subject->id}}" class="custom-control-label">{{ $subject->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="control-label" for="categories">Categories</label>
                                            <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                                                @foreach($categories as $category)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="categories[]" type="checkbox" id="category{{ $category->id}}" value="{{ $category->id }}">
                                                    <label for="category{{ $category->id}}" class="custom-control-label">{{ $category->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Tags</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($tags as $tag)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="tags[]" type="checkbox" id="tag{{ $tag->id}}" value="{{ $tag->id }}">
                                                    <label for="tag{{ $tag->id}}" class="custom-control-label">{{ $tag->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea name="post_content" id="kt_docs_tinymce_basic" rows="4" class="form-control"
                                                placeholder="Enter description">{{ old('post_content') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="video">Status</Small></label>
                                            <select class="form-control" name="status" id="">
                                                <option value="1">Active</option>
                                                <option value="0">Deactive</option>
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="video">Video Link (Optional) <Small>(IF post type video than mandatory with embed)</Small></label>
                                            <input type="text" name="video" value="{{ old('video') }}" class="form-control" placeholder="https://www.youtube.com/embed/67Pbeq_TUKU">
                                        </div> --}}
                                        <div class="tile-body">
                                            <div class="row">
                                                <div class="col-3">
                                                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="fimage" style="width: 80px; height: auto;">
                                                </div>
                                                <div class="col-9">
                                                    <div class="form-group">
                                                        <label class="control-label">Featured Image</label>
                                                        <input value="{{ old('image') }}" class="form-control @error('image') is-invalid @enderror" type="file" name="image" onchange="loadFile(event,'fimage')"/>
                                                        @error('image')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @push('scripts')
                                        <script>
                                            loadFile = function(event, id) {
                                                var output = document.getElementById(id);
                                                output.src = URL.createObjectURL(event.target.files[0]);
                                            };
                                        </script>
                                        @endpush
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/admin/css/summernote-bs4.min.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
<script>
    var options = {selector: "#kt_docs_tinymce_basic", height : "480"};
    tinymce.init(options);
</script>
@endsection
