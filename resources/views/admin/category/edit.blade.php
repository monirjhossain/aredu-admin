@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Category</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category list</a></li>
                    <li class="breadcrumb-item active">Edit Category</li>
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
                            <h3 class="card-title">Edit Category - {{ $category->name }}</h3>
                            <a href="{{ route('category.index') }}" class="btn btn-primary">Go Back to Category List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                                <form action="{{ route('category.update', [$category->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="control-label" for="name">Name <span class="m-l-5 text-danger"> *</span></label>
                                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', $category->name) }}"/>
                                            <input type="hidden" name="id" value="{{ $category->id }}">
                                            @error('name') {{ $message }} @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="slug">slug <span class="m-l-5 text-danger"> *</span></label>
                                            <input class="form-control @error('slug') is-invalid @enderror" type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"/>
                                            @error('slug') {{ $message }} @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="description">Description</label>
                                            <textarea class="form-control" rows="4" name="description" id="description">{{ old('description', $category->description) }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="parent">Parent Category <span class="m-l-5 text-danger"> *</span></label>
                                            <select id=parent class="form-control custom-select mt-15 @error('parent_id') is-invalid @enderror" name="parent_id">
                                                <option value="0">Select a parent category</option>
                                                @foreach($categories as $cat)
                                                    @if ($category->parent_id == $cat->id)
                                                        <option value="{{ $cat->id }}" selected> {{ $cat->name }} </option>
                                                    @else
                                                        <option value="{{ $cat->id }}"> {{ $cat->name }} </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('parent_id') {{ $message }} @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                                    {{ $category->featured == 1 ? 'checked' : '' }}
                                                    />Featured Category
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" id="menu" name="menu"
                                                    {{ $category->menu == 1 ? 'checked' : '' }}
                                                    />Show in Menu
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    @if ($category->image != null)
                                                        <figure class="mt-2" style="width: 80px; height: auto;">
                                                            <img src="{{ asset('storage/'.$category->image) }}" id="categoryImage" class="img-fluid" alt="img">
                                                        </figure>
                                                    @endif
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="control-label">Category Image</label>
                                                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image"/>
                                                    @error('image') {{ $message }} @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Category</button>
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
