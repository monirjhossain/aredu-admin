@extends('layouts.default')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Exchange Rates for {{ $currency->code }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('currencies.rates.store', $currency->id) }}" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <input type="number" step="0.000001" name="rate" class="form-control" placeholder="Rate" required>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Add Rate</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Rate</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->rate }}</td>
                    <td>{{ $rate->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $rates->links() }}
    </div>
</div>
@endsection
