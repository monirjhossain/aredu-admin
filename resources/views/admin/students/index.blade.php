@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            {{-- <form id="searchForm" action="{{ route('students.index') }}" method="get">
                <div class="card-title">
                    <input type="text" name="search" placeholder="Search" class="form-control" value="{{ $request->search }}">
                    <input type="text" name="passport_search" class="form-control" placeholder="Passport No." value="{{ request('passport_search') }}">
                    <select class="form-control" name="study_id" id="">
                        <option value="">Select Stydy Level</option>
                        @foreach ($study_levels as $item)
                        <option value="{{$item->id }}" {{ $request->study_id == $item->id ? 'selected' : ''}}>{{$item->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-control col-md-2" name="subject" id="">
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $item)
                        <option value="{{$item->id }}" {{ $request->subject == $item->id ? 'selected' : ''}} >{{$item->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-control" name="university" id="">
                        <option value="">Select University</option>
                        @foreach ($universities as $item)
                        <option value="{{$item->id }}" {{ $request->university == $item->id ? 'selected' : ''}} >{{$item->post_title }}</option>
                        @endforeach
                    </select>
                    <select name="intake" class="form-control" id="">
                        <option value="">Select Intake</option>
                        @foreach ($intakes as $item)
                            <option {{ $request->intake == $item ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>
                        <select name="type" class="form-control"  id="">
                            <option value="">Select Status</option>
                            <option>Inquery</option>
                            <option>Processing</option>
                            <option>Admitted to us</option>
                            <option>Paper Processing</option>
                            <option>Paper Ready</option>
                            <option>Paper Submitted</option>
                            <option>Rejected</option>
                            <option>Admission Cancel</option>
                        </select>
                    <input id="print" type="hidden" name="print" value="no">
                </div>
            </form> --}}
            <form id="searchForm" action="{{ route('students.index') }}" method="get">
                @if(request()->has('branch_id'))
                    <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                @endif
                <input type="hidden" name="perpage" value="{{ request('perpage', 20) }}">
                <div class="row student-filter-row">
                    <div class="col mb-2">
                        <input type="text" name="search" placeholder="Search" class="form-control" value="{{ $request->search }}">
                    </div>
                    <div class="col mb-2">
                        <select class="form-control" name="counselor">
                            <option value="">-Select Counselor-</option>
                            @foreach ($counselors as $counselor)
                                <option value="{{ $counselor->id }}" {{ $request->counselor == $counselor->id ? 'selected' : '' }}>{{ $counselor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <input type="text" name="passport_search" class="form-control" placeholder="Passport No." value="{{ request('passport_search') }}">
                    </div>
                    <div class="col mb-2">
                        <select class="form-control" name="study_id">
                            <option value="">-Select Study Level-</option>
                            @foreach ($study_levels as $item)
                            <option value="{{$item->id }}" {{ $request->study_id == $item->id ? 'selected' : ''}}>{{$item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <select class="form-control" name="subject">
                            <option value="">-Select Subject-</option>
                            @foreach ($subjects as $item)
                            <option value="{{$item->id }}" {{ $request->subject == $item->id ? 'selected' : ''}} >{{$item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <select class="form-control" name="university">
                            <option value="">-Select University-</option>
                            @foreach ($universities as $item)
                            <option value="{{$item->id }}" {{ $request->university == $item->id ? 'selected' : ''}} >{{$item->post_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <select name="intake" class="form-control">
                            <option value="">-Select Intake-</option>
                            @foreach ($intakes as $item)
                                <option {{ $request->intake == $item ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <select name="student_statuses" class="form-control">
                            <option value="">-Select Status-</option>
                            @foreach ($student_statuses as $it)
                                <option {{ $request->student_statuses == $it->id ? 'selected' : ''}} value="{{$it->id}}">{{$it->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input id="print" type="hidden" name="print" value="no">
                    <input id="export" type="hidden" name="export" value="no">
                </div>
            </form>
            <div class="card-toolbar">
                <form method="GET" action="{{ route('students.index') }}" class="mr-1">
                    @foreach(request()->except(['perpage', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="perpage" class="form-control" onchange="this.form.submit()">
                        <option value="5" {{ request('perpage', 20) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('perpage', 20) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perpage', 20) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perpage', 20) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perpage', 20) == 100 ? 'selected' : '' }}>100</option>
                        <option value="200" {{ request('perpage', 20) == 200 ? 'selected' : '' }}>200</option>
                        <option value="500" {{ request('perpage', 20) == 500 ? 'selected' : '' }}>500</option>
                    </select>
                </form>
                <a onclick="submitDetailsForm('no')" class="btn btn-primary mr-1"> <i class="la la-search"></i>Search </a>
                <a href="{{ route('students.index') }}" class="btn btn-primary mr-1"> <i class="la la-undo"></i>Reset </a>
                <!-- <a onclick="submitDetailsForm('yes')" class="btn btn-primary mr-1"> <i class="la la-print"></i>Print </a> -->
                <a onclick="submitDetailsForm('export')" class="btn btn-success mr-1"> <i class="la la-download"></i>Export </a>
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
        <div class="card-body" style="overflow: scroll; ">
            <table class="table table-hover">
                <thead class="thead-light ">
                    <tr>
                        <th style="width: 10px"> # </th>
                        <th class="min-w-180px">Name</th>
                        <th>Study Level</th>
                        <th>University</th>
                        <th>Subject</th>
                        {{-- <th>Documents</th> --}}
                        <th class="min-w-180px">Application Info</th>
                        {{-- <th class="min-w-300px">Student Details</th>
                        <th class="min-w-300px">Student Parents</th> --}}
                        <th>Responsible</th>
                        <th>Reference</th>
                        {{-- <th>Office Status</th> --}}
                        <th>Branch</th>
                        <th>Add By</th>
                        <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt">
                            </i></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $itemSl = $students->total() - ($students->currentPage() - 1) * $students->perPage();
                    @endphp
                    @forelse ($students as $item)

                        <tr>
                            <td>{{ $itemSl-- }}</td>
                            <td>
                                {{ $item->full_name }}
                                <span class="badge
                                    {{ ($item->student_status && in_array($item->student_status->id, [7, 9, 11])) ? 'badge-success'
                                    : ($item->student_status && in_array($item->student_status->id, [4, 12, 2]) ? 'badge-danger'
                                    : ($item->student_status && in_array($item->student_status->id, [6, 8, 13]) ? 'badge-warning' : 'badge-primary')) }}">
                                    {{ $item->student_status->name ?? '' }}
                                </span>
                                <span class="badge badge-primary">{{ $item->intake }} </span>
                            </td>
                            <td>{{ $item->studyLevel->name ?? '' }}</td>
                            <td>
                                @foreach($item->universities as $uni)
                                    @php
                                        $statusname = '';
                                    @endphp
                                    @foreach($item->universitiesAppStatus as $uniAppStatus)
                                            @if ($uniAppStatus->university_id == $uni->id)
                                                @foreach ($uniAppStatus->statusAll as $st)
                                                    @if ($st->active == 1)
                                                        @php
                                                        $statusname = $st->statusUni->name;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                            @endif
                                    @endforeach

                                    <span class="badge badge-primary">{{ $uni->post_title }} </span>
                                    @if ($statusname)
                                    -<span class="badge badge-warning">{{ $statusname }} </span><br>
                                    @endif
                                    @php
                                        $statusname = '';
                                    @endphp
                                @endforeach
                            </td>
                            <td>
                                @foreach($item->subjects as $sub)
                                <span class="badge badge-primary">{{ $sub->name }} </span>
                                @endforeach
                            </td>

                            {{-- <td>
                                @foreach($item->documents as $doc)
                                <span class="badge badge-primary">{{ $doc->name }} </span>
                                @endforeach
                            </td> --}}
                            {{-- <td>{{ $item->subject }}</td> --}}
                            <td>
                                {{-- <span><b>Study Level:</b> {{ $item->studyLevel->name ?? '' }}</span><br> --}}
                                <span><b>Intake:</b> {{ $item->intake }}</span><br>
                                <span><b>Passport:</b> {{ $item->passport }}</span><br>
                                <span><b>Email:</b> {{ $item->email }}</span><br>
                                {{-- <span><b>Father:</b> {{ $item->father_name }}</span><br> --}}
                                {{-- <span><b>mother:</b> {{ $item->mother_name }}</span><br> --}}
                                <span><b>Phone:</b> {{ $item->mobile_no }}</span><br>
                                {{-- <span><b>Preferred Country:</b> {{ $item->preferred_country }}</span><br> --}}
                                {{-- <span><b>Last Education:</b> {{ $item->last_education }}</span><br> --}}
                                {{-- <span><b>Point:</b> {{ $item->last_education }}</span><br> --}}
                                {{-- <span><b>note:</b> {{ $item->note }}</span><br> --}}
                            </td>
                            {{-- <td>
                                <span><b>Email:</b> {{ $item->email }}</span><br>
                                <span><b>Phone:</b> {{ $item->mobile_no }}</span><br>
                                <span><b>Address:</b> {{ $item->address }}</span><br>
                                <span><b>City:</b> {{ $item->city }}</span><br>
                                <span><b>Country:</b> {{ $item->country }}</span><br>
                                <span><b>ZIP:</b> {{ $item->zip }}</span><br>
                            </td> --}}
                            {{-- <td>
                                <span><b>Father:</b> {{ $item->father_name }}</span><br>
                                <span><b>Phone:</b> {{ $item->father_mobile_no }}</span><br>
                                <span><b>Occupation:</b> {{ $item->father_occoupation }}</span><br>
                                <span><b>mother:</b> {{ $item->mother_name }}</span><br>
                                <span><b>Phone:</b> {{ $item->mother_mobile_no }}</span><br>
                                <span><b>Occupation:</b> {{ $item->mother_occoupation }}</span><br>
                            </td> --}}
                            <td>{{ $item->jncounselor->full_name ?? ''}}</td>
                            <td>{{ \Illuminate\Support\Str::words($item->note, 10, '...') }}</td>
                            {{-- <td>
                                <span class="badge {{ $item->status == '1'? 'badge-primary': 'badge-danger' }}">{{ $item->status == '1'? 'Active': 'Deactive' }} </span>
                            </td> --}}
                            {{-- <td>
                                {{ $item->student_status->name ?? '' }}
                            </td> --}}
                            <td>
                                {{ $item->branchInfo->name ?? '' }}
                            </td>
                            <td>
                                {{ $item->user->name ?? '' }} - {{ $item->created_at ?? '' }}
                            </td>
            <td>
                <div class="btn-group" role="group" aria-label="Student Actions">
                    <a href="{{ route($url . '.edit', [$item->id]) }}" class="btn btn-sm btn-outline-primary mx-1 rounded" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('student.invoices.index') }}?student={{ $item->id }}" class="btn btn-sm btn-outline-info mx-1 rounded" title="Invoice List">
                        <i class="fas fa-file-invoice"></i>
                    </a>
                    <a href="{{ route('finance_entries.index') }}?student={{ $item->id }}" class="btn btn-sm btn-outline-warning mx-1 rounded" title="Payment Info">
                        <i class="fas fa-money-bill-wave"></i>
                    </a>
                    <a href="{{ route('students.show', $item->id) }}" class="btn btn-sm btn-info mx-1 rounded" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="getSmsTemplate({{ $item->id }})" type="button" class="btn btn-sm btn-outline-success mx-1 messageModal rounded" data-toggle="modal" data-target="#exampleModalLong" title="Send Message">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <form action="{{ route($url . '.destroy', [$item->id]) }}" method="POST" style="display:inline;">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger mx-1 rounded" title="Delete" onclick="return confirm('Are you sure you want to delete this student?');">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
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
        <div class="card-footer">
            {!! $students->appends(request()->query())->links() !!}
        </div>
        <!-- Modal-->
        <div class="modal fade" id="exampleModalLong" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="sendSmsForm" action="{{ route('student.sendBulkSms') }}">
                        <input type="hidden" name="student_id" value="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Message text</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <select name="template" id=""></select> --}}
                            <div class="loadersmall"></div>
                            <div class="form-group">
                                <label for="preferred_country">Message template</label>
                                <select name="template" class="form-control" id="" required>
                                    <option value="">-- select one --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="preferred_country">Message body</label>
                                <div id="msg_body"> ..... </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">Send SMS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    function getSmsTemplate(id) {
        if(id == 0){
            $cusUrl = '{{ route("student.sendBulkSms") }}';
        } else{
            $cusUrl = '{{ route("student.sendSingleSms") }}';
        }
        $('#msg_body').empty();
        $('#msg_body').append('......');
        $('#sendSmsForm').attr('action', $cusUrl);
        $('input[name="student_id"]').val(id);

        jQuery.ajax({
            url : '{{route("getSmsTemplates")}}',
            type : "GET",
            dataType : "json",
            success:function(data)
            {
                jQuery('select[name="template"]').html('<div class="loadersmall"></div>');

                jQuery('select[name="template"]').html('<option value="">-- Select One --</option>');
                jQuery.each(data.templates, function(key,value){
                    jQuery('select[name="template"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
                jQuery('.loadersmall').remove();
            }
        });
    }
    $('select[name="template"]').on('change',function(){
        var dataID = jQuery(this).val();
        jQuery.ajax({
            url : '{{url("/")}}/sms_template/' +dataID,
            type : "GET",
            dataType : "json",
            success:function(data)
            {
                $('#msg_body').empty();
                $('#msg_body').append(data.template.message);
            }
        });
    });

    function submitDetailsForm(type) {
        if(type == 'yes'){
            $('#searchForm #print').val('yes');
            $('#searchForm #export').val('no');
        }else if(type == 'export'){
            $('#searchForm #print').val('no');
            $('#searchForm #export').val('yes');
        }else{
            $('#searchForm #print').val('no');
            $('#searchForm #export').val('no');
        }
        $("#searchForm").submit();
    }

    $(document).ready(function() {
        $('.sms_send').click(function() {
            if (confirm('Are you sure?')) {
                var url = $(this).attr('linkUrl');
                $(location).attr('href', url);
                console.log('dd');
                console.log(url);
            }
        });
    });


    // $('select[name="division"]').on('change',function(){
    //     var dataID = jQuery(this).val();

    //     jQuery("#district_id").after('<div class="loadersmall"></div>');

    //     if(dataID)
    //     {
    //     jQuery.ajax({
    //         url : '{{url("/")}}/case/dropdownlist/getdependentdistrict/' +dataID,
    //         type : "GET",
    //         dataType : "json",
    //         success:function(data)
    //         {
    //             jQuery('select[name="district"]').html('<div class="loadersmall"></div>');
    //             //console.log(data);

    //             jQuery('select[name="district"]').html('<option value="">-- নির্বাচন করুন --</option>');
    //             jQuery.each(data, function(key,value){
    //                 jQuery('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
    //             });
    //             jQuery('.loadersmall').remove();
    //         }
    //     });
    // }
    </script>
@endsection
