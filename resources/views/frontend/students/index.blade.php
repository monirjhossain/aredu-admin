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
                    <div class="card-body" style="overflow: scroll; ">
                        <table class="table table-hover">
                            <thead class="thead-light ">
                                <tr>
                                    <th style="width: 10px"> # </th>
                                    <th>Name</th>
                                    <th>passport</th>
                                    <th>Study Level</th>
                                    <th>Subject</th>
                                    <th class="min-w-300px">Student Details</th>
                                    <th class="min-w-300px">Student Parents</th>
                                    <th>Responsible</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->full_name }}</td>
                                    <td>{{ $item->passport }}</td>
                                    <td>{{ $item->study_level }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>
                                        <span><b>Email:</b> {{ $item->email }}</span><br>
                                        <span><b>Phone:</b> {{ $item->mobile_no }}</span><br>
                                        <span><b>Address:</b> {{ $item->address }}</span><br>
                                        <span><b>City:</b> {{ $item->city }}</span><br>
                                        <span><b>Country:</b> {{ $item->country }}</span><br>
                                        <span><b>ZIP:</b> {{ $item->zip }}</span><br>
                                    </td>
                                    <td>
                                        <span><b>Father:</b> {{ $item->father_name }}</span><br>
                                        <span><b>Phone:</b> {{ $item->father_mobile_no }}</span><br>
                                        <span><b>Occupation:</b> {{ $item->father_occoupation }}</span><br>
                                        <span><b>mother:</b> {{ $item->mother_name }}</span><br>
                                        <span><b>Phone:</b> {{ $item->mother_mobile_no }}</span><br>
                                        <span><b>Occupation:</b> {{ $item->mother_occoupation }}</span><br>
                                    </td>
                                    <td>{{ $item->responsible_person }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == '1'? 'badge-primary': 'badge-danger' }}">{{ $item->status == '1'? 'Active': 'Deactive' }} </span>
                                    </td>


                                    <td class="d-flex">
                                        <a href="{{ route($url.'.edit', [$item->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                        <form id="deleteForm" action="{{ route($url.'.destroy', [$item->id]) }}" class="mr-1" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="submitButton" type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="11">
                                    <h5 class="text-center">No student found.</h5>
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
    <script>

        $("#submitButton").click(function(event){
            Swal.fire({
                title: "Warning!",
                text: "Are you want to delete?",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "<i class='la la-check'></i> Yes",
                showCancelButton: true,
                cancelButtonText: "<i class='la la-close'></i> No",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-success"
                }
            }).then(function(result) {
                if(result.isConfirmed){
                    $("#deleteForm").submit();
                }
            });

        });
@endsection
