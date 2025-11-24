@extends('layouts.default')

@section('content')
<form action="{{ route('user-management.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{$user->id}}">
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h2>{{ $page_title }}</h2>

                </div>
                <div class="card-toolbar">
                    <a href="{{ url('user-management') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-list"></i>Go To User List
                    </a>
                </div>
            </div>
            @include('user.form', ['student' => ''])
            {{-- <div class="card-body card-block">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="name" class=" form-control-label">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Full Name"
                                class="form-control form-control-sm"value="{{ $user->name }}">
                            <span style="color: red">
                                {{ $errors->first('name') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="username" class=" form-control-label">Username <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" placeholder="Username" class="form-control form-control-sm" value="{{ $user->username }}">
                            <span style="color: red">
                                {{ $errors->first('username') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="mobile_no" class=" form-control-label">Phone no </label>
                            <input type="text" name="mobile_no" id="mobile_no" placeholder="Phone no" class="form-control form-control-sm" value="{{ $user->mobile_no }}">
                            <span style="color: red">
                                {{ $errors->first('mobile_no') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" placeholder="" value="{{ $user->email }}" />
                            <span style="color: red">
                                {{ $errors->first('email') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-lg-4">
                        <div class="form-group">
                            <label for="password" class=" form-control-label">Password <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="password" id="password" placeholder="password"
                                class="form-control">
                            <span style="color: red">
                                {{ $errors->first('password') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <div class="form-group row">
                            <div class="col-lg-6 mb-5">
                                <label class=" form-control-label">Profile Picture<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <label></label>
                                    <div></div>
                                    <div class="custom-file">
                                        <input type="file" name="pro_pic" class="custom-file-input" id="customFile" />
                                        <label class="custom-file-label" for="customFile">Select File</label>
                                        <span style="color: red">
                                            {{ $errors->first('pro_pic') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <button type="submit" class="btn btn-success mr-2">Save</button>
                    </div>
                </div>
            </div> --}}
        </div>
        <!--end::Card-->
    </form>
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
    <!--end::Page Scripts-->
@endsection
