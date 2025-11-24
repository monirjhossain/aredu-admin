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
                    <li class="breadcrumb-item"><a href="{{ route($url.'.index') }}">{{$type}} list</a></li>
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
                            <h3 class="">{{$title}}</h3>
                            <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{$type}} List</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route($url.'.update', [$student->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        @include('includes.errors')
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="study_level">Study Level</label>
                                                {{-- <input type="text" name="study_level" value="{{ $student->study_level }}" class="form-control" placeholder="Type here"> --}}
                                                    <select name="study_level" class="form-control"  id="">
                                                        <option {{$student->study_level == 'BSC' ? 'selected': ''}}>BSC</option>
                                                        <option {{$student->study_level == 'MSC' ? 'selected': ''}}>MSC</option>
                                                        <option {{$student->study_level == 'PHD' ? 'selected': ''}}>PHD</option>
                                                        <option {{$student->study_level == 'Diploma' ? 'selected': ''}}>Diploma</option>
                                                        <option {{$student->study_level == 'Pharmacy' ? 'selected': ''}}>Pharmacy</option>
                                                        <option {{$student->study_level == 'MBBS' ? 'selected': ''}}>MBBS</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="subject">Subject</label>
                                                <input type="text" name="subject" value="{{ $student->subject }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="preferred_country">Preferred Country</label>
                                                    <select name="preferred_country" class="form-control"  id="">
                                                        <option {{$student->preferred_country == 'China' ? 'selected': ''}}>China</option>
                                                        <option {{$student->preferred_country == 'Spain' ? 'selected': ''}}>Spain</option>
                                                        <option {{$student->preferred_country == 'Japan' ? 'selected': ''}}>Japan</option>
                                                        <option {{$student->preferred_country == 'India' ? 'selected': ''}}>India</option>
                                                        <option {{$student->preferred_country == 'Pakistan' ? 'selected': ''}}>Pakistan</option>
                                                        <option {{$student->preferred_country == 'Nepal' ? 'selected': ''}}>Nepal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_education">Last Education</label>
                                                <input type="text" name="last_education" value="{{ $student->last_education }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <h4>Student Info</h4><hr>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" name="first_name" value="{{ $student->first_name }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" name="last_name" value="{{ $student->last_name }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" name="email" value="{{ $student->email }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="passport">Passport</label>
                                                    <input type="text" name="passport" value="{{ $student->passport }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="mobile_no">Phone No</label>
                                                    <input type="text" name="mobile_no" value="{{ $student->mobile_no }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" name="address" value="{{ $student->address }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" name="city" value="{{ $student->city }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    {{-- <input type="text" name="country" value="{{ $student->country }}" class="form-control" placeholder="Type here"> --}}
                                                    <select name="country" class="form-control"  id="">
                                                        <option {{$student->country == 'Bangladesh' ? 'selected': ''}}>Bangladesh</option>
                                                        <option {{$student->country == 'India' ? 'selected': ''}}>India</option>
                                                        <option {{$student->country == 'Pakistan' ? 'selected': ''}}>Pakistan</option>
                                                        <option {{$student->country == 'Nepal' ? 'selected': ''}}>Nepal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="zip">ZIP</label>
                                                    <input type="text" name="zip" value="{{ $student->zip }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <h4>Parent Info</h4><hr>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="father_name">Father's Name</label>
                                                    <input type="text" name="father_name" value="{{ $student->father_name }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="father_mobile_no">Phone No</label>
                                                    <input type="text" name="father_mobile_no" value="{{ $student->father_mobile_no }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="father_occoupation">Occupation</label>
                                                    <input type="text" name="father_occoupation" value="{{ $student->father_occoupation }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="mother_name">Mother's Name</label>
                                                    <input type="text" name="mother_name" value="{{ $student->mother_name }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="mother_mobile_no">Phone No</label>
                                                    <input type="text" name="mother_mobile_no" value="{{ $student->mother_mobile_no }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="mother_occoupation">Occupation</label>
                                                    <input type="text" name="mother_occoupation" value="{{ $student->mother_occoupation }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <h4>Official Info</h4><hr>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="responsible_person">Assigened Empolyee</label>
                                                    <select name="responsible_person" class="form-control"  id="">
                                                        <option {{$student->responsible_person == 'Amir' ? 'selected': ''}}>Amir</option>
                                                        <option {{$student->responsible_person == 'Rohan' ? 'selected': ''}}>Rohan</option>
                                                        <option {{$student->responsible_person == 'Raju' ? 'selected': ''}}>Raju</option>
                                                        <option {{$student->responsible_person == 'Minar' ? 'selected': ''}}>Minar</option>
                                                        <option {{$student->responsible_person == 'Rafsan' ? 'selected': ''}}>Rafsan</option>
                                                        <option {{$student->responsible_person == 'Babu' ? 'selected': ''}}>Babu</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="note">Note</label>
                                                    <textarea name="note" id=""  rows="1" class="form-control">{{ $student->note}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
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
