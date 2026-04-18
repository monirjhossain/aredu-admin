@extends('layouts.default')

@section('content')
<div class="container">
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Student Details
    </a>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Application Status</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3>Student Information</h3>
                    <p><strong>Name:</strong> {{ $student->full_name }}</p>
                    <p><strong>Email:</strong> {{ $student->email }}</p>
                    <p><strong>Phone:</strong> {{ $student->mobile_no }}</p>
                </div>
                <div class="col-md-6">
                    <h3>Application Details</h3>
                    <p><strong>University:</strong>
                        <a href="{{ route('universities.show', [$application->university->id]) }}">{{ $application->university->post_title }}</a>
                    </p>
                    <p><strong>Subject:</strong> {{ $application->subject->name }}</p>
                    <p><strong>Current Status:</strong> <span class="badge badge-info">{{ $application->statusUni->name }}</span></p>
                </div>
            </div>

            <form action="{{ route($url.'.update', $application->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <div class="form-group">
                    <label for="status_id">Update Status</label>
                    <select name="status_id" id="status_id" class="form-control" required>
                        @foreach($university_statuses as $status)
                            <option value="{{ $status->id }}" {{ $application->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="application_no">  Application No</label>
                    <input type="text" value="{{ old('application_no', $application->application_no) }}" name="application_no" class="form-control" placeholder="Enter application no">
                </div>
                <div class="form-group">
                    <label for="portal_type">Is this agent portal?</label>
                        <label>
                            <input type="radio" name="portal_type" value="1" required {{ old('portal_type', $application->portal_type ?? 1) == 1 ? 'checked' : '' }}>
                            Yes
                        </label>
                
                        <label>
                            <input type="radio" name="portal_type" value="0" {{ old('portal_type', $application->portal_type ?? 0) == 0 ? 'checked' : '' }}>
                            No
                        </label>
                </div>
                <div id="credentials_fields" style="display: none;">
                <div class="form-group">
                    <label for="login_id">Login ID</label>
                    <input type="text" value="{{ old('login_id', $application->login_id) }}" name="login_id" class="form-control" placeholder="Enter">
                </div>
                <div class="form-group">
                    <label for="login_password">Login Password</label>
                    <input type="text" value="{{ old('login_id', $application->login_password) }}" name="login_password" class="form-control" placeholder="Enter">
                </div>
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Add a note about this application status">{{ old('note', $application->note) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Add any additional remarks">{{ old('remarks', $application->remarks) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body py-2">
                                <label for="admission_notice" class="font-weight-bold">Admission Notice</label>
                                <input type="file" name="admission_notice" id="admission_notice" class="form-control-file mb-1" accept=".pdf,.jpg,.jpeg,.png">
                                @if($application->admission_notice)
                                    <div>
                                        <a href="{{ asset('storage/student_applications/' . $application->admission_notice) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View/Download</a>
                                    </div>
                                @endif
                                <small class="text-muted">PDF, JPG, JPEG, PNG</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body py-2">
                                <label for="jw_copy" class="font-weight-bold">JW Copy</label>
                                <input type="file" name="jw_copy" id="jw_copy" class="form-control-file mb-1" accept=".pdf,.jpg,.jpeg,.png">
                                @if($application->jw_copy)
                                    <div>
                                        <a href="{{ asset('storage/student_applications/' . $application->jw_copy) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View/Download</a>
                                    </div>
                                @endif
                                <small class="text-muted">PDF, JPG, JPEG, PNG</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body py-2">
                                <label for="visa_copy" class="font-weight-bold">Visa Copy</label>
                                <input type="file" name="visa_copy" id="visa_copy" class="form-control-file mb-1" accept=".pdf,.jpg,.jpeg,.png">
                                @if($application->visa_copy)
                                    <div>
                                        <a href="{{ asset('storage/student_applications/' . $application->visa_copy) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View/Download</a>
                                    </div>
                                @endif
                                <small class="text-muted">PDF, JPG, JPEG, PNG</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Application
                    </button>
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const radios = document.querySelectorAll('input[name="portal_type"]');
    const fields = document.getElementById('credentials_fields');

    function toggleCredentials() {
        const selected = document.querySelector('input[name="portal_type"]:checked').value;

        if (selected === '0') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    }

    // Attach event listener to both radios
    radios.forEach(radio => {
        radio.addEventListener('change', toggleCredentials);
    });

    // Run on load
    toggleCredentials();
});
</script>
@endsection