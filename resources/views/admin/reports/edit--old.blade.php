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
                        {{-- http://127.0.0.1:8000/finance_entries?type=expense --}}
                        <a href="{{ route($url.'.index', ['type'=> 'expense']) }}" class="btn btn-primary">Go Back to {{ $type }} List</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('includes.errors')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control" required id="typeSelector">
                                    <option value="">Select Type</option>
                                    <option value="income" {{ $financeEntry->type == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="expense" {{ $financeEntry->type == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Transaction Date</label>
                                <input type="date" name="transaction_date" value="{{ $financeEntry->transaction_date }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" value="{{ $financeEntry->amount }}" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        @php
                            $selectedCategories = collect(json_decode($financeEntry->categories));
                        @endphp

                        <div class="col-lg-6 col-md-6 col-sm-12" id="expenseCategory" style="display:none;">
                            <div class="form-group">
                                <label>Expense Categorie</label>
                                <select name="categories[]" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($expenses as $item)
                                        <option value="{{ $item->id }}" {{ $selectedCategories->contains($item->id) && $financeEntry->type == 'expense' ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12" id="incomeCategory" style="display:none;">
                            <div class="form-group">
                                <label>Income Categorie</label>
                                <select name="categories[]" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($products as $item)
                                        <option value="{{ $item->id }}" {{ $selectedCategories->contains($item->id) && $financeEntry->type == 'income' ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12" id="studentCategory">
                            <div class="form-group">
                                <label>Student (Optional)</label>
                                <select name="student_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($students as $item)
                                        <option value="{{ $item->id }}" {{ $financeEntry->student_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->id . '. ' . $item->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ $financeEntry->reference }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ $financeEntry->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const typeSelector = document.getElementById('typeSelector');
        const expenseDiv = document.getElementById('expenseCategory');
        const incomeDiv = document.getElementById('incomeCategory');

        function toggleCategories() {
            const value = typeSelector.value;
            expenseDiv.style.display = value === 'expense' ? 'block' : 'none';
            incomeDiv.style.display = value === 'income' ? 'block' : 'none';
        }

        typeSelector.addEventListener('change', toggleCategories);
        toggleCategories(); // trigger on page load
    });
</script>
@endsection
