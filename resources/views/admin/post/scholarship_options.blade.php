@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Scholarship options</h1>
                <div class="text-muted mt-1">Dropdown lists used on the scholarship form and the website filters.</div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('scholarships.index') }}">Scholarships</a></li>
                    <li class="breadcrumb-item active">Options</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="alert alert-light border mb-5">
            City still comes from <a href="{{ route('category.index') }}">Cities / Categories</a>. The lists below are everything else.
        </div>

        <div class="row">
            @foreach($groups as $key => $title)
                @php $items = $options[$key] ?? collect(); @endphp
                <div class="col-md-6 col-xl-4 mb-5">
                    <div class="card card-custom h-100">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label mb-0">{{ $title }}</h3>
                            </div>
                            <div class="card-toolbar">
                                <span class="label label-inline label-light-primary font-weight-bold">{{ $items->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap mb-4" style="gap: 8px;">
                                @forelse($items as $option)
                                    <form method="POST" action="{{ route('scholarship-options.destroy', $option) }}" onsubmit="return confirm('Remove {{ $option->label }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-primary font-weight-bold" title="Remove">
                                            {{ $option->label }}
                                            <i class="ki ki-close icon-xs ml-1"></i>
                                        </button>
                                    </form>
                                @empty
                                    <span class="text-muted">No options yet.</span>
                                @endforelse
                            </div>
                            <form method="POST" action="{{ route('scholarship-options.store') }}" class="d-flex">
                                @csrf
                                <input type="hidden" name="group" value="{{ $key }}">
                                <input type="text" name="label" class="form-control mr-2" placeholder="Add {{ strtolower($title) }}" required>
                                <button class="btn btn-primary font-weight-bold px-6">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
