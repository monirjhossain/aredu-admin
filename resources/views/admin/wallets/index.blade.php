@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid d-flex justify-content-between">
        <h1 class="m-0 text-dark">Wallets</h1>
        <a href="{{ route('wallets.create') }}" class="btn btn-primary">Add Wallet</a>
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
                            <th>Name</th>
                            <th>Currency</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $wallet)
                            <tr>
                                <td>{{ $wallet->id ?? '-' }}</td>
                                <td>{{ $wallet->name }}</td>
                                <td>{{ $wallet->currency->code }}</td>
                                <td>{{ number_format($wallet->balance, 2) }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('wallets.edit', $wallet->id) }}" class="btn btn-sm btn-primary mr-1"><i class="fas fa-edit"></i></a>
                                    {{-- <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></button>
                                    </form> --}}
                                    <a href="{{ route('wallets.transactions.index', $wallet->id) }}" class="btn btn-sm btn-info ml-1"><i class="fas fa-list"></i> Txns</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $wallets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
