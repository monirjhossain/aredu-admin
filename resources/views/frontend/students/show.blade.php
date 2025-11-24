@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card bg-primary border-0 shadow rounded-lg w-100">
                <div class="d-flex align-items-center justify-content-center flex-wrap px-5 py-5 px-md-10 py-md-9">
                    <!--begin::Menu-->
                    <h1 class="m-0 text-light">{{$title}}</h1>
                    <!--end::Menu-->
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="">{{$title}}</h3>
                        <a href="https://areducation.net/" class="btn btn-primary">Go Back to Home</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12" style="overflow: scroll; ">
                            <table class="table table-md border-0">
                                <thead>
                                  <tr>
                                    <th scope="col">Application Info</th>
                                    <th scope="col">Student Info</th>
                                    <th scope="col">Parent Info</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                        <span><b>Study Level:</b> {{ $student->study_level }}</span><br>
                                        <span><b>Name:</b> {{ $student->full_name }}</span><br>
                                        <span><b>Passport:</b> {{ $student->passport }}</span><br>
                                        <span><b>Preferred Subject:</b> {{ $student->subject }}</span><br>
                                        <span><b>Preferred University:</b> {{ $student->university->post_title ?? ''  }}</span><br>
                                        <span><b>Preferred Country:</b> {{ $student->preferred_country }}</span><br>
                                        <span><b>SSC Result:</b> {{ $student->ssc_result }}</span><br>
                                        <span><b>Hsc Result:</b> {{ $student->hsc_result }}</span><br>
                                        <span><b>Last Education:</b> {{ $student->last_education }}</span><br>
                                        <span><b>note:</b> {{ $student->note }}</span><br>

                                    </td>
                                    <td>
                                        <span><b>Email:</b> {{ $student->email }}</span><br>
                                        <span><b>Phone:</b> {{ $student->mobile_no }}</span><br>
                                        <span><b>Address:</b> {{ $student->address }}</span><br>
                                        <span><b>City:</b> {{ $student->city }}</span><br>
                                        <span><b>Country:</b> {{ $student->country }}</span><br>
                                        <span><b>ZIP:</b> {{ $student->zip }}</span><br>
                                    </td>
                                    <td>
                                        <span><b>Father:</b> {{ $student->father_name }}</span><br>
                                        <span><b>Phone:</b> {{ $student->father_mobile_no }}</span><br>
                                        <span><b>Occupation:</b> {{ $student->father_occoupation }}</span><br>
                                        <span><b>mother:</b> {{ $student->mother_name }}</span><br>
                                        <span><b>Phone:</b> {{ $student->mother_mobile_no }}</span><br>
                                        <span><b>Occupation:</b> {{ $student->mother_occoupation }}</span><br>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">
                                        <span><b>Link:</b> {{ url()->full() }}</span><br>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
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
