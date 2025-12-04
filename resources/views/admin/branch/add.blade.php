@extends('layouts.default')

@section('content')
    <form method="POST" action="{{ route('branches.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h2>{{ $page_title }}</h2>
                </div>
                <div class="card-toolbar">
                    <a href="{{ url('branches') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-list"></i>Go To Branch List
                    </a>
                </div>
            </div>
            @include('admin.branch.form', ['student' => ''])
        </div>
    </form>
@endsection
