@php use Carbon\Carbon; @endphp
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Income</th>
            <th>BDT</th>
            <th>RMB</th>
            <th>Transaction Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entries as $key => $entry)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $entry->income->name ?? '' }}</td>
                <td>{{ $entry->currency == 'BDT' ? number_format($entry->total_amount, 2) . ' ' . $entry->currency : '-' }}</td>
                <td>{{ $entry->currency == 'RMB' ? number_format($entry->total_amount, 2) . ' ' . $entry->currency : '-' }}</td>
                <td>{{ $entry->transaction_date ? Carbon::parse($entry->transaction_date)->format('d-m-Y') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
