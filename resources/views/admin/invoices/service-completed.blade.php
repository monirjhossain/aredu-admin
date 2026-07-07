@extends('layouts.default')

@section('content')
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            /* font-size: 10px; */
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.35;
        }
        .page-wrap {
            page-break-inside: avoid;
        }
        .copy-block {
            width: 100%;
        }
        .cut-line {
            border-top: 2px dashed #999;
            margin: 10px 0;
            text-align: center;
            color: #888;
            /* font-size: 9px; */
            padding-top: 4px;
        }
        .section-title {
            /* font-size: 11px; */
            font-weight: bold;
            margin: 0 0 5px;
            color: #c00;
        }
        .red-bar {
            height: 4px;
            background: #c00;
            margin: 8px 0 10px;
        }
        .cert-title {
            /* font-size: 16px; */
            font-weight: bold;
            color: #c00;
            margin: 0;
            line-height: 1.2;
        }
        .cert-subtitle {
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0 0;
        }
        .copy-label {
            /* font-size: 11px; */
            font-weight: bold;
            color: #444;
            margin-top: 6px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            /* font-size: 9.5px; */
            margin-bottom: 8px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 5px 6px;
            text-align: left;
        }
        .data-table th {
            background: #c00;
            color: #fff;
            border-color: #c00;
            font-weight: bold;
        }
        .data-table .num {
            text-align: right;
        }
        .totals-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        .declaration {
            /* font-size: 9px; */
            text-align: justify;
            margin: 6px 0 8px;
            line-height: 1.4;
        }
        .signature td {
            border-top: 1px solid #333;
            padding-top: 6px;
            /* font-size: 9px; */
            text-align: center;
        }
        .branch-footer {
            margin-top: 4px;
            /* font-size: 8px; */
            color: #666;
            text-align: center;
        }
    </style>
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
                                {{-- http://127.0.0.1:8000/student/invoices/9/service-completed
                                {{ route('student.invoices.service-completed', $item->id) }} --}}
                                <form action="{{ route('student.invoices.service-completed', $invoice->id) }}" method="GET" enctype="multipart/form-data">
                                    @csrf 
                                    <div class="card-body">
                                        @include('includes.errors')
                                        {{-- <div class="row"> --}}
                                            <div class="">
                                            <div class="copy-block">
                                                @include('admin.invoices.partials.service-completed-body', ['copy_label' => 'STUDENT COPY'])
                                            </div>

                                            {{-- <div class="cut-line">— — — cut here — — —</div>

                                            <div class="copy-block">
                                                @include('pdf.partials.service-completed-copy', [
                                                    'copy_label' => 'OFFICE COPY',
                                                    'office_copy_note' => 'Office Copy — For internal record only',
                                                ])
                                            </div> --}}
                                        {{-- </div>                                        --}}
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <input type="hidden" name="view_page" value="yes">
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

    document.addEventListener('DOMContentLoaded', function () {
        var scholarshipYes = document.querySelector('input[name="scholarship_status"][value="yes"]');
        var scholarshipNo = document.querySelector('input[name="scholarship_status"][value="no"]');
        var detailsWrapper = document.getElementById('scholarship-details-wrapper');

        function toggleScholarshipDetails() {
            if (scholarshipYes && scholarshipYes.checked) {
                detailsWrapper.style.display = 'block';
            } else {
                detailsWrapper.style.display = 'none';
            }
        }

        if (scholarshipYes) {
            scholarshipYes.addEventListener('change', toggleScholarshipDetails);
        }
        if (scholarshipNo) {
            scholarshipNo.addEventListener('change', toggleScholarshipDetails);
        }

        toggleScholarshipDetails();
    });
</script>
@endsection
