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
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Student Information <a href="{{ route('students.edit', [$student->id]) }}" class="text-warning" title="Edit">Edit</a></h4>
            
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Box -->
                <div class="col-md-6 border-right">
                    <p class="h5"><b>Full Name:</b> {{ $student->full_name }}</p>
                    <p class="h5"><b>Email:</b> {{ $student->email }}</p>
                    <p class="h5"><b>Phone:</b> {{ $student->mobile_no }}</p>
                    <p class="h5"><b>Address:</b> {{ $student->address }}, {{ $student->city }}</p>
                </div>

                <!-- Right Box -->
                <div class="col-md-6">
                    <p class="h5"><b>Passport Number:</b> {{ $student->passport }}</p>
                    <p class="h5"><b>Application Status:</b> <span class="badge badge-info p-2">{{ $student->student_status->name }}</span></p>
                    <p class="h5"><b>Remark:</b> {{ $student->note }}</p>
                </div>
                <div class="col-md-6">
                    <span><b>Intake:</b> {{ $student->intake }}</span><br>
                                <span><b>Passport:</b> {{ $student->passport }}</span><br>
                                <span><b>Preferred Country:</b> {{ $student->preferred_country }}</span><br>
                                <span><b>Last Education:</b> {{ $student->last_education }}</span><br>
                                <span><b>Point:</b> {{ $student->last_education }}</span><br>
                                <span><b>note:</b> {{ $student->note }}</span><br>
                </div>
                <div class="col-md-6">
                    <span><b>Father:</b> {{ $student->father_name }}</span><br>
                                <span><b>Phone:</b> {{ $student->father_mobile_no }}</span><br>
                                <span><b>Occupation:</b> {{ $student->father_occoupation }}</span><br>
                                <span><b>mother:</b> {{ $student->mother_name }}</span><br>
                                <span><b>Phone:</b> {{ $student->mother_mobile_no }}</span><br>
                                <span><b>Occupation:</b> {{ $student->mother_occoupation }}</span><br>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects, Universities, Documents -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white"><h3 class="card-title">Subjects</h3></div>
                <div class="card-body">
                    @forelse($student->subjects as $sub)
                        <span class="badge badge-primary mb-1 p-2">{{ $sub->name }}</span><br>
                    @empty
                        <p class="text-muted">No subjects assigned</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-white"><h3 class="card-title">Universities</h3></div>
                <div class="card-body">
                    @forelse($student->universities as $uni)
                        <span class="badge badge-success mb-1 p-2">{{ $uni->post_title }}</span><br>
                    @empty
                        <p class="text-muted">No universities selected</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white"><h3 class="card-title">Documents</h3></div>
                <div class="card-body">
                    @forelse($student->documents as $doc)
                        <span class="badge badge-info mb-1 p-2">{{ $doc->name }}</span><br>
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
                        <th>University</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Remarks</th>
                        <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->universitiesAppStatus as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->university->post_title }}</td>
                            <td>{{ $item->subject->name  }}</td>
                            {{-- <td>{{ $item->statusUni->name }} --}}
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
                            <td>{{ $item->note }}</td>
                            <td>{{ $item->remarks }}</td>
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
