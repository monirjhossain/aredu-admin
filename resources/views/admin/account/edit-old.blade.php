{{-- @extends('layouts.default')
@section('content')
<h1>Edit Finance Entry</h1>

<form action="{{ route('finance_entries.update', $financeEntry->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.account.form', ['financeEntry' => $financeEntry])
</form>
@endsection --}}

@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="{{ route('finance_entries.update', $financeEntry->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="">{{ $title }}</h3>
                        <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{ $type }} List</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('includes.errors')
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="income" {{ old('type', $financeEntry->type) == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="expense" {{ old('type', $financeEntry->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Transaction Date</label>
                                <input type="date" name="transaction_date" value="{{ old('transaction_date', $financeEntry->transaction_date) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" value="{{ old('amount', $financeEntry->amount) }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference" value="{{ old('reference', $financeEntry->reference) }}" class="form-control" placeholder="Reference">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Write description here...">{{ old('description', $financeEntry->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Update Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

