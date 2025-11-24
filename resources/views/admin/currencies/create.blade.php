@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add Currency</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Currencies</a></li>
                    <li class="breadcrumb-item active">Add Currency</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">New Currency</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('currencies.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="code">Code (ISO)</label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="e.g. USD" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Currency Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. US Dollar" required>
                            </div>

                            <div class="form-group">
                                <label for="symbol">Symbol</label>
                                <input type="text" class="form-control" id="symbol" name="symbol" value="{{ old('symbol') }}" placeholder="e.g. $" >
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="is_base" name="is_base" value="1" {{ old('is_base') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_base">Set as Base Currency</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('currencies.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
