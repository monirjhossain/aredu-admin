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
                    <p><strong>University:</strong> {{ $application->university->post_title }}</p>
                    <p><strong>Subject:</strong> {{ $application->subject->name }}</p>
                    <p><strong>Current Status:</strong> <span class="badge badge-info">{{ $application->statusUni->name }}</span></p>
                </div>
            </div>

            <form action="{{ route($url.'.update', $application->id) }}" method="POST">
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
                    <label for="note">Note</label>
                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Add a note about this application status">{{ old('note', $application->note) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Add any additional remarks">{{ old('remarks', $application->remarks) }}</textarea>
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
@endsection