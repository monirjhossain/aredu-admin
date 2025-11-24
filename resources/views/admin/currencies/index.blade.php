@extends('layouts.default')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3>Currencies</h3>
        <a href="{{ route('currencies.create') }}" class="btn btn-primary">Add Currency</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Symbol</th>
                    <th>Base</th>
                    <th>Latest Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currencies as $currency)
                <tr>
                    <td>{{ $currency->code }}</td>
                    <td>{{ $currency->name }}</td>
                    <td>{{ $currency->symbol }}</td>
                    <td>{{ $currency->is_base ? 'Yes' : 'No' }}</td>
                    <td>{{ $currency->latestRate->rate ?? '-' }}</td>
                    <td>
                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <a href="{{ route('currencies.rates', $currency->id) }}" class="btn btn-sm btn-success">Rates</a>
                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this currency?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $currencies->links() }}
    </div>
</div>
@endsection
