@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- <h1 class="m-0 text-dark">{{$title}}</h1> --}}
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
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0 font-weight-bold">Student-wise Income Cost</h3>
                    </div>
                    <div class="card-body bg-light">
                        <div class="mb-4 p-3 bg-white rounded shadow-sm border">
                            <form method="GET" action="{{ route($url.'.reports.index') }}" class="w-100">
                                <input type="hidden" name="type" value="student_summary">
                                <div class="row align-items-end g-2">
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="start_date" class="form-label font-weight-bold">From</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="end_date" class="form-label font-weight-bold">To</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="student_id" class="form-label font-weight-bold">Student</label>
                                        <select name="student_id" id="student_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2 d-flex">
                                        <button type="submit" class="btn btn-primary flex-fill me-2" style="margin-right:8px;">Filter</button>
                                        <a href="{{ route($url.'.reports.index', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success flex-fill">Export Excel</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    <div class="card-body" style="overflow: scroll;">
                        <table class="table table-hover mb-6">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>student</th>
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>Income BDT</th>
                                    <th>Income RMB</th>
                                    <th>Expense BDT</th>
                                    <th>Expense RMB</th>
                                    <th>Total Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $bdt = 0; $rmb = 0; @endphp
                                @forelse ($entries as $key=>$entry)
                                                                        <tr>
                                                                            <td>{{ $key+1 }}</td>
                                                                            <td>
                                                                                <a href="{{ route('finance_entries.index') }}?student={{ $entry->student_id }}">
                                                                                    {{ $entry->student->full_name ?? '' }}
                                                                                </a>
                                                                            </td>
                                                                            <td>{{ $entry->description }}</td>
                                                                            <td>{{ $entry->reference }}</td>
                                                                            <td>{{ $entry->currency == 'BDT' ? number_format($entry->income_bdt, 2) . ' ' . $entry->currency : '-' }}</td>
                                                                            <td>{{ $entry->currency == 'RMB' ? number_format($entry->income_rmb, 2) . ' ' . $entry->currency : '-' }}</td>
                                                                            <td>{{ $entry->currency == 'BDT' ? number_format($entry->expense_bdt, 2) . ' ' . $entry->currency : '-' }}</td>
                                                                            <td>{{ $entry->currency == 'RMB' ? number_format($entry->expense_rmb, 2) . ' ' . $entry->currency : '-' }}</td>
                                                                            <td>
                                                                                {{ number_format(($entry->income_bdt - $entry->expense_bdt) + ($entry->income_rmb - $entry->expense_rmb), 2) }}
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center" data-toggle="modal" data-target="#detailsModal{{ $entry->student_id }}" style="gap:4px;">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/>
                                                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                                                                                    </svg>
                                                                                    <span>Details</span>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                        <!-- Details Modal -->
                                                                        <div class="modal fade" id="detailsModal{{ $entry->student_id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $entry->student_id }}" aria-hidden="true">
                                                                            <div class="modal-dialog modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="detailsModalLabel{{ $entry->student_id }}">Costing & Revenue Details - {{ $entry->student->full_name ?? '' }}</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <ul class="list-group">
                                                                                            <li class="list-group-item">
                                                                                                <strong>BDT</strong><br>
                                                                                                Income: {{ number_format($entry->income_bdt, 2) }} BDT<br>
                                                                                                Expense: {{ number_format($entry->expense_bdt, 2) }} BDT<br>
                                                                                                <strong>Net Balance:</strong> {{ number_format($entry->income_bdt - $entry->expense_bdt, 2) }} BDT
                                                                                            </li>
                                                                                            <li class="list-group-item">
                                                                                                <strong>RMB</strong><br>
                                                                                                Income: {{ number_format($entry->income_rmb, 2) }} RMB<br>
                                                                                                Expense: {{ number_format($entry->expense_rmb, 2) }} RMB<br>
                                                                                                <strong>Net Balance:</strong> {{ number_format($entry->income_rmb - $entry->expense_rmb, 2) }} RMB
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                    @php 
                                        if($entry->currency == 'BDT'){
                                            $bdt += $entry->amount;
                                        }else{
                                            $rmb += $entry->amount;
                                        }
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Finance Entries found.</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="4">#</td>
                                    
                                    <td>{{ number_format($bdt, 2) . ' BDT' }}</td>
                                    <td>{{ number_format($rmb, 2) . ' RMB' }}</td>
                                </tr>


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
