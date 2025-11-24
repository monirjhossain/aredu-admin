@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid d-flex justify-content-between">
        <h1 class="m-0 text-dark">Transactions - Wallet #{{ $wallet->id }} ({{ $wallet->currency->code }})</h1>
        <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Back to Wallets</a>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Reference</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $txn)
                            <tr>
                                <td>{{ $txn->id }}</td>
                                <td>
                                    <span class="badge {{ $txn->type === 'credit' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($txn->type) }}
                                    </span>
                                </td>
                                <td>{{ number_format($txn->amount, 2) }}</td>
                                <td>{{ number_format($txn->balance_after, 2) }}</td>
                                <td>{{ $txn->description ?? '-' }}</td>
                                <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
