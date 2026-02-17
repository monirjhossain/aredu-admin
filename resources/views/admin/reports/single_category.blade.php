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
                        <h3 class="mb-0 font-weight-bold">Category-wise Expense Reports</h3>
                    </div>
                    <div class="card-body bg-light">
                        <div class="mb-4 p-3 bg-white rounded shadow-sm border">
                            <form method="GET" action="{{ route($url.'.reports.index') }}" class="w-100">
                                <div class="row align-items-end g-2">
                                    <input type="hidden" name="type" value="expense_summary">
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="start_date" class="form-label font-weight-bold">From</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="end_date" class="form-label font-weight-bold">To</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                    </div>
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="category_id" class="form-label font-weight-bold">Category</label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
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
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>BDT</th>
                                    <th>RMB</th>
                                    <th>Transaction Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $bdt = 0; $rmb = 0; @endphp
                                @forelse ($entries as $key=>$entry)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $entry->description }}</td>
                                        <td>{{ $entry->ref }}</td>
                                        <td>{{ $entry->currency == 'BDT' ? number_format($entry->amount, 2) . ' ' . $entry->currency : '-' }}</td>
                                        <td>{{ $entry->currency == 'RMB' ? number_format($entry->amount, 2) . ' ' . $entry->currency : '-' }}</td>
                                        <td>{{ $entry->transaction_date ?? '-' }}</td>
                                        <td>
                                            <!-- You can add action buttons here if needed -->
                                        </td>
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
                    <div class="card-footer bg-transparent border-0 mt-4 d-flex justify-content-start">
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm" style="display:inline-flex;align-items:center;gap:4px;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
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
