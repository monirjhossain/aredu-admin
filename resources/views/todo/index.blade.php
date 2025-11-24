@extends('layouts.default')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3> {{ $page_title }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-10">
                <form method="POST" action="{{ route('todo.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Add New To Do </label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Add New To Do">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="accordion" id="payment-posts">
                    @forelse ($todoLists as $item)
                        <div class="card mb-5 card-custom" id="mainTodoDiv{{ $item->id }}">
                            <div class="card-header" id="{{ $item->slug }}{{ $item->id }}">
                                <div class="input-group m-4" id="todoInput{{ $item->id }}" style="display:none;">
                                    <input type="text" id="todoValue{{ $item->id }}" class="form-control"
                                        value="{{ $item->name }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button"
                                            onclick="updateTodo('{{ $item->id }}')">Update</button>
                                    </div>
                                </div>
                                <a id="todoName{{ $item->id }}" class="card-title collapsed" href="#{{ $item->slug }}{{ $item->id }}"
                                    data-toggle="collapse" data-target="#{{ $item->slug }}{{ $item->id }}-collapse"
                                    {{ $loop->first ? 'aria-expanded="true"' : '' }}>
                                    <div class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="">
                                            <!-- Generator: Sketch 50.2 (55047) - http://www.bohemiancoding.com/sketch -->
                                            <title>Stockholm-icons / Navigation / Angle-double-right</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Stockholm-icons-/-Navigation-/-Angle-double-right" stroke="none"
                                                stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon id="Shape" points="0 0 24 0 24 24 0 24"></polygon>
                                                <path
                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                    id="Path-94" fill="#000000" fill-rule="nonzero"></path>
                                                <path
                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                    id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) ">
                                                </path>
                                            </g>
                                        </svg>
                                    </div>
                                    <div id="todoText{{ $item->id }}"
                                        class="card-label pl-4 font-weight-bold font-size-4">
                                        <span>
                                            {{ $item->name }}
                                        </span>
                                    </div>
                                </a>
                                <div class="card-toolbar mx-4 ">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="javascript:void(0)" id="cancelIcon{{ $item->id }}"
                                            class="btn btn-icon btn-bg-danger btn-active-color light btn-sm me-1 mr-1"
                                            style="display: none;" onclick="showTodoEditInput(0,'{{ $item->id }}')">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="la la-close text-white"></i>
                                            </span>
                                        </a>
                                        <a href="javascript:void(0)" id="editIcon{{ $item->id }}"
                                            class="btn btn-icon btn-bg-primary btn-active-color light btn-sm me-1 mr-1"
                                            onclick="showTodoEditInput(1,'{{ $item->id }}')">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="la la-pen text-white"></i>
                                            </span>
                                        </a>
                                        <a href="javascript:void(0)" id="deleteIcon{{ $item->id }}"
                                            onclick="deleteTodo('{{ $item->id }}')"
                                            class="btn btn-icon btn-bg-danger btn-active-color-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                        fill="white"></path>
                                                    <path opacity="0.5"
                                                        d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                        fill="white"></path>
                                                    <path opacity="0.5"
                                                        d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                        fill="white"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div id="{{ $item->slug }}{{ $item->id }}-collapse" class="collapse" data-parent="#payment-posts"
                                style="">
                                <div id="parentDivOfTask{{ $item->id }}"
                                    class="card-body py-2 pl-10 font-weight-normal font-size-3 text-dark-60">
                                    @forelse ($item->tasks as $task)
                                        <div class="col-md-12 ps-md-12 taskItem{{ $item->id }}" id="mainTaskDiv{{ $task->id }}">
                                            <div class="m-0">
                                                <div class="text-gray-600 fw-bold fs-6 ps-10 my-4">
                                                    <div class="input-group" id="taskInput{{ $task->id }}"
                                                        style="display:none;">
                                                        <textarea name="" id="taskNewValue{{ $task->id }}" class="form-control w-100">{{ $task->description }}</textarea>
                                                    </div>
                                                    <p id="taskDescription{{ $task->id }}">{{ $task->description }}
                                                    </p>
                                                </div>
                                                <div class="d-flex justify-content-left flex-shrink-0 my-4">
                                                    <a href="javascript:void(0)" id="saveIconTask{{ $task->id }}"
                                                        style="display:none;"
                                                        class="btn btn-icon btn-bg-success btn-active-color light btn-sm me-1 mr-1"
                                                        style="display: block;"
                                                        onclick="updateTask('{{ $task->id }}')">
                                                        <span class="svg-icon svg-icon-3">
                                                            <i class="la la-check text-white mt-2"></i>
                                                        </span>
                                                    </a>
                                                    <a href="javascript:void(0)" id="cancelIconTask{{ $task->id }}"
                                                        class="btn btn-icon btn-bg-danger btn-active-color light btn-sm me-1 mr-1"
                                                        style="display: none;"
                                                        onclick="showTaskEditInput(0, '{{ $task->id }}')">
                                                        <span class="svg-icon svg-icon-3">
                                                            <i class="la la-close text-white"></i>
                                                        </span>
                                                    </a>
                                                    <a href="javascript:void(0)" id="editIconTask{{ $task->id }}"
                                                        class="btn btn-icon btn-bg-primary btn-active-color light btn-sm me-1 mr-1"
                                                        onclick="showTaskEditInput(1, '{{ $task->id }}')">
                                                        <span class="svg-icon svg-icon-3">
                                                            <i class="la la-pen text-white"></i>
                                                        </span>
                                                    </a>
                                                    <a href="javascript:void(0)" id="deleteIconTask{{ $task->id }}"
                                                        onclick="deleteTask('{{ $task->id }}')"
                                                        class="btn btn-icon btn-bg-danger btn-active-color-primary btn-sm">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                                    fill="white"></path>
                                                                <path opacity="0.5"
                                                                    d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                                    fill="white"></path>
                                                                <path opacity="0.5"
                                                                    d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed"></div>
                                        </div>
                                    @empty
                                        No data available
                                        <div class="separator separator-dashed"></div>
                                    @endforelse
                                    <div class="col-md-12 ps-md-12 taskItem{{ $item->id }}">
                                        <div class="m-0">
                                            <div class="text-gray-600 fw-bold fs-6 ps-10">
                                                <div class="input-group my-4" id="taskInput" style="display:block;">
                                                    <textarea name="" rows="1" id="addNewTask{{ $item->id }}" class="form-control w-100"
                                                        placeholder="Add New Task"></textarea>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-left flex-shrink-0 my-4">
                                                <a href="javascript:void(0)" id="saveIconTask" style="display:block;"
                                                    class="btn btn-icon btn-bg-success btn-active-color light btn-sm me-1 mr-1"
                                                    style="display: block;" onclick="addTask('{{ $item->id }}')">
                                                    <span class="svg-icon svg-icon-3">
                                                        <i class="la la-check text-white mt-2"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        Data not available
                    @endforelse
                </div>
            </div>
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
        function showTodoEditInput(params, todo_id) {
            if (params == 1) {
                $('#todoInput' + todo_id).show(300);
                $('#todoName' + todo_id).hide(300);
                $('#editIcon' + todo_id).hide(300);
                $('#cancelIcon' + todo_id).show(300);
                $('#deleteIcon' + todo_id).hide(300);
            } else {
                $('#todoName' + todo_id).show(300);
                $('#cancelIcon' + todo_id).hide(300);
                $('#todoInput' + todo_id).hide(300);
                $('#editIcon' + todo_id).show(300);
                $('#deleteIcon' + todo_id).show(300);
            }
        }

        function showTaskEditInput(params, task_id) {
            if (params == 1) {
                $('#taskInput' + task_id).show(300);
                $('#taskDescription' + task_id).hide(300);
                $('#editIconTask' + task_id).hide(300);
                $('#cancelIconTask' + task_id).show(300);
                $('#deleteIconTask' + task_id).hide(300);
                $('#saveIconTask' + task_id).show(300);
            } else {
                $('#taskDescription' + task_id).show(300);
                $('#cancelIconTask' + task_id).hide(300);
                $('#taskInput' + task_id).hide(300);
                $('#editIconTask' + task_id).show(300);
                $('#deleteIconTask' + task_id).show(300);
                $('#saveIconTask' + task_id).hide(300);
            }
        }
        function checkValidation(params) {
            if(params == ''){
                toastr.error('Fill up the field', "Error");
                return true;
            }
            return false;
        }
        function updateTodo(params) {
            var name = $('#todoValue' + params).val();
            if(checkValidation(name)){
                return;
            }
            if(name == ''){
                toastr.error('Fill up the field', "Error");
                return;
            }
            $.ajax({
                url: '{{ url('/') }}/todo/' + params,
                method: 'PUT',
                data: {
                    'name': name
                },
                success: function(result) {
                    $('#todoText' + params).empty();
                    $('#todoText' + params).append("<span>" + name + "</span>");
                    toastr.success(result.message, "Success");
                    showTodoEditInput(0, params);
                },
                error: function(request, msg, error) {
                    // console.log(error);
                }
            });
        }

        function updateTask(params) {
            var description = $('#taskNewValue' + params).val();
            if(checkValidation(description)){
                return;
            }
            $.ajax({
                url: '{{ url('/') }}/task/' + params,
                method: 'PUT',
                data: {
                    'description': description
                },
                success: function(result) {
                    $('#taskDescription' + params).empty();
                    $('#taskDescription' + params).append(description);
                    toastr.success(result.message, "Success");
                    showTaskEditInput(0, params);
                },
                error: function(request, msg, error) {
                    // console.log(error);
                }
            });
        }

        function deleteTask(params) {
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
                        url: '{{ url('/') }}/task/' + params,
                        method: 'DELETE',
                        success: function(result) {
                            $('#mainTaskDiv' + params).hide();
                            toastr.success(result.message, "Success");
                        },
                        error: function(request, msg, error) {
                            // console.log(error);
                        }
                    });
                }
            });
        }

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

        function addTask(todo_id) {
            var description = $('textarea#addNewTask' + todo_id).val();
            if(checkValidation(description)){
                return;
            }
            $.ajax({
                url: '{{ url('/') }}/task',
                method: 'POST',
                data: {
                    'description': description,
                    'todo_id': todo_id
                },
                success: function(result) {
                    console.log(result.task);
                    var item = result.task;
                    var task = '';

                    task += '<div class="col-md-12 ps-md-12 taskItem' + todo_id + '" id="mainTaskDiv' + item.id + '">';
                    task += '<div class="m-0">';
                    task += '    <div class="text-gray-600 fw-bold fs-6 ps-10 my-4">';
                    task += '        <div class="input-group" id="taskInput' + item.id +'" style="display:none;">';
                    task += '            <textarea name="" id="taskNewValue' + item.id +'" class="form-control w-100">' + item.description + '</textarea>';
                    task += '        </div>';
                    task += '        <p id="taskDescription' + item.id + '">' + item.description + '</p>';
                    task += '    </div>';
                    task += '    <div class="d-flex justify-content-left flex-shrink-0 my-4">';
                    task += '        <a href="javascript:void(0)" id="saveIconTask' +item.id + '" style="display:none;"';
                    task += '            class="btn btn-icon btn-bg-success btn-active-color light btn-sm me-1 mr-1"';
                    task += '            style="display: block;" onclick="updateTask(' +item.id + ')">';
                    task += '            <span class="svg-icon svg-icon-3">';
                    task += '                <i class="la la-check text-white mt-2"></i>';
                    task += '            </span>';
                    task += '        </a>';
                    task += '        <a href="javascript:void(0)" id="cancelIconTask' +item.id + '"';
                    task += '            class="btn btn-icon btn-bg-danger btn-active-color light btn-sm me-1 mr-1"';
                    task += '            style="display: none;" onclick="showTaskEditInput(0,' +item.id + ')">';
                    task += '            <span class="svg-icon svg-icon-3">';
                    task += '                <i class="la la-close text-white"></i>';
                    task += '            </span>';
                    task += '        </a>';
                    task += '        <a href="javascript:void(0)" id="editIconTask' +item.id + '"';
                    task += '            class="btn btn-icon btn-bg-primary btn-active-color light btn-sm me-1 mr-1"';
                    task += '            onclick="showTaskEditInput(1,' + item.id +')">';
                    task += '            <span class="svg-icon svg-icon-3">';
                    task += '                <i class="la la-pen text-white"></i>';
                    task += '            </span>';
                    task += '        </a>';
                    task += '        <a href="javascript:void(0)" id="deleteIconTask' +item.id + '" onclick="deleteTask(' + item.id +')" class="btn btn-icon btn-bg-danger btn-active-color-primary btn-sm">';
                    task += '            <span class="svg-icon svg-icon-3">';
                    task += '                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"';
                    task += '                    viewBox="0 0 24 24" fill="none">';
                    task += '                    <path';
                    task += 'd="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"';
                    task += 'fill="white"></path>';
                    task += '                    <path opacity="0.5"';
                    task += 'd="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"';
                    task += 'fill="white"></path>';
                    task += '                    <path opacity="0.5"';
                    task += 'd="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"';
                    task += 'fill="white"></path>';
                    task += '                </svg>';
                    task += '            </span>';
                    task += '        </a>';
                    task += '    </div>';
                    task += '</div>';
                    task += '<div class="separator separator-dashed"></div>';
                    task += '</div>';

                    var count = $('#parentDivOfTask' + todo_id).children().length;
                    $('#parentDivOfTask' + todo_id + ' .taskItem' + todo_id + ':last').before(task);
                    toastr.success(result.message, "Success");
                    $('textarea#addNewTask' + todo_id).val('');
                },
                error: function(request, msg, error) {
                    // console.log(error);
                }
            });
        }
    </script>
@endsection
