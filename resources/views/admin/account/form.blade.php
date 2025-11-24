<div>
    <label>Type</label>
    <select name="type" required>
        <option value="income" {{ old('type', $financeEntry->type ?? '') == 'income' ? 'selected' : '' }}>Income</option>
        <option value="expense" {{ old('type', $financeEntry->type ?? '') == 'expense' ? 'selected' : '' }}>Expense</option>
    </select>
</div>

<div>
    <label>Amount</label>
    <input type="number" step="0.01" name="amount" value="{{ old('amount', $financeEntry->amount ?? '') }}" required>
</div>

<div>
    <label>Transaction Date</label>
    <input type="date" name="transaction_date" value="{{ old('transaction_date', $financeEntry->transaction_date ?? '') }}" required>
</div>

<div>
    <label>Student</label>
    <select name="student_id">
        <option value="">None</option>
        @foreach($students as $student)
        <option value="{{ $student->id }}" {{ old('student_id', $financeEntry->student_id ?? '') == $student->id ? 'selected' : '' }}>
            {{ $student->id .". ". $student->full_name }}
        </option>
        @endforeach
    </select>
</div>

<div>
    <label>Reference</label>
    <input type="text" name="reference" value="{{ old('reference', $financeEntry->reference ?? '') }}">
</div>

<div>
    <label>Description</label>
    <textarea name="description">{{ old('description', $financeEntry->description ?? '') }}</textarea>
</div>

<button type="submit">Save</button>
