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
                            <div class="">
                                <select class="form-control" name="type">
                                    <option value="">All Type</option>
                                    @foreach ($type as $item)
                                    <option value="{{$item->type }}">{{$item->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button onclick="getSmsTemplate(0)" type="button" class="btn btn-primary messageModal" data-toggle="modal" data-target="#exampleModalLong">
                                <i class="fas fa-envelope" title="message"></i>
                            </button>
                            <a href="{{ route('inquiries.create') }}" class="btn btn-primary">Create inquiry</a>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="overflow: scroll; ">
                        <table class="table table-hover mb-6">
                            <thead class="thead-light ">
                                <tr>
                                    <th style="width: 10px"> # </th>
                                    <th>Type</th>
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
                                    <td>{{ $inquiry->type ?? '' }}</td>
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
    <div class="modal fade" id="exampleModalLong" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="sendSmsForm" action="{{ route('student.sendBulkSms') }}">
                        <input type="hidden" name="student_id" value="">
                        <input type="hidden" name="type" value="">
                        <input type="hidden" name="page" value="inquery">
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
    $('select[name="type"]').on('change', function() {
        $('input[name="type"]').val($(this).val());
    });
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
        $('input[name="type"]').val($('select[name="type"]').val());

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
        console.log('mm');
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
</script>
@endsection
