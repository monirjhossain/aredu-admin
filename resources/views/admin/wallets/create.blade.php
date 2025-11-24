@extends('layouts.default')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Add Wallet</div>
        <div class="card-body">
            <form method="POST" action="{{ route('wallets.store') }}">
                @csrf
                {{-- <div class="form-group">
                    <label>User ID (optional)</label>
                    <input type="number" name="user_id" class="form-control">
                </div> --}}
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Currency</label>
                    <select name="currency_id" class="form-control" required>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Initial Balance</label>
                    <input type="number" step="0.01" name="balance" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
