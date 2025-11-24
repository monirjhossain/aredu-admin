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
            @method('PUT') {{-- Needed for update --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="">{{ $title }}</h3>
                        <a href="{{ route($url.'.index', ['type' => 'expense']) }}" class="btn btn-primary">
                            Go Back to {{ $type }} List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @include('includes.errors')
                    <div class="row">
                        <input type="hidden" name="type" value="expense">

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Transaction Date</label>
                                <input type="date" name="transaction_date" value="{{ old('transaction_date', $financeEntry->transaction_date) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="0.00"
                                    value="{{ old('amount', $financeEntry->amount) }}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6" id="studentCategory">
                            <div class="form-group">
                                <label for="office_status">Student (Optional)</label>
                                <select name="student_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($students as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('student_id', $financeEntry->student_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->id . '. ' . $item->full_name . ($item?->passport ? ' - ' . $item->passport : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="office_status">Expense Categorie</label>
                                <select name="categories[]" class="form-control select2" id="kt_select2_2" multiple="multiple">
                                    <option value="">None</option>
                                    @foreach ($expenses as $item)
                                        <option value="{{ $item->id }}"
                                            {{ in_array($item->id, old('categories', $financeEntry->categories ?? [])) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference" class="form-control" placeholder="Reference"
                                    value="{{ old('reference', $financeEntry->reference) }}">
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
                    <button type="submit" class="btn btn-primary">Update Entry</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- The exact same JavaScript as create page --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const typeSelector = document.getElementById('typeSelector');
        const expenseDiv = document.getElementById('expenseCategory');
        const incomeDiv = document.getElementById('incomeCategory');

        function toggleCategoriesMK() {
            if (expenseDiv) expenseDiv.style.display = 'none';
            if (incomeDiv) incomeDiv.style.display = 'none';
        }

        if (typeSelector) {
            typeSelector.addEventListener('change', function () {
                const value = typeSelector.value;
                if (expenseDiv) expenseDiv.style.display = value === 'expense' ? 'block' : 'none';
                if (incomeDiv) incomeDiv.style.display = value === 'income' ? 'block' : 'none';
            });
        }

        window.onload = function () {
            setTimeout(function () {
                toggleCategoriesMK();
            }, 1000);
        };
    });

    var KTSelect2 = function () {
        var demos = function () {
            $('#kt_select2_1').select2({ placeholder: "Select a state" });
            $('#kt_select2_2').select2({ placeholder: "Select a state" });
            $('#kt_select2_3').select2({ placeholder: "Select a state" });
            $('#kt_select2_3_5658965656').select2({ placeholder: "Custom" });
            $('#kt_select2_4').select2({ placeholder: "Select a state", allowClear: true });
        }
        return { init: function () { demos(); } };
    }();
    jQuery(document).ready(function () { KTSelect2.init(); });
</script>
@endsection
