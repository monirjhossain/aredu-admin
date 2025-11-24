@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Inquiry List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inquiry list</li>
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
                            <h3 class="card-title">inquiry List</h3>
                            <a href="{{ route('inquiries.create') }}" class="btn btn-primary">Create inquiry</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="overflow: scroll; ">
                        <table class="table table-hover mb-6">
                            <thead class="thead-light ">
                                <tr>
                                    <th style="width: 10px"> # </th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Country Preferred</th>
                                    <th>Last Education</th>
                                    <th>Course</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inquiries as $inquiry)
                                <tr>
                                    <td>{{ $inquiry->id }}</td>
                                    <td>{{ $inquiry->full_name }}
                                        @if ($inquiry->view==0)
                                            <span class="badge badge-danger">New</span>
                                        @endif
                                    </td>
                                    <td>{{ $inquiry->mobile_no }}</td>
                                    <td>{{ $inquiry->email }}</td>
                                    <td>{{ $inquiry->country }}</td>
                                    <td>{{ $inquiry->country_preferred }}</td>
                                    <td>{{ $inquiry->last_education }}</td>
                                    <td>{{ $inquiry->course }}</td>
                                    <td>{{ $inquiry->message }}</td>
                                    <td>
                                        <span class="badge {{ $inquiry->status == '1'? 'badge-primary': 'badge-danger' }}">{{ $inquiry->status == '1'? 'Active': 'Deactive' }} </span>
                                    </td>


                                    <td class="d-flex">
                                        <a href="{{ route('inquiries.edit', [$inquiry->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                        <form action="{{ route('inquiries.destroy', [$inquiry->id]) }}" class="mr-1" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="11">
                                    <h5 class="text-center">No Inquiries found.</h5>
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        {{ $inquiries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
