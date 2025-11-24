@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="">{{ $title }}</h3>
                <a href="{{ route($url . '.index') }}" class="btn btn-primary">Go Back to {{ $type }} List</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route($url . '.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="post_type" value="{{ $type }}">
                        <div class="card-body">
                            @include('includes.errors')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="Enter Name">
                            </div>
                            @if ($type == 'SMS Template')
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="title" class=" form-control-label">Message <span class="text-danger">*</span></label>
                                            <textarea id="kt_docs_tinymce_basic" id="content" name="message" class="tox-target"></textarea>
                                            {{-- <textarea value="{{ old('content') }}" type="text" id="content" name="content" placeholder="content" class="form-control form-control-sm"></textarea> --}}
                                            <span style="color: red"> {{ $errors->first('message') }} </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($type == 'product')
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" value="{{ old('price') }}" class="form-control"
                                    placeholder="Enter price">
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                        </div>
                    </form>
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
