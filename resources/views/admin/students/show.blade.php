@extends('layouts.default')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f8f9fa;
    }

    .progress-container {
        position: relative;
        width: 300px;
        margin: 10px auto;
    }

    /* Vertical line */
    .progress-line {
        position: absolute;
        left: 13px;
        top: 0;
        width: 3px;
        height: 100%;
        background-color: #ccc;
        z-index: 1;
    }

    /* Step item */
    .step {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        z-index: 2;
    }

    /* Circle */
    .circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #ccc;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        z-index: 2;
    }

    /* Active step */
    .circle.active {
        background-color: #28a745; /* green */
    }

    /* Step label */
    .label-ff {
        margin-left: 20px;
        /* font-size: 16px; */
        color: #333;
    }
    .label-ff.active {
        color: #28a745; /* green */
    }

    /* Optional: last step margin */
    .step:last-child {
        margin-bottom: 0;
    }
</style>
<div class="container">
    <a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Students
    </a>


    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Student Information</h4>
                <a href="{{ route('student.invoices.index') }}?student={{ $student->id }}" class="btn btn-dark btn-sm rounded-pill font-weight-bold d-flex align-items-center" title="Edit">
                    <i class="fas fa-file-invoice mr-1"></i> Invoice List
                </a>
                <a href="{{ route('students.edit', [$student->id]) }}" class="btn btn-dark btn-sm rounded-pill font-weight-bold d-flex align-items-center" title="Edit">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Personal Info -->
                <div class="col-md-6 border-right mb-3">
                    <h5 class="text-secondary mb-3"><i class="fas fa-user mr-1"></i> Personal Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="fw-bold text-primary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-user-circle me-2 pe-1"></i></span><span class="ms-3">Name:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->full_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-success ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-envelope me-2 pe-1"></i></span><span class="ms-3">Email:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-info ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-phone-alt me-2 pe-1"></i></span><span class="ms-3">Phone:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->mobile_no }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-secondary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-map-marker-alt me-2 pe-1"></i></span><span class="ms-3">Address:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->address }}, {{ $student->city }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-warning ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-calendar-alt me-2 pe-1"></i></span><span class="ms-3">Intake:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->intake }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-danger ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-flag me-2 pe-1"></i></span><span class="ms-3">Preferred Country:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->preferred_country }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Academic Info -->
                <div class="col-md-6 mb-3">
                    <h5 class="text-secondary mb-3"><i class="fas fa-graduation-cap mr-1"></i> Academic & Application</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="fw-bold text-secondary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-passport me-2 pe-1"></i></span><span class="ms-3">Passport Number:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->passport }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-info ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-info-circle me-2 pe-1"></i></span><span class="ms-3">Application Status:</span></span>
                            <span class="ms-3" style="font-size:1.18rem;"><span class="badge badge-info p-2">{{ $student->student_status->name }}</span></span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-success ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-university me-2 pe-1"></i></span><span class="ms-3">Last Education:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->last_education }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-primary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-star me-2 pe-1"></i></span><span class="ms-3">Point:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->last_education }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-dark ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-flask me-2 pe-1"></i></span><span class="ms-3">CSCA Background:</span></span>
                            <div class="ms-5 mt-2 text-dark" style="font-size:1.05rem; line-height: 1.6;">
                                <div><b>Have CSCA:</b> {{ (int)($student->has_csca ?? 0) === 1 ? 'Yes' : 'No' }}</div>
                                <div><b>Math Result:</b> {{ $student->csca_math ?? '-' }}</div>
                                <div><b>Physics Result:</b> {{ $student->csca_physics ?? '-' }}</div>
                                <div><b>Chemistry Result:</b> {{ $student->csca_chemistry ?? '-' }}</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-muted ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-comment-dots me-2 pe-1"></i></span><span class="ms-3">Remark:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{!! nl2br(e($student->note)) !!}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-primary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-user-tie me-2 pe-1"></i></span><span class="ms-3">Assign Employee:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->responsiblePerson->name ?? '' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-primary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-user-graduate me-2 pe-1"></i></span><span class="ms-3">Senior Counselor:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->sncounselor->name ?? '' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="fw-bold text-primary ps-2" style="font-size:1.18rem;"><span style="display:inline-block;min-width:32px;"><i class="fas fa-user-tie me-2 pe-1"></i></span><span class="ms-3">Junior Counselor:</span></span>
                            <span class="ms-3 text-dark" style="font-size:1.18rem;">{{ $student->jncounselor->name ?? '' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-3">
                <!-- Family Info -->
                <div class="col-md-6 border-right">
                    <h5 class="text-secondary mb-3"><i class="fas fa-male mr-1"></i> Father Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Name:</b> <span class="ms-3">{{ $student->father_name }}</span></li>
                        <li class="list-group-item"><b>Phone:</b> <span class="ms-3">{{ $student->father_mobile_no }}</span></li>
                        <li class="list-group-item"><b>Occupation:</b> <span class="ms-3">{{ $student->father_occoupation }}</span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="text-secondary mb-3"><i class="fas fa-female mr-1"></i> Mother Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Name:</b> <span class="ms-3">{{ $student->mother_name }}</span></li>
                        <li class="list-group-item"><b>Phone:</b> <span class="ms-3">{{ $student->mother_mobile_no }}</span></li>
                        <li class="list-group-item"><b>Occupation:</b> <span class="ms-3">{{ $student->mother_occoupation }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects, Universities, Documents -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white"><h3 class="card-title" style="font-size:2rem;">Subjects</h3></div>
                <div class="card-body">
                    @forelse($student->subjects as $sub)
                        <span class="badge badge-primary mb-1 p-2" style="font-size:1rem;">{{ $sub->name }}</span><br>
                    @empty
                        <p class="text-muted">No subjects assigned</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-white"><h3 class="card-title" style="font-size:2rem;">Universities</h3></div>
                <div class="card-body">
                    @forelse($student->universities as $uni)
                        <span class="badge badge-success mb-1 p-2" style="font-size:1rem;">{{ $uni->post_title }}</span><br>
                    @empty
                        <p class="text-muted">No universities selected</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white"><h3 class="card-title" style="font-size:2rem;">Documents</h3></div>
                <div class="card-body">
                    @forelse($student->documents as $doc)
                        <span class="badge badge-info mb-1 p-2" style="font-size:1rem;">{{ $doc->name }}</span><br>
                    @empty
                        <p class="text-muted">No documents uploaded</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <form action="{{ route($url . '.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="student_id" value="{{$student->id}}">
        <input type="hidden" name="status_id" value="1">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Application</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="country">Subject</label>
                        <select name="subject_id" class="form-control" id="" required>
                            <option value="">----Select----</option>
                            @foreach($student->subjects as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="country">University </label>
                        <select name="university_id" class="form-control" id="" required>
                            <option value="">----Select----</option>
                            @foreach($student->universities as $item)
                                <option value="{{$item->id}}">{{$item->post_title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-lg btn-primary">ADD</button>
        </div>
        </form>

    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Application Information</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="thead-light ">
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Application No</th>
                        <th>University</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Remarks</th>
                        <th>Admission Notice</th>
                        <th>JW Copy</th>
                        <th>Visa Copy</th>
                        <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->universitiesAppStatus as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->application_no ?? '-' }}</td>
                            <td>{{ $item->university->post_title }}</td>
                            <td>{{ $item->subject->name ?? '-' }}</td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress-line"></div>
                                    @foreach ($item->statusAll as $key => $st)
                                        <div class="step">
                                            <div class="circle {{ $st->active == 1 ? 'active' : '' }}">{{ $key+1 }}</div>
                                            <div class="label-ff {{ $st->active == 1 ? 'active' : '' }} "><b>{{ $st->statusUni->name }}</b></div>
                                            <div> &nbsp; {{ $st->created_at }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $item->note ?? '-'}}</td>
                            <td>{{ $item->remarks ?? '-'}}</td>
                            <td class="text-center">
                                @if($item->admission_notice)
                                    <a href="{{ asset('storage/student_applications/' . $item->admission_notice) }}" target="_blank" class="btn btn-light btn-sm p-2 border shadow-sm mx-1 file-link" title="View or Download Admission Notice">
                                        <i class="fas fa-file-pdf fa-lg text-danger"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->jw_copy)
                                    <a href="{{ asset('storage/student_applications/' . $item->jw_copy) }}" target="_blank" class="btn btn-light btn-sm p-2 border shadow-sm mx-1 file-link" title="View or Download JW Copy">
                                        <i class="fas fa-file-pdf fa-lg text-primary"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->visa_copy)
                                    <a href="{{ asset('storage/student_applications/' . $item->visa_copy) }}" target="_blank" class="btn btn-light btn-sm p-2 border shadow-sm mx-1 file-link" title="View or Download Visa Copy">
                                        <i class="fas fa-file-pdf fa-lg text-success"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            @push('styles')
                            <style>
                                .file-link:hover {
                                    background: #f0f0f0;
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                                    text-decoration: none;
                                }
                            </style>
                            @endpush
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route($url.'.edit', [$item->id]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="deleteForm{{ $item->id }}" action="{{ route($url.'.destroy', [$item->id]) }}"
                                        class="d-inline-block" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{$student->id}}">
                                        <input type="hidden" name="id" value="{{$item->id}}">
                                        <button id="submitButton" onclick="confirmDelete({{ $item->id }})" type="button" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
                                <h5 class="text-center">Not found.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                console.log('dfsdafds');

                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
</script>

@endsection
