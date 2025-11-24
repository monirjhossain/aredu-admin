@extends('layouts.blank')

@section('content')
<style>
    body {
        font-size: 10px !important;
    }
</style>
<div class="card card-custom">
        <table class="table table-hover">
            <thead class="thead-light ">
                <tr>
                    <th style="width: 10px"> # </th>
                    <th>Name</th>
                    <th>Study Level</th>
                    <th>Subject</th>
                    <th class="min-w-300px">Application Info</th>
                    <th class="min-w-300px">Student Details</th>
                    <th class="min-w-300px">Student Parents</th>
                    {{-- <th>Responsible</th> --}}
                    <th>Remarks</th>
                    {{-- <th>Office Status</th> --}}
                    {{-- <th>Add By</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->full_name }}</td>
                        <td>{{ $item->study_level }}</td>
                        <td>{{ $item->subject }}</td>
                        <td>
                            <span><b>Study Level:</b> {{ $item->study_level }}</span><br>
                            <span><b>Passport:</b> {{ $item->passport }}</span><br>
                            <span><b>Preferred Subject:</b> {{ $item->subject }}</span><br>
                            <span><b>Preferred University:</b> {{ $item->university->post_title ?? '' }}</span><br>
                            <span><b>Preferred Country:</b> {{ $item->preferred_country }}</span><br>
                            <span><b>SSC Result:</b> {{ $item->ssc_result }}</span><br>
                            <span><b>Hsc Result:</b> {{ $item->hsc_result }}</span><br>
                            <span><b>Last Education:</b> {{ $item->last_education }}</span><br>
                            <span><b>note:</b> {{ $item->note }}</span><br>
                        </td>
                        <td>
                            <span><b>Email:</b> {{ $item->email }}</span><br>
                            <span><b>Phone:</b> {{ $item->mobile_no }}</span><br>
                            <span><b>Address:</b> {{ $item->address }}</span><br>
                            <span><b>City:</b> {{ $item->city }}</span><br>
                            <span><b>Country:</b> {{ $item->country }}</span><br>
                            <span><b>ZIP:</b> {{ $item->zip }}</span><br>
                        </td>
                        <td>
                            <span><b>Father:</b> {{ $item->father_name }}</span><br>
                            <span><b>Phone:</b> {{ $item->father_mobile_no }}</span><br>
                            <span><b>Occupation:</b> {{ $item->father_occoupation }}</span><br>
                            <span><b>mother:</b> {{ $item->mother_name }}</span><br>
                            <span><b>Phone:</b> {{ $item->mother_mobile_no }}</span><br>
                            <span><b>Occupation:</b> {{ $item->mother_occoupation }}</span><br>
                        </td>
                        {{-- <td>{{ $item->responsible_person }}</td> --}}
                        <td>{{ $item->note }}</td>
                        {{-- <td>
                            <span class="badge {{ $item->status == '1'? 'badge-primary': 'badge-danger' }}">{{ $item->status == '1'? 'Active': 'Deactive' }} </span>
                        </td> --}}
                        {{-- <td>
                            {{ $item->office_status }}
                        </td>
                        <td>
                            {{ $item->user->name ?? '' }}
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <h5 class="text-center">No student found.</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
    <script>
        function submitDetailsForm(type) {
            if(type == 'yes'){
                $('#searchForm #print').val('yes');
            }else{
                $('#searchForm #print').val('no');
            }
            $("#searchForm").submit();
        }
    </script>
@endsection
