@extends('layouts.default')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Edit Wallet #{{ $wallet->id }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('wallets.update', $wallet->id) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $wallet->name }}" required>
                </div>
                {{-- <div class="form-group">
                    <label>Currency</label>
                    <select name="currency_id" class="form-control" required>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ $wallet->currency_id == $currency->id ? 'selected' : '' }}>
                                {{ $currency->code }} - {{ $currency->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                
                {{-- <div class="form-group">
                    <label>Balance</label>
                    <input type="number" step="0.01" name="balance" class="form-control" value="{{ $wallet->balance }}" required>
                </div> --}}
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
