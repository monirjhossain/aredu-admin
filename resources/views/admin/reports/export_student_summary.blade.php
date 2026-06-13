<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Description</th>
            <th>Reference</th>
            <th>Income BDT</th>
            <th>Income RMB</th>
            <th>Expense BDT</th>
            <th>Expense RMB</th>
            <th>Total Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entries as $key => $entry)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $entry->student->st_office_id ?? '' }}</td>
                <td>{{ $entry->student->full_name ?? '' }}</td>
                <td>{{ $entry->description }}</td>
                <td>{{ $entry->reference }}</td>
                <td>{{ $entry->income_bdt }}</td>
                <td>{{ $entry->income_rmb }}</td>
                <td>{{ $entry->expense_bdt }}</td>
                <td>{{ $entry->expense_rmb }}</td>
                <td>{{ ($entry->income_bdt - $entry->expense_bdt) + ($entry->income_rmb - $entry->expense_rmb) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
