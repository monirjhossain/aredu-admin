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
                            <h2>AR Education</h2>
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
                                    <th>Date</th>
                                    @if ($type != 'expense')
                                        <th>Type</th>
                                    @endif
                                    <th>Student</th>
                                    {{-- @if($type == 'expense') --}}
                                    <th>Categories</th>
                                    {{-- @endif --}}
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>Amount</th>
                                    @if ($type != 'expense')
                                        <th>Balance BDT</th>
                                        <th>Balance RMB</th>
                                    @endif
                                    {{-- <th>USD</th> --}}
                                    {{-- @if ($type != 'expense' && $openingBalanceBDT != 0)
                                        <th>BDT</th>
                                    @endif
                                    @if ($type != 'expense' && $openingBalanceRMB != 0)
                                        <th>RMB</th>
                                    @endif
                                    @if ($type != 'expense' && $openingBalanceUSD != 0)
                                        <th>USD</th>
                                    @endif --}}
                                    <th>Created By</th>
                                    @if($type == 'expense')
                                        <th class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if ($type != 'expense'){
                                        $openingBalanceBDT = $openingBalanceBDT;
                                        $openingBalanceRMB = $openingBalanceRMB;
                                        // $openingBalanceUSD = $openingBalanceUSD;
                                    }

                                    // @if ($type != 'expense' && $openingBalanceBDT != 0)
                                    //     <th>BDT</th>
                                    // @endif
                                    // @if ($type != 'expense' && $openingBalanceRMB != 0)
                                    //     <th>RMB</th>
                                    // @endif
                                    // @if ($type != 'expense' && $openingBalanceUSD != 0)
                                    //     <th>USD</th>
                                    // @endif


                                @endphp
                                @if ($type != 'expense')
                                    @if ($openingBalanceBDT != 0 || $openingBalanceRMB != 0 )
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><strong>Opening Balance</strong></td>
                                            <td>-</td>
                                            <td>-</td>
                                            @if ($type != 'expense')
                                                <td><strong>{{ number_format($openingBalanceBDT, 2) }}</strong></td>
                                                <td><strong>{{ number_format($openingBalanceRMB, 2) }}</strong></td>
                                            @endif
                                            {{-- <td><strong>{{ number_format($openingBalanceUSD, 2) }}</strong></td> --}}
                                            <td>-</td>
                                            {{-- <td>-</td> --}}
                                        </tr>
                                    @endif
                                @endif


                                @forelse ($entries as $entry)
                                @php
                                    if ($type != 'expense' && $entry->currency == 'BDT'){
                                        if ($entry->type === 'income') {
                                            $openingBalanceBDT += $entry->amount;
                                        } else {
                                            $openingBalanceBDT -= $entry->amount;
                                        }
                                    }
                                    if ($type != 'expense' && $entry->currency == 'RMB'){
                                        if ($entry->type === 'income') {
                                            $openingBalanceRMB += $entry->amount;
                                        } else {
                                            $openingBalanceRMB -= $entry->amount;
                                        }
                                    }
                                    // if ($type != 'expense' && $entry->currency == 'USD'){
                                    //     if ($entry->type === 'income') {
                                    //         $openingBalanceUSD += $entry->amount;
                                    //     } else {
                                    //         $openingBalanceUSD -= $entry->amount;
                                    //     }
                                    // }
                                @endphp
                                    <tr>
                                        <td>{{ $entry->id }}</td>
                                        <td>{{ $entry->transaction_date }}</td>
                                        @if ($type != 'expense')
                                        <td>
                                            <span class="badge {{ $entry->type == 'income' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst($entry->type) }}
                                            </span>
                                        </td>
                                        @endif
                                        <td>{{ $entry->student->full_name ?? '-' }}</td>
                                        {{-- @if ($type == 'expense')
                                        <td>
                                            @foreach($entry->categories as $category)
                                            @php
                                                $expense = collect($expenses)->firstWhere('id', $category);
                                                if($expense == null || $expense == ''){
                                                    continue;
                                                }
                                            @endphp
                                                <span class="badge badge-primary">{{ $expense->name }} </span>
                                            @endforeach
                                        </td>
                                        @endif --}}
                                        <td>
                                            <span class="badge badge-primary">
                                                @if($entry->type == 'expense')
                                                    {{ $entry->expense->name }} 
                                                @else
                                                    {{ $entry->income->name }}
                                                @endif
                                            </span>

                                        </td>

                                        <td>{{ $entry->description }}</td>
                                        <td>{{ $entry->reference }}</td>
                                        <td>{{ number_format($entry->amount, 2)  . ' ' . $entry->currency}}</td>
                                        @if ($type != 'expense')
                                            <td><strong>{{ number_format($openingBalanceBDT, 2)  . ' BDT'}}</strong></td>
                                            <td><strong>{{ number_format($openingBalanceRMB, 2)  . ' RMB'}}</strong></td>
                                        @endif
                                        {{-- <td><strong>{{ number_format($openingBalanceUSD, 2)  . ' USD'}}</strong></td> --}}

                                        {{-- @if ($type != 'expense' && $entry->currency == 'BDT')
                                            <td><strong>{{ number_format($openingBalanceBDT, 2)  . ' ' . $entry->currency}}</strong></td>
                                        @endif
                                        @if ($type != 'expense' && $entry->currency == 'RMB')
                                            <td><strong>{{ number_format($openingBalanceRMB, 2)  . ' ' . $entry->currency}}</strong></td>
                                        @endif
                                        @if ($type != 'expense' && $entry->currency == 'USD')
                                            <td><strong>{{ number_format($openingBalanceUSD, 2)  . ' ' . $entry->currency}}</strong></td>
                                        @endif --}}


                                        <td>{{ $entry->creator->name ?? '-' }}</td>
                                        @if($type == 'expense')
                                            <td class="d-flex justify-content-center">
                                                <a href="{{ route('finance_entries.edit', [$entry->id, 'type' => 'expense']) }}" class="btn btn-sm btn-primary mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form id="deleteForm{{ $entry->id }}" action="{{ route('finance_entries.destroy', $entry->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="type" value="expense">
                                                    <button id="submitButton" onclick="confirmDelete({{ $entry->id }})" class="btn btn-sm btn-danger" type="button">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Finance Entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-center">
                        {{-- {{ $entries->links() }} --}}
                        @if($type == 'expense')
                            {{ $entries->appends(['perpage' => $perPage, 'type' => 'expense'])->links() }}
                        @elseif($type == 'student')
                            {{ $entries->appends(['perpage' => $perPage, 'student' => $student_id])->links() }}
                        @else
                            {{ $entries->appends(['perpage' => $perPage])->links() }}
                        @endif
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
