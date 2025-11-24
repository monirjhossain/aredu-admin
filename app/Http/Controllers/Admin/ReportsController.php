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

class ReportsController extends Controller
{
    public function index(Request $request)
    // return $request->student;  
    {
        $perPage = request()->get('perpage', 15);
        $type = request()->get('type', '');
        $typeStudent = request()->get('student', '');
        $category_id = request()->get('category_id', '');

        $order = 'asc';
        if($type == 'expense'){
            $order = 'desc';
        }
        $financeEntry = FinanceEntry::orderBy('category_id', 'ASC');
        if($type && $category_id){
            if($type == 'income_summary'){
                $category = Product::findOrFail($category_id);
            }else{
                $category = Expense::findOrFail($category_id);
            }
            $financeEntry->where('category_id', $category_id)->where('type', $type == 'income_summary' ? 'income' : 'expense');
            $data['title'] = $category->name;
            $data['type'] = 'report';
            $data['url'] = 'finance_entries';
            $data['entries'] = $financeEntry->get();
            return view('admin.reports.single_category')->with($data);
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
            $financeEntry->select('category_id', DB::raw('SUM(amount) as total_amount'), 'currency')
                        ->groupBy('category_id')
                        ->where('type', 'income')
                        ->with('income'); // assuming relation Payment -> belongsTo(Category::class)
        }
        if($type == 'expense_summary'){
            $financeEntry->select('category_id', DB::raw('SUM(amount) as total_amount'), 'currency')
                        ->groupBy('category_id')
                        ->where('type', 'expense')
                        ->with('expense'); // assuming relation Payment -> belongsTo(Category::class)
        }
            
        // return $data['entries'] = $financeEntry->get();
        $data['entries'] = $financeEntry->get();

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
