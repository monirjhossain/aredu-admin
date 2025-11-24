@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
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

<!-- Main content -->
<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
            <form id="searchForm" action="{{ route('students.index') }}" method="get">
                <input type="text" name="search" placeholder="Search" class="form-control"
                    value="{{ $request->search }}">
            </form>
        </div>
        <div class="card-toolbar">
            <a onclick="submitDetailsForm()" class="btn btn-primary mr-1"> <i class="la la-search"></i>Search </a>
            <a href="{{ route($url . '.create') }}" class="btn btn-primary"> <i class="la la-plus"></i> Create
                {{ $type }}</a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="overflow: scroll; ">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    @if ($type == 'SMS Template')
                    <th>Message</th>
                    @endif
                    {{-- <th>Price</th> --}}
                    <th style="width: 40px">Action</th>
                </tr>
            </thead>
            <tbody>
                @if($data->count())
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        @if($type == 'role')
                        <td>{{ $item->user_type  ?? '' }}</td>
                        @else
                        <td>{{ $item->name  ?? '' }}</td>
                        @endif
                        {{-- <td>{{ $item->price ?? '' }}</td> --}}
                        @if ($type == 'SMS Template')
                        <th>{!!$item->message!!}</th>
                        @endif
                        <td class="d-flex">
                            <a href="{{ route($url.'.edit', [$item->id]) }}" class="btn btn-sm btn-primary mr-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route($url.'.destroy', [$item->id]) }}" class="mr-1" method="POST">
                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="post_type" value="{{ $type }}">
                                <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <h5 class="text-center">No {{$type}} found.</h5>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer d-flex justify-content-center">
        {{ $data->links() }}
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
            </div>
        </div>
    </div>
</div>
@endsection
