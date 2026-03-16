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
                <form id="searchForm" action="{{ route('students.index') }}" method="get">
                    <input type="text" name="search" placeholder="Search" class="form-control"
                        value="{{ $request->search }}">
                </form>
            </div>
            <div class="card-toolbar">
                <a onclick="submitDetailsForm()" class="btn btn-primary mr-1"> <i class="la la-search"></i>Search </a>
                <a href="{{ route($url.'.create') }}{{$request->student ? '?student='.$request->student :'' }}" class="btn btn-primary"> <i class="la la-plus"></i> Create {{ $type }}</a>
            </div>
            <div class="d-flex align-items-center">
                                        <form method="GET" action="{{ route('finance_entries.invoices.index') }}" class="mr-2">
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
            {{ $invoices->appends(['perpage' => $perPage])->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitDetailsForm() {
            $("#searchForm").submit();
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
