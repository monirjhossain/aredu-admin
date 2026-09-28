@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0 text-dark">Scholarship dropdowns</h1></div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <p class="text-muted">These lists fill the scholarship create/edit form. City still comes from Cities / Categories.</p>
        <div class="row">
            @foreach($groups as $key => $title)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header"><strong>{{ $title }}</strong></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-3">
                                @forelse(($options[$key] ?? collect()) as $option)
                                    <li class="d-flex justify-content-between align-items-center mb-2">
                                        <span>{{ $option->label }}</span>
                                        <form method="POST" action="{{ route('scholarship-options.destroy', $option) }}" onsubmit="return confirm('Remove this option?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-light-danger">Remove</button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-muted">No options yet.</li>
                                @endforelse
                            </ul>
                            <form method="POST" action="{{ route('scholarship-options.store') }}" class="d-flex">
                                @csrf
                                <input type="hidden" name="group" value="{{ $key }}">
                                <input type="text" name="label" class="form-control form-control-sm mr-2" placeholder="Add {{ strtolower($title) }}" required>
                                <button class="btn btn-sm btn-primary">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
