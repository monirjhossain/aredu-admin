@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }} - {{ $totalAmount }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <form id="filterForm" action="{{ route($url.'.index') }}" method="GET" class="w-100">
                    @if($request->student)
                        <input type="hidden" name="student" value="{{ $request->student }}">
                    @endif
                    <input type="hidden" name="perpage" value="{{ request('perpage', 20) }}">
                    <div class="d-flex flex-nowrap align-items-center" style="gap: 8px; overflow-x: auto; white-space: nowrap; padding-bottom: 4px;">
                        <input type="text" name="search" placeholder="Search invoice, student, phone, passport, user"
                            class="form-control" style="min-width: 280px; width: 280px; flex: 0 0 auto;" value="{{ $request->search }}">
                        <select name="branch_id" class="form-control" style="width: 180px; flex: 0 0 auto;">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (string) $request->branch_id === (string) $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="user_id" class="form-control" style="width: 220px; flex: 0 0 auto;">
                            <option value="">All Collectors</option>
                            @foreach($collectors as $collector)
                                <option value="{{ $collector->id }}" {{ (string) $request->user_id === (string) $collector->id ? 'selected' : '' }}>
                                    {{ $collector->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="date" name="date_from" class="form-control" style="width: 170px; flex: 0 0 auto;" value="{{ $request->date_from }}">
                        <input type="date" name="date_to" class="form-control" style="width: 170px; flex: 0 0 auto;" value="{{ $request->date_to }}">
                    </div>
                </form>
            </div>
            <div class="card-toolbar">
                <form method="GET" action="{{ route($url.'.index') }}" class="mr-1">
                    @foreach(request()->except(['perpage', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="perpage" class="form-control" onchange="this.form.submit()">
                        <option value="5" {{ request('perpage', 20) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('perpage', 20) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perpage', 20) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perpage', 20) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perpage', 20) == 100 ? 'selected' : '' }}>100</option>
                        <option value="200" {{ request('perpage', 20) == 200 ? 'selected' : '' }}>200</option>
                        <option value="500" {{ request('perpage', 20) == 500 ? 'selected' : '' }}>500</option>
                    </select>
                </form>
                <a onclick="submitDetailsForm()" class="btn btn-primary mr-1"> <i class="la la-search"></i>Search </a>
                <a href="{{ route($url.'.export', request()->except('page')) }}" class="btn btn-success mr-1"><i class="la la-download"></i> Export Excel</a>
                <a href="{{ route($url.'.index') }}{{ $request->student ? '?student='.$request->student : '' }}" class="btn btn-light-danger mr-1">Reset</a>
                
                @if ($student)
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-primary mr-1"> <i class="fas fa-eye"></i>Details</a>
                <a href="{{ route('students.edit', [$student->id]) }}" class="btn btn-primary mr-1" title="Edit">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                @endif
                <a href="{{ route($url.'.create') }}{{$request->student ? '?student='.$request->student :'' }}" class="btn btn-primary"> <i class="la la-plus"></i> Create {{ $type }}</a>
            </div>
        </div>
        
        <div class="card-body" style="overflow: scroll; ">
            <table class="table table-hover">
                <thead class="thead-light ">
                    <tr>
                        <th style="width: 10px"> # </th>
                        <th>Invoice</th>
                        <th class="min-w-300px">Student Details</th>
                        {{-- <th>Study Level</th>
                        <th>Subject</th> --}}
                        <th>Sub-Total</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Currency</th>
                        <th>Date</th>
                        <th>Add By</th>
                        <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>


                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->invoice_no }}</td>
                            @if($item->student == null || $item->student == '')
                            <td>
                                {{-- @dd($item->products['student']['email']) --}}
                                <span><b>Name:</b> {{ $item->products['student']['full_name'] ?? '' }}</span><br>
                                {{-- <span><b>Email:</b> {{ $item->products['student']['email'] ?? '' }}</span><br> --}}
                                <span><b>Phone:</b> {{ $item->products['student']['mobile_no'] ?? '' }}</span><br>
                                {{-- <span><b>Address:</b> {{ $item->products['student']['address'] ?? '' }}</span><br> --}}
                                {{-- <span><b>City:</b> {{ $item->student->city ?? '' }}</span><br>
                                <span><b>Country:</b> {{ $item->student->country ?? '' }}</span><br>
                                <span><b>ZIP:</b> {{ $item->student->zip ?? '' }}</span><br> --}}
                            </td>
                            @else
                            <td>
                                <span><b>Name:</b> {{ $item->student->full_name ?? '' }}</span><br>
                                {{-- <span><b>Email:</b> {{ $item->student->email ?? '' }}</span><br> --}}
                                <span><b>Phone:</b> {{ $item->student->mobile_no ?? '' }}</span><br>
                                {{-- <span><b>Address:</b> {{ $item->student->address ?? '' }}</span><br> --}}
                                {{-- <span><b>City:</b> {{ $item->student->city ?? '' }}</span><br>
                                <span><b>Country:</b> {{ $item->student->country ?? '' }}</span><br>
                                <span><b>ZIP:</b> {{ $item->student->zip ?? '' }}</span><br> --}}
                            </td>
                            @endif
                            {{-- <td>{{ $item->student->study_level ?? '' }}</td>
                            <td>{{ $item->student->subject ?? '' }}</td> --}}
                            <td>{{ $item->subtotal}}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->grandtotal }}</td>
                            <td><b>{{ $item->formatted_paid }}</b></td>
                            <td>{{ $item->due }}</td>
                            <td>{{ $item->currency }}</td>
                            <td>{{ $item->invoice_date }}</td>
                            <td>
                                {{ $item->user->name ?? '' }}
                            </td>


                            <td class="d-flex">
                                <a href="{{ route($url.'.edit', [$item->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
                                <a target="_blank" href="{{ route($url.'.show', [$item->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-eye"></i> </a>
                                <form id="deleteForm{{ $item->id }}" action="{{ route($url.'.destroy', [$item->id]) }}"
                                    class="mr-1" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button id="submitButton" onclick="confirmDelete({{ $item->id }})" type="button" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
                                <h5 class="text-center">No {{$type}} found.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- {!! $invoices->links() !!} --}}
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitDetailsForm() {
            $("#filterForm").submit();
        }
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
