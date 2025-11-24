@extends('layouts.default')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Edit Currency - {{ $currency->code }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('currencies.update', $currency->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="code">Code (ISO)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $currency->code) }}" required>
                </div>
                <div class="form-group">
                    <label for="name">Currency Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $currency->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="symbol">Symbol</label>
                    <input type="text" name="symbol" class="form-control" value="{{ old('symbol', $currency->symbol) }}">
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_base" class="form-check-input" id="is_base" value="1" {{ $currency->is_base ? 'checked' : '' }}>
                    <label for="is_base" class="form-check-label">Set as Base Currency</label>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
                <a href="{{ route('currencies.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
