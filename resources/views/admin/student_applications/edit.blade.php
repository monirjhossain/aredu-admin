@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/admin/application-edit.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="app-edit-subheader d-flex flex-wrap align-items-center justify-content-between">
    <div>
        <h1 class="font-weight-bolder text-dark mb-1">Edit Application</h1>
        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-muted">Students</a></li>
            <li class="breadcrumb-item"><a href="{{ route('students.show', $student->id) }}" class="text-muted">{{ $student->full_name }}</a></li>
            <li class="breadcrumb-item text-muted">Application #{{ $application->id }}</li>
        </ul>
    </div>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-light-primary font-weight-bold mt-3 mt-md-0">
        <i class="fas fa-arrow-left mr-1"></i> Back to Student
    </a>
</div>

<div class="row">
    <div class="col-xl-4">
    <div class="card card-custom gutter-b">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title font-weight-bolder text-dark">Student</h3>
        </div>
        <div class="card-body pt-0 app-edit-summary">
            <div class="d-flex align-items-center mb-5">
                <div class="symbol symbol-50 symbol-circle symbol-light-primary mr-4">
                    <span class="symbol-label font-weight-bold text-primary">{{ $studentInitials ?: 'ST' }}</span>
                </div>
                <div>
                    <a href="{{ route('students.show', $student->id) }}" class="font-weight-bolder text-dark text-hover-primary font-size-h6 d-block">
                        {{ $student->full_name }}
                    </a>
                    <span class="text-muted font-size-sm">ID #{{ $student->id }}</span>
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Email</div>
                <div class="summary-value">{{ $student->email ?: '—' }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Phone</div>
                <div class="summary-value">{{ $student->mobile_no ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title font-weight-bolder text-dark">Application</h3>
        </div>
        <div class="card-body pt-0 app-edit-summary">
            <div class="summary-item">
                <div class="summary-label">University</div>
                <div class="summary-value">
                    @if(optional($application->university)->id)
                        <a href="{{ route('universities.show', $application->university->id) }}" class="text-hover-primary">
                            {{ $application->university->post_title }}
                        </a>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Subject</div>
                <div class="summary-value">{{ optional($application->subject)->name ?? '—' }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Application No</div>
                <div class="summary-value">{{ $application->application_no ?: '—' }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Current Status</div>
                <div class="summary-value">
                    <span class="label label-lg label-light-{{ $statusTone }} label-inline font-weight-bold">
                        {{ optional($application->statusUni)->name ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-xl-8">
    <div class="card card-custom gutter-b">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title font-weight-bolder text-dark">
                <i class="fas fa-edit text-primary mr-2"></i>Update Application
            </h3>
        </div>
        <div class="card-body pt-2 pb-8">
            <form action="{{ route($url . '.update', $application->id) }}" method="POST" enctype="multipart/form-data" id="applicationEditForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <div class="app-form-section">
                    <div class="app-form-section-title"><i class="fas fa-flag text-primary"></i>Status & Reference</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_id" class="font-weight-bold">Application Status <span class="text-danger">*</span></label>
                                <select name="status_id" id="status_id" class="form-control form-control-solid" required>
                                    @foreach($university_statuses as $status)
                                        <option value="{{ $status->id }}" {{ (int) old('status_id', $application->status_id) === (int) $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="application_no" class="font-weight-bold">Application No</label>
                                <input type="text" name="application_no" id="application_no" class="form-control form-control-solid"
                                    value="{{ old('application_no', $application->application_no) }}" placeholder="Enter application number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-form-section">
                    <div class="app-form-section-title"><i class="fas fa-key text-warning"></i>Portal Access</div>
                    <div class="form-group">
                        <label class="font-weight-bold d-block mb-3">Is this agent portal?</label>
                        <div class="btn-group portal-type-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-success {{ $portalType === 1 ? 'active' : '' }}">
                                <input type="radio" name="portal_type" value="1" autocomplete="off" {{ $portalType === 1 ? 'checked' : '' }}> Yes
                            </label>
                            <label class="btn btn-outline-secondary {{ $portalType === 0 ? 'active' : '' }}">
                                <input type="radio" name="portal_type" value="0" autocomplete="off" {{ $portalType === 0 ? 'checked' : '' }}> No
                            </label>
                        </div>
                    </div>
                    <div id="agent_portal_hint" class="agent-portal-hint mb-4" style="{{ $portalType === 1 ? '' : 'display:none;' }}">
                        <i class="fas fa-info-circle mr-1"></i>
                        University portal credentials from university settings will be used.
                        @if(optional($application->university)->getMeta('link'))
                            <br><strong>Link:</strong> {{ $application->university->getMeta('link') }}
                        @endif
                    </div>
                    <div id="credentials_fields" style="{{ $portalType === 0 ? '' : 'display:none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="login_id" class="font-weight-bold">Login ID</label>
                                    <input type="text" name="login_id" id="login_id" class="form-control form-control-solid"
                                        value="{{ old('login_id', $application->login_id) }}" placeholder="Portal login ID">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="login_password" class="font-weight-bold">Login Password</label>
                                    <input type="text" name="login_password" id="login_password" class="form-control form-control-solid"
                                        value="{{ old('login_password', $application->login_password) }}" placeholder="Portal password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-form-section">
                    <div class="app-form-section-title"><i class="fas fa-sticky-note text-info"></i>Notes</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="note" class="font-weight-bold">Note</label>
                                <textarea name="note" id="note" rows="4" class="form-control form-control-solid"
                                    placeholder="Note about this application">{{ old('note', $application->note) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remarks" class="font-weight-bold">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="4" class="form-control form-control-solid"
                                    placeholder="Additional remarks">{{ old('remarks', $application->remarks) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-form-section">
                    <div class="app-form-section-title"><i class="fas fa-paperclip text-success"></i>Documents</div>
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="app-doc-upload text-center">
                                <div class="doc-icon text-danger"><i class="fas fa-file-pdf"></i></div>
                                <div class="doc-title">Admission Notice</div>
                                <div class="custom-file mb-2">
                                    <input type="file" name="admission_notice" id="admission_notice" class="custom-file-input" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="admission_notice">Choose file</label>
                                </div>
                                @if($application->admission_notice)
                                    <a href="{{ asset('storage/student_applications/' . $application->admission_notice) }}" target="_blank" class="btn btn-sm btn-light-primary font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Current file
                                    </a>
                                @endif
                                <small class="d-block text-muted mt-2">PDF, JPG, PNG</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="app-doc-upload text-center">
                                <div class="doc-icon text-primary"><i class="fas fa-file-pdf"></i></div>
                                <div class="doc-title">JW Copy</div>
                                <div class="custom-file mb-2">
                                    <input type="file" name="jw_copy" id="jw_copy" class="custom-file-input" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="jw_copy">Choose file</label>
                                </div>
                                @if($application->jw_copy)
                                    <a href="{{ asset('storage/student_applications/' . $application->jw_copy) }}" target="_blank" class="btn btn-sm btn-light-primary font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Current file
                                    </a>
                                @endif
                                <small class="d-block text-muted mt-2">PDF, JPG, PNG</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="app-doc-upload text-center">
                                <div class="doc-icon text-success"><i class="fas fa-file-pdf"></i></div>
                                <div class="doc-title">Visa Copy</div>
                                <div class="custom-file mb-2">
                                    <input type="file" name="visa_copy" id="visa_copy" class="custom-file-input" accept=".pdf,.jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="visa_copy">Choose file</label>
                                </div>
                                @if($application->visa_copy)
                                    <a href="{{ asset('storage/student_applications/' . $application->visa_copy) }}" target="_blank" class="btn btn-sm btn-light-primary font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Current file
                                    </a>
                                @endif
                                <small class="d-block text-muted mt-2">PDF, JPG, PNG</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-form-actions d-flex flex-wrap">
                    <button type="submit" class="btn btn-primary font-weight-bold mr-2 mb-2">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-light-secondary font-weight-bold mb-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/application-edit.js') }}"></script>
<script>
$(document).ready(function () {
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error({!! json_encode($error) !!}, 'Validation');
        @endforeach
    @endif
});
</script>
@endsection