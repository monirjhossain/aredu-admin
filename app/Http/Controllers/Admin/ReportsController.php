<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account\FinanceEntry;
use App\Models\Account\Wallet;
use App\Models\Category;
use App\Models\Currency\Currency;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ReportsController extends Controller
{
    private function downloadBladeAsExcel(string $view, array $data, string $filename)
    {
        $content = view($view, $data)->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function safeExcelDownload($export, string $filename, string $fallbackView, array $fallbackData)
    {
        try {
            return Excel::download($export, $filename);
        } catch (Throwable $e) {
            report($e);
            $fallbackFilename = preg_replace('/\.xlsx$/i', '.xls', $filename);

            return $this->downloadBladeAsExcel($fallbackView, $fallbackData, $fallbackFilename);
        }
    }

    public function index(Request $request)
    {
        $perPage = request()->get('perpage', 20);
        $type = request()->get('type', '');
        $typeStudent = request()->get('student', '');
        $category_id = request()->get('category_id', '');
        $currentUser = Auth::user();
        

        // Excel export logic
        if ($request->has('export') && $request->get('export') === 'excel') {
            if ($type === 'expense_summary' || $type === 'income_summary') {
                $financeEntry = FinanceEntry::orderBy('category_id', 'ASC');
                $financeEntry->where('branch_id', $currentUser->branch);

                if($type && $category_id){
                    $financeEntry->where('category_id', $category_id)->where('type', $type == 'income_summary' ? 'income' : 'expense');
                }
                $start_date = $request->get('start_date');
                $end_date = $request->get('end_date');
                if ($start_date) {
                    $financeEntry->whereDate('transaction_date', '>=', $start_date);
                }
                if ($end_date) {
                    $financeEntry->whereDate('transaction_date', '<=', $end_date);
                }
                if ($type === 'expense_summary') {
                    $entries = $financeEntry->select(
                        'category_id',
                        DB::raw('SUM(amount) as total_amount'),
                        'currency',
                        DB::raw('MAX(transaction_date) as transaction_date')
                    )
                    ->groupBy('category_id')
                    ->where('type', 'expense')
                    ->with('expense')
                    ->get();
                    return $this->safeExcelDownload(
                        new \App\Exports\ExpenseSummaryExport($entries),
                        'CategoryCost_summery.xlsx',
                        'admin.reports.export_expense_summary',
                        ['entries' => $entries]
                    );
                } elseif ($type === 'income_summary') {
                    $entries = $financeEntry->select(
                        'category_id',
                        DB::raw('SUM(amount) as total_amount'),
                        'currency',
                        DB::raw('MAX(transaction_date) as transaction_date')
                    )
                    ->groupBy('category_id')
                    ->where('type', 'income')
                    ->with('income')
                    ->get();
                    return $this->safeExcelDownload(
                        new \App\Exports\IncomeSummaryExport($entries),
                        'CategoryIncome_summery.xlsx',
                        'admin.reports.export_income_summary',
                        ['entries' => $entries]
                    );
                }
            } else if ($type === 'student_summary') {
                $financeEntry = FinanceEntry::orderBy('student_id', 'ASC');
                $financeEntry->where('branch_id', $currentUser->branch);
                $start_date = $request->get('start_date');
                $end_date = $request->get('end_date');
                $student_id = $request->get('student_id');
                if ($start_date) {
                    $financeEntry->whereDate('transaction_date', '>=', $start_date);
                }
                if ($end_date) {
                    $financeEntry->whereDate('transaction_date', '<=', $end_date);
                }
                if ($student_id) {
                    $financeEntry->where('student_id', $student_id);
                }
                $entries = $financeEntry
                    ->select('description', 'reference', 'student_id','category_id', 'currency', 
                        DB::raw("SUM(CASE WHEN type = 'income' AND currency = 'BDT' THEN amount ELSE 0 END) as income_bdt"),
                        DB::raw("SUM(CASE WHEN type = 'income' AND currency = 'RMB' THEN amount ELSE 0 END) as income_rmb"),
                        DB::raw("SUM(CASE WHEN type = 'expense' AND currency = 'BDT' THEN amount ELSE 0 END) as expense_bdt"),
                        DB::raw("SUM(CASE WHEN type = 'expense' AND currency = 'RMB' THEN amount ELSE 0 END) as expense_rmb")
                    )
                    ->groupBy('student_id')
                    ->with('student')
                    ->get();
                return $this->safeExcelDownload(
                    new \App\Exports\StudentSummaryExport($entries),
                    'StudentSummary.xlsx',
                    'admin.reports.export_student_summary',
                    ['entries' => $entries]
                );
            }
        }

        $order = 'asc';
        if($type == 'expense'){
            $order = 'desc';
        }
        $financeEntry = FinanceEntry::orderBy('category_id', 'ASC');
        $financeEntry->where('branch_id', $currentUser->branch);
        // If filtering by category, just filter the summary, don't redirect to single_category
        if($type && $category_id){
            $financeEntry->where('category_id', $category_id)->where('type', $type == 'income_summary' ? 'income' : 'expense');
        }
        // Date filter logic
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        if ($start_date) {
            $financeEntry->whereDate('transaction_date', '>=', $start_date);
        }
        if ($end_date) {
            $financeEntry->whereDate('transaction_date', '<=', $end_date);
        }




        if($type == 'student_summary'){
            $financeEntry->orderBy('student_id', 'ASC')
            ->select('description', 'reference', 'student_id','category_id', 'currency', 
                DB::raw("SUM(CASE WHEN type = 'income' AND currency = 'BDT' THEN amount ELSE 0 END) as income_bdt"),
                DB::raw("SUM(CASE WHEN type = 'income' AND currency = 'RMB' THEN amount ELSE 0 END) as income_rmb"),
                DB::raw("SUM(CASE WHEN type = 'expense' AND currency = 'BDT' THEN amount ELSE 0 END) as expense_bdt"),
                DB::raw("SUM(CASE WHEN type = 'expense' AND currency = 'RMB' THEN amount ELSE 0 END) as expense_rmb"))
            ->groupBy('student_id');
                
        }
        if($type == 'income_summary'){
            $financeEntry->select(
                'category_id',
                DB::raw('SUM(amount) as total_amount'),
                'currency',
                DB::raw('MAX(transaction_date) as transaction_date')
            )
            ->groupBy('category_id')
            ->where('type', 'income')
            ->with('income');
        }
        if($type == 'expense_summary'){
            $financeEntry->select(
                'category_id',
                DB::raw('SUM(amount) as total_amount'),
                'currency',
                DB::raw('MAX(transaction_date) as transaction_date')
            )
            ->groupBy('category_id')
            ->where('type', 'expense')
            ->with('expense');
            // Pass all expense categories for filter dropdown
            $data['categories'] = Expense::all();
        }
            
        // return $data['entries'] = $financeEntry->get();
        $data['entries'] = $financeEntry->paginate($perPage);
        // Ensure categories is always set for expense_summary and income_summary
        if ($type == 'expense_summary' && !isset($data['categories'])) {
            $data['categories'] = Expense::all();
        }
        if ($type == 'income_summary' && !isset($data['categories'])) {
            $data['categories'] = \App\Models\Product::all();
        }

        // $data['openingBalanceBDT']=0;
        // $data['openingBalanceRMB']=0;
        // $data['openingBalanceUSD']=0;
        // if($type != 'expense' && count($data['entries']) !=0){
        //     $firstRow = $data['entries']->first();
        //     // Calculate opening balance (before first row)
        //     $financeEntry = FinanceEntry::where(function($q) use ($firstRow) {
        //             $q->where('transaction_date', '<', $firstRow->transaction_date)
        //             ->orWhere(function($q2) use ($firstRow) {
        //                 $q2->where('transaction_date', $firstRow->transaction_date)
        //                     ->where('id', '<', $firstRow->id);
        //             });
        //         });
        //         if($request->student){
        //             $financeEntry->where('student_id', $request->student);
        //         }
        //         $currentUser = Auth::user();
        //         if($currentUser->role_id == 4){
        //             $financeEntry = $financeEntry->where('created_by', $currentUser->id);
        //         }
        //         $financeEntry = $financeEntry->get();
        //         $data['openingBalanceBDT'] = $financeEntry
        //         ->reduce(function($carry, $entry) {
        //             $amount = $entry->currency == 'BDT' ? $entry->amount : 0;
        //             return $carry + ($entry->type === 'income' ? $amount : -$amount);
        //         }, 0);
        //         $data['openingBalanceRMB'] = $financeEntry
        //         ->reduce(function($carry, $entry) {
        //             $amount = $entry->currency == 'RMB' ? $entry->amount : 0;
        //             return $carry + ($entry->type === 'income' ? $amount : -$amount);
        //         }, 0);
        //         $data['openingBalanceUSD'] = $financeEntry
        //         ->reduce(function($carry, $entry) {
        //             $amount = $entry->currency == 'USD' ? $entry->amount : 0;
        //             return $carry + ($entry->type === 'income' ? $amount : -$amount);
        //         }, 0);
        // }
        $data['expenses'] = Expense::orderBy('id', 'desc')->get();
        $data['products'] = Product::orderBy('id', 'desc')->get();
        $data['title'] = $type;
        $data['type'] = $type;
        $data['url'] = 'finance_entries';
        $data['perPage'] = $perPage;
        // if($request->student){
        //     $data['student_id'] = $student->id;
        // }
        // return $data;
        if($type == 'student_summary'){
            // Fetch students for the filter dropdown
            $students = \App\Models\Student\Student::orderBy('id', 'desc')->select('id', 'first_name', 'last_name', 'passport')->get();
            $data['students'] = $students;
            return view('admin.reports.single_student_list')->with($data);
        }
        return view('admin.reports.index')->with($data);
    }

    public function create()
    {
        $students = Student::orderBy('id', 'desc')->select('id', 'first_name', 'last_name', 'passport');
        $currentUser = Auth::user();
        if($currentUser->role_id == 4){
            $students->where('user_id', $currentUser->id);
        }
        $data['students'] = $students->get();
        $data['expenses'] = Expense::orderBy('id', 'desc')->get();
        $data['products'] = Product::orderBy('id', 'desc')->get();
        $data['currencies'] = Currency::with('latestRate')->get();
        $data['title'] = 'Report Create';
        $data['type'] = 'report';
        $data['url'] = 'finance_entries';
        return view('admin.reports.create')->with($data);
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
                'category' => 'nullable|string',
                'reference' => 'nullable|string',
            ]);

            $currency =  Currency::findOrFail($request->currency);
            if($currency){
                $validated['currency'] = $currency->code;
                $validated['exchange_rate'] = $currency->latestRate->rate;
                $validated['base_amount'] = $request->amount * $currency->latestRate->rate;
            }
            $validated['created_by'] = Auth::id();
            $financeEntry = FinanceEntry::create($validated);

            $wallet = Wallet::where('user_id', null)->where('currency_id', $request->currency)->first();
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
        return view('admin.reports.edit')->with($data);       
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
            'category' => 'nullable|string',
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
