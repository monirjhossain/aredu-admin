@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h2>Branch Management</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('branches.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                    <i class="la la-plus"></i>Add New Branch
                </a>
            </div>
        </div>
        <div class="card-body" style="overflow: scroll; ">
            <table class="table table-hover mb-6 font-size-h6">
                <thead class="thead-light ">
                    <tr>
                        <th scope="col" width="30">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branches as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->address }}</td>
                            <td>
                                <span class="label label-inline label-light-primary font-weight-bold">Enable</span>
                            </td>
                            <td>
                                <form id="deleteForm" action="{{ route('branches.destroy', $row->id) }}" method="POST">
                                <a href="{{ route('branches.show', $row->id) }}" class="btn btn-primary btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Details</a>
                                <a href="{{ route('branches.edit', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button id="submitButton" type="button" class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1 pb-1 mb-1">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $branches->links() !!}
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
