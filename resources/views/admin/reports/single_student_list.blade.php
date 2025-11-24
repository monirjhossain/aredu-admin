@extends('layouts.default')

@section('content')
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
                            <div class="d-flex align-items-center">
                                @php
                                    // if($type == 'expense')

                                @endphp
                                <form method="GET" action="{{ route('finance_entries.index') }}" class="mr-2">
                                    @if($type == 'expense')
                                        <input type="hidden" name="type" value="{{$type}}">
                                    @endif
                                    @if($type == 'student')
                                        <input type="hidden" name="student" value="{{$student_id}}">
                                    @endif
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
                                @if($type == 'expense')
                                    <a href="{{ route('finance_entries.create', ['type' => $type]) }}" class="btn btn-primary">Create Entry</a>
                                @endif
                            </div>
                            {{-- <a href="{{ route('finance_entries.create') }}" class="btn btn-primary">Create Entry</a> --}}
                        </div>
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
                                    </tr>
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
