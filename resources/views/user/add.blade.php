@extends('layouts.default')

@section('content')
    <form method="POST" action="{{ route('user-management.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h2>{{ $page_title }}</h2>
                </div>
                <div class="card-toolbar">
                    <a href="{{ url('user-management') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-list"></i>Go To User List
                    </a>
                </div>
            </div>
            @include('user.form', ['student' => ''])
        </div>
    </form>
@endsection
