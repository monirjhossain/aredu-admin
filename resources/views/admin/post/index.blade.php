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
                            <h3 class="card-title">{{$title}}</h3>
                            <a href="{{ route($url.'.create') }}" class="btn btn-primary">Create {{$type}}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card card-custom">
                        <div class="card-header flex-wrap py-5">
                            <form id="searchForm" action="{{ route($url.'.index') }}" method="get">
                                <div class="card-title">
                                    <input type="text" name="search" placeholder="Search" class="form-control" value="{{ $request->search }}">
                                    @if ($type == 'university')
                                        <select class="form-control" name="subject" id="">
                                            <option value="">--- Select Subject ---</option>
                                            @foreach ($subjects as $item)
                                            <option value="{{$item->id }}" {{ $request->subject == $item->id ? 'selected' : ''}} >{{$item->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <input id="print" type="hidden" name="print" value="no">
                                </div>
                            </form>
                            <div class="card-toolbar">
                                <a onclick="submitDetailsForm('no')" class="btn btn-primary mr-1"> <i class="la la-search"></i>Search </a>
                                <a onclick="submitDetailsForm('yes')" class="btn btn-primary mr-1"> <i class="la la-print"></i>Print </a>
                                <a href="{{ route($url . '.create') }}" class="btn btn-primary mr-1"> <i class="la la-plus"></i> Create
                                    {{ $type }}</a>
                                {{-- <a href="#" linkUrl="{{ route('student.sendBulkSms') }}" class="btn btn-primary sms_send mr-1"><i class="fas fa-envelope" title="Invoice List"></i>
                                </a> --}}

                                <!-- Button trigger modal-->
                                <button onclick="getSmsTemplate(0)" type="button" class="btn btn-primary messageModal" data-toggle="modal" data-target="#exampleModalLong">
                                    <i class="fas fa-envelope" title="message"></i>
                                </button>

                            </div>
                        </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    @if ($type == 'university')
                                    <th>subjects</th>
                                    @endif
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th style="width: 130px">Created Date</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($posts->count())
                                @foreach ($posts as $key => $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            <div style="max-width: 80px; max-height:80px;overflow:hidden">
                                                <img src="{{ asset($post->image) }}" class="img-fluid img-rounded" alt="">
                                                <a class="btn btn-sm btn-success mt-1" href="{{ asset($post->image) }}" download>Download</a>
                                            </div>
                                        </td>
                                        <td>{{ $post->post_title }}</td>
                                        @if ($type == 'university')
                                        <td>
                                            @foreach($post->subjects as $sub)
                                            <span class="badge badge-primary">{{ $sub->name }} </span>
                                            @endforeach
                                        </td>
                                        @endif
                                        <td>
                                            @foreach($post->categories as $cat)
                                                <span class="badge badge-primary">{{ $cat->name }} </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $post->user->name ?? '-' }}</td>
                                        <td style="width: 130px">{{ $post->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <span class="badge {{ $post->status == '1'? 'badge-primary': 'badge-danger' }}">{{ $post->status == '1'? 'Active': 'Deactive' }} </span>
                                        </td>
                                        <td class="d-flex">

                                            <a href="{{ route($url.'.show', [$post->id]) }}" class="btn btn-sm btn-success mr-1">
                                                 <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route($url.'.edit', [$post->id]) }}" class="btn btn-sm btn-primary mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route($url.'.show', [$post->id]) }}" target="_blank" class="btn btn-sm btn-dark mr-1">
                                                 <i class="fas fa-link"></i>
                                            </a>

                                            <form action="{{ route($url.'.destroy', [$post->id]) }}" class="mr-1" method="POST">
                                                @method('DELETE')
                                                @csrf
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
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function submitDetailsForm(type) {
        if(type == 'yes'){
            $('#searchForm #print').val('yes');
        }else{
            $('#searchForm #print').val('no');
        }
        $("#searchForm").submit();
    }
    </script>
@endsection
