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
                            <h3 class="card-title">{{$title}} - {{ $post->post_name }}</h3>
                            <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{$type}} List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12 col-lg-8 offset-lg-2 col-md-8 offset-md-2">
                                <div class="card-body">
                                    <form action="{{ route($url.'.update', [$post->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="post_type" value="{{$type}}">
                                        @include('includes.errors')
                                        <div class="form-group">
                                            <label for="post_title">Title</label>
                                            <input type="name" name="post_title" value="{{ $post->post_title }}" class="form-control" placeholder="Enter title">
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="category">Post Category</label>

                                            <select name="category" id="category" class="form-control">
                                                <option value="" style="display: none" selected>Select Category</option>
                                                @foreach($categories as $c)
                                                <option value="{{ $c->id }}" @if($post->category_id == $c->id) selected @endif> {{ $c->name }} </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        {{-- <div class="form-group">
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="image">Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="image">
                                                        <label class="custom-file-label" for="image">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div style="max-width: 100px; max-height: 100px;overflow:hidden; margin-left: auto">
                                                        <img src="{{ asset($post->image) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        @if ( $type == 'university')
                                        <div class="form-group">
                                            <label for="link">Application Portal Link</label>
                                            <input type="text" name="meta[link]" value="{{ $post->getMeta('link') }}" class="form-control" placeholder="Enter">
                                        </div>
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input type="text" name="meta[user]" value="{{ old('meta.user', $post->getMeta('user')) }}" class="form-control" placeholder="Enter">
                                        </div><div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="text" name="meta[password]" value="{{ old('meta.password', $post->getMeta('password')) }}" class="form-control" placeholder="Enter">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="subjects">Subject / Majors</label>
                                            <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                                                @foreach($subjects as $subject)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="subjects[]" type="checkbox" id="subject{{ $subject->id}}" value="{{ $subject->id }}" @foreach($post->subjects as $sub) @if($subject->id == $sub->id) checked @endif @endforeach>
                                                    <label for="subject{{ $subject->id}}" class="custom-control-label">{{ $subject->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        @if ($type == 'scholarship')
                                        <div class="form-group">
                                            <label>Short description</label>
                                            <textarea name="post_excerpt" rows="3" class="form-control">{{ old('post_excerpt', $post->post_excerpt) }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Coverage</label>
                                            <select name="meta[coverage]" class="form-control">
                                                @foreach(['Full','Partial','Full tuition','Varies'] as $cov)
                                                    <option value="{{ $cov }}" @if(old('meta.coverage', $post->getMeta('coverage'))==$cov) selected @endif>{{ $cov }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Universities count (approx)</label>
                                            <input type="number" min="0" name="meta[universities]" value="{{ old('meta.universities', $post->getMeta('universities', 0)) }}" class="form-control">
                                        </div>
                                        @endif
                                        @if ($type == 'service')
                                        <div class="form-group">
                                            <label>Short summary</label>
                                            <textarea name="post_excerpt" rows="2" class="form-control">{{ old('post_excerpt', $post->post_excerpt) }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Icon class (Tabler)</label>
                                            <input type="text" name="meta[icon]" value="{{ old('meta.icon', $post->getMeta('icon', 'ti-briefcase')) }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Accent color</label>
                                            <input type="text" name="meta[color]" value="{{ old('meta.color', $post->getMeta('color', '#E11D2E')) }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Background color</label>
                                            <input type="text" name="meta[bg]" value="{{ old('meta.bg', $post->getMeta('bg', '#FCE8EA')) }}" class="form-control">
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="control-label" for="categories">Categories @if($type=='university')(Cities / Provinces)@endif</label>
                                            <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                                                @foreach($categories as $category)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="categories[]" type="checkbox" id="category{{ $category->id}}" value="{{ $category->id }}" @foreach($post->categories as $cat) @if($category->id == $cat->id) checked @endif @endforeach>
                                                    <label for="category{{ $category->id}}" class="custom-control-label">{{ $category->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label> Tags</label>
                                            <div class=" d-flex flex-wrap">
                                                @foreach($tags as $tag)
                                                <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                    <input class="custom-control-input" name="tags[]" type="checkbox" id="tag{{ $tag->id}}" value="{{ $tag->id }}"
                                                    @foreach($post->tags as $t)
                                                        @if($tag->id == $t->id) checked @endif
                                                    @endforeach
                                                    >
                                                    <label for="tag{{ $tag->id}}" class="custom-control-label">{{ $tag->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <textarea name="post_content" id="kt_docs_tinymce_basic" rows="4" class="form-control"
                                                placeholder="Enter description">{{ $post->post_content }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="video">Status</Small></label>
                                            <select class="form-control" name="status" id="">
                                                <option value="1" {{ $post->status == '1'? 'selected': '' }}>Active</option>
                                                <option value="0" {{ $post->status == '0'? 'selected': '' }}>Deactive</option>
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="video">Video Link (Optional) <Small>(IF post type video than mandatory)</Small></label>
                                            <input type="name" name="video" value="{{ $post->video }}" class="form-control" placeholder="https://www.youtube.com/watch?v=SOlHkPrJBOU">
                                        </div> --}}
                                        <div class="tile-body">
                                            <div class="row">
                                                <div class="col-3">
                                                        <img src="{{ asset($post->image) }}" id="postImg" style="width: 80px; height: auto;">
                                                </div>
                                                <div class="col-9">
                                                    <div class="form-group">
                                                        <label class="control-label">Featured Image</label>
                                                        <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" onchange="loadFile(event,'postImg')"/>
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
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
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
