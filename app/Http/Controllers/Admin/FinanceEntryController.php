<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account\FinanceEntry;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class FinanceEntryController extends Controller
{

    /**
     * Export filtered finance entries to Excel.
     */
    public function export(Request $request)
    {
        // You must have maatwebsite/excel installed for this to work
        // composer require maatwebsite/excel
        $type = $request->get('type', '');
        $studentId = $request->get('student_id', '');
        $categoryId = $request->get('category_id', '');
        $categoryType = null;
        $categoryRealId = null;
        if ($categoryId && strpos($categoryId, '_') !== false) {
            [$categoryType, $categoryRealId] = explode('_', $categoryId, 2);
        }
        $createdBy = $request->get('created_by', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');

        $entries = FinanceEntry::orderBy('transaction_date', $type == 'expense' ? 'desc' : 'asc')
            ->when($type === 'expense' || $type === 'income', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($studentId, function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->when($categoryRealId, function ($query) use ($categoryRealId) {
                $query->where('category_id', $categoryRealId);
            })
            ->when($createdBy, function ($query) use ($createdBy) {
                $query->where('created_by', $createdBy);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('transaction_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('transaction_date', '<=', $endDate);
            })
            ->get();

        $exportData = $entries->map(function($entry) {
            return [
                'ID' => $entry->id,
                'Date' => $entry->transaction_date,
                'Type' => $entry->type,
                'Student' => $entry->student->full_name ?? '',
                'Category' => $entry->expense->name ?? $entry->income->name ?? '',
                'Description' => $entry->description,
                'Reference' => $entry->reference,
                'Amount' => $entry->amount,
                'Currency' => $entry->currency,
                'Created By' => $entry->creator->name ?? '',
            ];
        });

        // Use Laravel Excel 3.x+ to export
        // If not installed: composer require maatwebsite/excel
        // Create a simple export class on the fly
        $export = new class($exportData) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return collect($this->data); }
            public function headings(): array { return array_keys($this->data->first() ?? []); }
        };
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'FinanceEntriesReport.xlsx');
    }
    
    public function index(Request $request)
    {
        // For filter dropdowns
        $data['students'] = \App\Models\Student\Student::orderBy('first_name')->get();
        $data['users'] = \App\Models\User::orderBy('name')->get();
        // return $request->student;  
        $perPage = $request->get('perpage', 15);
        $type = $request->get('type', '');
        $studentId = $request->get('student_id', '');
        $categoryId = $request->get('category_id', '');
        $categoryType = null;
        $categoryRealId = null;
        if ($categoryId && strpos($categoryId, '_') !== false) {
            [$categoryType, $categoryRealId] = explode('_', $categoryId, 2);
        }
        $createdBy = $request->get('created_by', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');

        $order = 'asc';
        if($type == 'expense'){
            $order = 'desc';
        }
        $data['entries'] = FinanceEntry::orderBy('transaction_date', $order)
            ->when($type === 'expense' || $type === 'income', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($studentId, function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->when($categoryRealId, function ($query) use ($categoryRealId) {
                $query->where('category_id', $categoryRealId);
            })
            ->when($createdBy, function ($query) use ($createdBy) {
                $query->where('created_by', $createdBy);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('transaction_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('transaction_date', '<=', $endDate);
            });
        $currentUser = Auth::user();
        $data['entries'] = $data['entries']->where('branch_id', $currentUser->branch);
        $data['entries'] = $data['entries']->paginate($perPage);
        $data['openingBalanceBDT']=0;
        $data['openingBalanceRMB']=0;
        $data['openingBalanceUSD']=0;
        if($type != 'expense' && count($data['entries']) !=0){
            $firstRow = $data['entries']->first();
            // Calculate opening balance (before first row)
            $financeEntry = FinanceEntry::where(function($q) use ($firstRow) {
                    $q->where('transaction_date', '<', $firstRow->transaction_date)
                    ->orWhere(function($q2) use ($firstRow) {
                        $q2->where('transaction_date', $firstRow->transaction_date)
                            ->where('id', '<', $firstRow->id);
                    });
                });
                if($request->student){
                    $financeEntry->where('student_id', $request->student);
                }
                $currentUser = Auth::user();
                if($currentUser->role_id == 4){
                    $financeEntry = $financeEntry->where('created_by', $currentUser->id);
                }
                $financeEntry = $financeEntry->get();
                $data['openingBalanceBDT'] = $financeEntry
                ->reduce(function($carry, $entry) {
                    $amount = $entry->currency == 'BDT' ? $entry->amount : 0;
                    return $carry + ($entry->type === 'income' ? $amount : -$amount);
                }, 0);
                $data['openingBalanceRMB'] = $financeEntry
                ->reduce(function($carry, $entry) {
                    $amount = $entry->currency == 'RMB' ? $entry->amount : 0;
                    return $carry + ($entry->type === 'income' ? $amount : -$amount);
                }, 0);
                $data['openingBalanceUSD'] = $financeEntry
                ->reduce(function($carry, $entry) {
                    $amount = $entry->currency == 'USD' ? $entry->amount : 0;
                    return $carry + ($entry->type === 'income' ? $amount : -$amount);
                }, 0);
        }
        $data['expenses'] = Expense::orderBy('name', 'asc')->get();
        $data['products'] = Product::orderBy('name', 'asc')->get();
        $data['title'] = 'Finance List';
        $data['type'] = $type;
        $data['url'] = 'finance_entries';
        $data['perPage'] = $perPage;
        if($studentId){
            $data['student_id'] = $studentId;
        }
        // return $data;
        return view('admin.account.index')->with($data);
    }

    public function create()
    {
        $students = Student::orderBy('id', 'desc')->select('id', 'first_name', 'last_name', 'passport');
        $currentUser = Auth::user();
        $students->where('branch_id', $currentUser->branch);
        $data['students'] = $students->get();
        $data['expenses'] = Expense::orderBy('id', 'desc')->get();
        $data['products'] = Product::orderBy('id', 'desc')->get();
        // $data['currencies'] = Currency::with('latestRate')->get();
        $data['wallets'] = Wallet::where('branch_id', $currentUser->branch)->get();

        $data['title'] = 'Expense Create';
        $data['type'] = 'Expense';
        $data['url'] = 'finance_entries';
        return view('admin.account.create')->with($data);
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'type' => 'required|in:income,expense',
                'amount' => 'required|numeric',
                'transaction_date' => 'required|date',
                'student_id' => 'nullable|exists:students,id',
                'description' => 'nullable|string',
                // 'categories' => 'nullable|array',
                'category_id' => 'nullable|string',
                'reference' => 'nullable|string',
            ]);
            $wallet = Wallet::where('id', $request->wallet_id)->first();
            $currency =  Currency::findOrFail($wallet->currency_id);
            if($currency){
                $validated['currency'] = $currency->code;
                $validated['exchange_rate'] = $currency->latestRate->rate;
                $validated['base_amount'] = $request->amount * $currency->latestRate->rate;
            }
            $validated['created_by'] = Auth::id();
            $financeEntry = FinanceEntry::create($validated);

            $wallet->debitCredit($financeEntry->type, $financeEntry->amount, $financeEntry->id, $financeEntry->description);
            DB::commit();
            return redirect()->route('finance_entries.index', ['type' => 'expense'])->with('success', 'Entry created successfully.');
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function edit(FinanceEntry $financeEntry)
    {
        $data['students'] = Student::orderBy('id', 'desc')->get();
        $data['expenses'] = Expense::orderBy('id', 'desc')->get();
        $data['products'] = Product::orderBy('id', 'desc')->get();
        $data['financeEntry'] = $financeEntry;
        $data['title'] = 'Expense Edit';
        $data['type'] = 'expense';
        $data['url'] = 'finance_entries';
        // return $data;
        return view('admin.account.edit')->with($data);       
    }

    public function update(Request $request, FinanceEntry $financeEntry)
    {
        // return $request;
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'student_id' => 'nullable|exists:students,id',
            'description' => 'nullable|string',
            'reference' => 'nullable|string',
            'category_id' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string',
        ]);
        $financeEntry->update($validated);

        return redirect()->route('finance_entries.index', ['type' => 'expense'])->with('success', 'Entry updated successfully.');
    }

    public function destroy(FinanceEntry $financeEntry)
    {
        $type = request()->get('type', '');
        $financeEntry->delete();
        if($type == 'expense'){
            return redirect()->route('finance_entries.index', ['type' => 'expense'])->with('success', 'Entry deleted successfully.');
        }
        return redirect()->route('finance_entries.index')->with('success', 'Entry deleted successfully.');
    }
}
