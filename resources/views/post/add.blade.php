@extends('layouts.default')

@section('content')
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h2>{{ $page_title }}</h2>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-list"></i>Go To post List
                    </a>
                </div>
            </div>
            <div class="card-body card-block">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="title" class=" form-control-label">Title <span class="text-danger">*</span></label>
                            <input value="{{ old('post_title') }}" type="text" id="title" name="post_title" placeholder="Title" class="form-control form-control-sm">
                            <span style="color: red"> {{ $errors->first('post_title') }} </span>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="title" class=" form-control-label">Content <span class="text-danger">*</span></label>
                            <textarea id="kt_docs_tinymce_basic" id="content" name="post_content" class="tox-target"></textarea>
                            {{-- <textarea value="{{ old('content') }}" type="text" id="content" name="content" placeholder="content" class="form-control form-control-sm"></textarea> --}}
                            <span style="color: red"> {{ $errors->first('post_content') }} </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="category" class=" form-control-label">Category<span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="category" id="category">
                                <option value="">12</option>
                                <option value="">12</option>
                                <option value="">12</option>
                            </select>
                            <span style="color: red"> {{ $errors->first('category') }} </span>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="tag" class=" form-control-label">Tag<span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="tag" id="tag">
                                <option value="">12</option>
                                <option value="">12</option>
                                <option value="">12</option>
                            </select>
                            <span style="color: red"> {{ $errors->first('tag') }} </span>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <div class="form-group row">
                            <div class="col-lg-6 mb-5">
                                <label class=" form-control-label">Fetured Image<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <label></label>
                                    <div></div>
                                    <div class="custom-file">
                                        <input type="file" name="pro_pic" class="custom-file-input" id="customFile" />
                                        <label class="custom-file-label" for="customFile">Select File</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <button type="submit" class="btn btn-success mr-2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
<script>
    var options = {selector: "#kt_docs_tinymce_basic", height : "480"};
    tinymce.init(options);
</script>
@endsection
