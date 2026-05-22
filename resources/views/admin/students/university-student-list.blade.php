@extends('layouts.default')

@section('content')
@include('admin.students.partials.inline-status-select-styles')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{$title}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{$title}}</h3>
                            {{-- <div class="d-flex align-items-center">
                                <form method="GET" action="{{ route('finance_entries.index') }}" class="mr-2">
                                    <select name="perpage" class="form-control" onchange="this.form.submit()">
                                        <option value="5" {{ request('perpage') == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ request('perpage') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('perpage') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('perpage') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('perpage') == 100 ? 'selected' : '' }}>100</option>
                                        <option value="200" {{ request('perpage') == 200 ? 'selected' : '' }}>200</option>
                                        <option value="500" {{ request('perpage') == 500 ? 'selected' : '' }}>500</option>
                                    </select>
                                </form>
                            </div> --}}
                        </div>
                        @include('admin.students.partials.application-list-toolbar', [
                            'university' => $university,
                            'application_status_filter' => $application_status_filter ?? null,
                        ])
                    </div>

                    <div class="card-body" style="overflow: scroll;">
                        <table
                            class="table table-hover mb-6"
                            data-inline-status-root
                            data-csrf="{{ csrf_token() }}"
                            data-status-tones='@json($status_tone_map)'
                        >
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Application No</th>
                                    <th>Name</th>
                                    <th>Passport</th>
                                    {{-- <th>University</th> --}}
                                    <th>GPA</th>
                                    <th>Passing Year</th>
                                    <th>Branch</th>
                                    <th>Subject</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th>Language</th>
                                    <th>Note</th>
                                    <th>Remarks</th>
                                    <th>Link</th>
                                    <th>User</th>
                                    <th>Password</th>
                                    <th>CSCA</th>
                                    <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $key=>$item)
                                    @php
                                        $application = $item->universityById($university->id);
                                        $education = $item->educationQualifications->sortByDesc('id')->first();
                                    @endphp
                                    <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $application->application_no ?? '-' }}</td>
                                            <td>{{ $item->full_name ?? '-' }}</td>
                                            <td>{{ $item->passport ?? '-' }}</td>
                                            {{-- <td>{{ $university->post_title }}</td> --}}

                                            <td>{{ optional($education)->result ?? '-' }}</td>
                                            <td>{{ optional($education)->attend_to ?? '-' }}</td>
                                            <td>{{ $item->branchInfo->name ?? '' }}</td>
                                            <td>{{ optional($application->subject)->name ?? '-' }}</td>
                                            <td>{{ $item->mobile_no }}</td>
                                            <td class="uni-status-cell">
                                                @if($application)
                                                    @include('admin.students.partials.inline-status-select', [
                                                        'updateUrl' => route('student.application.update-status', $application),
                                                        'currentStatusId' => $application->status_id,
                                                        'statuses' => $university_statuses,
                                                        'tone' => \App\Support\StatusTone::forStatusId((int) $application->status_id),
                                                    ])
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->language_cer ?? '-' }}</td>
                                            <td>{{ $application->note ?? '-' }}</td>
                                            <td>{{ $application->remarks ?? '-' }}</td>

                                            <td>{{ $university->getMeta('link') ?? '-'}}</td>
                                            <td>
                                                @if($application && (int) $application->portal_type === 1)
                                                    {{ $university->getMeta('user') ?? '' }}
                                                @else
                                                    {{ $application->login_id ?? '' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($application && (int) $application->portal_type === 1)
                                                    {{ $university->getMeta('password') ?? '' }}
                                                @else
                                                    {{ $application->login_password ?? '' }}
                                                @endif
                                            </td>
                                            <td>
                                                -
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route($url.'.show', [$item->id]) }}" class="btn btn-sm btn-info mx-1 rounded" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route($url.'.edit', [$item->id]) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    {{-- <form id="deleteForm{{ $item->id }}" action="{{ route($url.'.destroy', [$item->id]) }}"
                                                        class="d-inline-block" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input type="hidden" name="student_id" value="{{$student->id}}">
                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                        <button id="submitButton" onclick="confirmDelete({{ $item->id }})" type="button" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form> --}}
                                                </div>
                                            </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center">Not found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-center">
                        {{-- {{ $entries->links() }} --}}
                        {{-- @if($type == 'expense')
                            {{ $entries->appends(['perpage' => $perPage, 'type' => 'expense'])->links() }}
                        @elseif($type == 'student')
                            {{ $entries->appends(['perpage' => $perPage, 'student' => $student_id])->links() }}
                        @else
                            {{ $entries->appends(['perpage' => $perPage])->links() }}
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/inline-status-select.js') }}"></script>
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
