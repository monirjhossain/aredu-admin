@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid d-flex justify-content-between">
        <h1 class="m-0 text-dark">Wallet Transfer</h1>
        <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Back to Wallets</a>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('wallets.transfer') }}">
                    @csrf
                    <div class="form-group">
                        <label>From Wallet</label>
                        <select name="from_wallet_id" class="form-control" required>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">
                                    #{{ $wallet->id }} - {{ $wallet->currency->code }} 
                                    (Balance: {{ number_format($wallet->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>To Wallet</label>
                        <select name="to_wallet_id" class="form-control" required>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">
                                    #{{ $wallet->id }} - {{ $wallet->currency->code }} 
                                    (Balance: {{ number_format($wallet->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Reference</label>
                        <input type="text" name="reference" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Transfer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
