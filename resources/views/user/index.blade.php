@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h2> User Management</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('user-management.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                    <i class="la la-plus"></i>Add New User
                </a>
            </div>
        </div>
        <div class="card-body" style="overflow: scroll; ">
            <table class="table table-hover mb-6 font-size-h6">
                <thead class="thead-light ">
                    <tr>
                        <th scope="col" width="30">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Role Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $row)
                        <tr>
                            <th scope="row" class="tg-bn">
                                @if ($row->profile_pic != null)
                                    <div class="symbol symbol-35px symbol-circle" style="width:50px;">
                                        <img alt="Pic" src="{{ asset('/uploads/profile/' . $row->profile_pic) }}"
                                            width="200">
                                    </div>
                                @else
                                    @php
                                        $str = $row->name;
                                    @endphp
                                    <div class="symbol symbol-35px symbol-circle text-capitalize">
                                        <span
                                            class="symbol-label bg-danger text-light fw-bold h3">{{ substr($str, 0, 1) }}</span>
                                    </div>
                                @endif
                            </th>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->role ? $row->role->user_type : '-' }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->email }}</td>
                            <td>
                                <span class="label label-inline label-light-primary font-weight-bold">Enable</span>
                            </td>
                            <td>
                                <form id="deleteForm" action="{{ route('user-management.destroy', $row->id) }}" method="POST">
                                <a href="{{ route('user-management.show', $row->id) }}" class="btn btn-primary btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Details</a>
                                <a href="{{ route('user-management.edit', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button id="submitButton" type="button" class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
        </div>
    </div>
@endsection
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
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
        function deleteTodo(params) {
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
                    $.ajax({
                        url: '{{ url('/') }}/todo/' + params,
                        method: 'DELETE',
                        success: function(result) {
                            $('#mainTodoDiv' + params).hide(300);
                            toastr.success(result.message, "Success");
                        },
                        error: function(request, msg, error) {
                            console.log(error);
                        }
                    });
                }
            });
        }
    </script>
@endsection
