@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Inquiry List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inquiry List</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label font-weight-bolder mb-0">Inquiries</h3>
                    <span class="text-muted font-size-sm d-block mt-1">Manage branch applications and convert to students</span>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('inquiries.index', array_filter(['branch_id' => $branchId, 'type' => request('type'), 'export' => 'yes'])) }}" class="btn btn-success btn-sm font-weight-bold mr-2">
                        <i class="la la-download"></i> Export
                    </a>
                    <button onclick="getSmsTemplate(0)" type="button" class="btn btn-light-primary btn-sm font-weight-bold mr-2 messageModal" data-toggle="modal" data-target="#exampleModalLong" title="Send SMS">
                        <i class="fas fa-envelope"></i> SMS
                    </button>
                    <a href="{{ route('application-qr-codes') }}" class="btn btn-warning btn-sm font-weight-bold mr-2">
                        <i class="fas fa-qrcode"></i> Application QR
                    </a>
                    <a href="{{ route('inquiries.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="la la-plus"></i> Create Inquiry
                    </a>
                </div>
            </div>

            <div class="card-body pt-4 pb-0">
                <div class="row align-items-end mb-5">
                    <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                        <label class="font-weight-bold mb-2 d-block" for="branchFilter">Branch</label>
                        @if ($canManageAnyBranch && $branches->count() > 1)
                            <select class="form-control form-control-sm" id="branchFilter">
                                <option value="" {{ $branchId ? '' : 'selected' }}>All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ (int) $branchId === (int) $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif ($branches->isNotEmpty())
                            <div class="form-control form-control-sm bg-light">{{ $branches->first()->name }}</div>
                        @else
                            <div class="form-control form-control-sm bg-light">—</div>
                        @endif
                    </div>
                    <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                        <label class="font-weight-bold mb-2 d-block" for="typeFilter">Type</label>
                        <select class="form-control form-control-sm" id="typeFilter">
                            <option value="">All Types</option>
                            @foreach ($type as $item)
                                <option value="{{ $item->type }}" {{ request('type') == $item->type ? 'selected' : '' }}>
                                    {{ $item->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Tracking ID</th>
                                <th>Type</th>
                                <th>Branch</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Preferred</th>
                                <th>Education</th>
                                <th>Last Result</th>
                                <th>Year</th>
                                <th>CSCA Subjects</th>
                                <th>Course</th>
                                <th>University</th>
                                <th>Subject</th>
                                <th style="min-width: 160px;">Message</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->id }}</td>
                                <td>
                                    @if ($inquiry->tracking_id)
                                        <span class="badge badge-light-warning font-weight-bold">{{ $inquiry->tracking_id }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td><span class="text-muted font-size-sm">{{ $inquiry->type ?? '—' }}</span></td>
                                <td>{{ optional($inquiry->branchInfo)->name ?? '—' }}</td>
                                <td class="font-weight-bold">
                                    {{ $inquiry->full_name }}
                                    @if ($inquiry->view == 0)
                                        <span class="badge badge-danger badge-sm ml-1">New</span>
                                    @endif
                                    @if ($inquiry->student_id)
                                        <span class="badge badge-success badge-sm ml-1">Student</span>
                                    @endif
                                </td>
                                <td>{{ $inquiry->mobile_no }}</td>
                                <td>{{ $inquiry->email }}</td>
                                <td>{{ $inquiry->country ?: '—' }}</td>
                                <td>{{ $inquiry->country_preferred ?: '—' }}</td>
                                <td>{{ $inquiry->last_education ?: '—' }}</td>
                                <td>{{ $inquiry->result ?: '—' }}</td>
                                <td>{{ $inquiry->year ?: '—' }}</td>
                                <td style="max-width: 180px;" class="text-truncate" title="{{ $inquiry->csca_subjects }}">{{ $inquiry->csca_subjects ?: '—' }}</td>
                                <td>{{ $inquiry->course ?: '—' }}</td>
                                <td style="max-width: 180px;" class="text-truncate" title="{{ $inquiry->university }}">{{ $inquiry->university ?: '—' }}</td>
                                <td style="max-width: 140px;" class="text-truncate" title="{{ $inquiry->subject }}">{{ $inquiry->subject ?: '—' }}</td>
                                <td style="max-width: 180px;" class="text-truncate" title="{{ $inquiry->message }}">{{ $inquiry->message ?: '—' }}</td>
                                <td>
                                    <span class="badge {{ $inquiry->status == '1' ? 'badge-light-primary' : 'badge-light-danger' }}">
                                        {{ $inquiry->status == '1' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        @if ($inquiry->student_id)
                                            <a href="{{ route('students.show', $inquiry->student_id) }}" class="btn btn-icon btn-light-success btn-sm mr-1" title="View Student">
                                                <i class="fas fa-user-graduate"></i>
                                            </a>
                                        @else
                                            <form action="{{ route('inquiries.convert-to-student', $inquiry->id) }}" method="POST" onsubmit="return confirm('Convert this inquiry to a student?');">
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-light-success btn-sm mr-1" title="Convert to Student">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('inquiries.edit', $inquiry->id) }}" class="btn btn-icon btn-light-primary btn-sm mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-icon btn-light-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="19" class="text-center py-10">
                                    <span class="text-muted font-size-h6">No inquiries found for this branch.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-center">
                {{ $inquiries->links() }}
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
                        <div class="loadersmall"></div>
                        <div class="form-group">
                            <label for="preferred_country">Message template</label>
                            <select name="template" class="form-control" required>
                                <option value="">-- select one --</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label for="preferred_country">Message body</label>
                            <div id="msg_body">.....</div>
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
    function inquiryListUrl() {
        var url = new URL(window.location.href);

        if ($('#branchFilter').length) {
            var branch = $('#branchFilter').val();
            if (branch) {
                url.searchParams.set('branch_id', branch);
            } else {
                url.searchParams.delete('branch_id');
            }
        }

        var type = $('#typeFilter').val();
        if (type) {
            url.searchParams.set('type', type);
        } else {
            url.searchParams.delete('type');
        }

        return url.toString();
    }

    $('#branchFilter, #typeFilter').on('change', function() {
        window.location.href = inquiryListUrl();
    });

    function getSmsTemplate(id) {
        var cusUrl = id == 0 ? '{{ route("student.sendBulkSms") }}' : '{{ route("student.sendSingleSms") }}';

        $('#msg_body').empty().append('......');
        $('#sendSmsForm').attr('action', cusUrl);
        $('input[name="student_id"]').val(id);
        $('input[name="type"]').val($('#typeFilter').val());

        jQuery.ajax({
            url: '{{ route("getSmsTemplates") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                jQuery('select[name="template"]').html('<option value="">-- Select One --</option>');
                jQuery.each(data.templates, function(key, value) {
                    jQuery('select[name="template"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                jQuery('.loadersmall').remove();
            }
        });
    }

    $('select[name="template"]').on('change', function() {
        var dataID = jQuery(this).val();
        jQuery.ajax({
            url: '{{ url("/") }}/sms_template/' + dataID,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#msg_body').empty().append(data.template.message);
            }
        });
    });
</script>
@endsection
