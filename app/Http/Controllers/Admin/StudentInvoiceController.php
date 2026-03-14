<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Models\Account\FinanceEntry;
use App\Models\Account\Invoice;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\Document;
use App\Models\Inquiry\Inquiry;

use App\Models\Post\Post;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Student\Student;
use App\Services\WalletService;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentInvoiceController extends Controller
{

    use UploadAble;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $invoices = Invoice::orderBy('id', 'DESC')->with(['student', 'user']);
        $perPage = $request->get('perpage', 15);
        $student = '';
        if ($request->input('student')) {
            $student_id = $request->input('student');
            $student = Student::findOrFail($student_id);
            $invoices->where('student_id', $student_id);
        }

        $currentUser = Auth::user();
        if($currentUser->role_id == 4){
            $invoices->where('user_id', $currentUser->id);
        }
        // if($request->input('type')){
        //     $students->where('office_status', $request->input('type'));
        // }
        // if($request->input('search')){
        //     $students->where('first_name', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('last_name', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('study_level', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('subject', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('preferred_country', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('preferred_subject', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('last_education', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('passport', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('email', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('mobile_no', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('address', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('city', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('country', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('zip', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('father_name', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('father_occoupation', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('father_mobile_no', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('mother_name', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('mother_occoupation', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('mother_mobile_no', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('responsible_person', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('note', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('status', 'like', '%'.$request->input('search').'%')
        //     ->orWhereHas('university', function($q) use ($request){
        //         $q->where('post_title', 'like', '%'.$request->input('search').'%');
        //     })
        //     ->orWhere('ssc_result', 'like', '%'.$request->input('search').'%')
        //     ->orWhere('hsc_result', 'like', '%'.$request->input('search').'%')
        //     ;
        // }
        $data['invoices'] = $invoices->paginate($perPage);
        $data['perPage'] = $perPage;
        $data['totalAmount'] = number_format($data['invoices']->sum('paid'), 2);
        $data['openingBalanceBDT']=0;
        $data['request'] = $request;
        // $data['invoices'] = $invoices->paginate(20);
        $data['title'] = 'Invoices';
        $data['type'] = 'invoice';
        $data['student'] = $student;
        $data['url'] = $student? 'student.invoices' : 'finance_entries.invoices';
        // return $data;
        return view('admin.invoices.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $student = '';
        if ($request->input('student')) {
            $student_id = $request->input('student');
            $student = Student::findOrFail($student_id);
        }
        $data['products'] = Product::select('id', 'name')->get();
        $data['wallets'] = Wallet::select('id', 'name')->where('currency_id', 1)->where('branch_id', $currentUser->branch)->get(); // 1 means BDT
        $data['documents'] = Document::select('id', 'name')->get();
        $data['currencies'] = Currency::select('id', 'code')->orderBy('id', 'asc')->get();
        $data['student'] = $student;
        $data['students'] = $student ? null : Student::select('id', 'first_name', 'last_name', 'email', 'address', 'mobile_no', 'passport')->where('branch_id', $currentUser->branch)->orderby('id', 'DESC')->get();
        
        // Get first invoice's documents to auto-select for subsequent invoices
        $data['preselectedDocuments'] = [];
        if ($student) {
            $firstInvoice = Invoice::where('student_id', $student->id)
                ->orderBy('id', 'asc')
                ->first();
            if ($firstInvoice && is_array($firstInvoice->documents)) {
                $data['preselectedDocuments'] = $firstInvoice->documents;
            }
        }
        
        $data['title'] = 'Invoice Create';
        $data['type'] = 'invoice';
        $data['url'] = $student? 'student.invoices' : 'finance_entries.invoices';
        // return $data;
        return view('admin.invoices.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
            $invoice = new Invoice();
            $invoiceFillableData = $request->only($invoice->getFillable());
            $invoice->fill($invoiceFillableData)->save();
            $invoice->user_id = Auth::user()->id;   
            $invoice->user_id = Auth::user()->id; 
            $currency =  Currency::findOrFail($request->currency);
            if($currency){
                $invoice->currency = $currency->code;
                $invoice->exchange_rate = $currency->latestRate->rate;
                $invoice->base_amount = $request->paid * $currency->latestRate->rate;
            }

            $filtered = [];
            $services = '';
            $products = $request->product;
            $prices = $request->price;
            $ids = $request->ids;
            $count = 1;
            foreach ($products as $index => $product) {
                if ((float) $prices[$index] != 0) {
                    $filtered['product'][] = $product;
                    $filtered['price'][] = $prices[$index];
                    $filtered['id'][] = $ids[$index];
                    $services .= $count .'. '. $product. ' ';
                    $count++;
                }
            }
            
            if($request->student_id == '' || $request->student_id == null){
                $filtered['student']['full_name'] = $request->first_name . ' ' . $request->last_name;
                $filtered['student']['first_name'] = $request->first_name;
                $filtered['student']['last_name'] = $request->last_name;
                $filtered['student']['email'] = $request->email;
                $filtered['student']['mobile_no'] = $request->mobile_no;
                $filtered['student']['address'] = $request->address;
            }

            // return $filtered;
            $invoice->products = $filtered;
            $invoice->documents = $request->documents;
            $invoice->invoice_no = date('Ym') . $invoice->id;
            $invoice->save();

            $financeEntry = FinanceEntry::create([
                'type' => 'income',
                'amount' => $invoice->paid,
                'transaction_date' => now(),
                'student_id' => $invoice->student_id ?? null,
                'created_by' => Auth::id(),
                'description' => 'Invoice #' . $invoice->invoice_no . ' | ' . $services,
                'reference' => 'INV-' . $invoice->invoice_no,
                'categories' => json_encode($filtered['id']),
                'category_id' => $filtered['id'][0],
                'currency' => $invoice->currency,
                'exchange_rate' => $invoice->exchange_rate,
                'base_amount' => $invoice->base_amount,
            ]);

            $wallet = Wallet::where('id', $request->wallet_id)->first();
            $wallet->debitCredit('income', $financeEntry->amount, $financeEntry->id, $financeEntry->description);

            DB::commit();
            Session::flash('success', 'Created successfully');
            return redirect()->to(route('student.invoices.index', ['student' => $request->student_id]));
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        // return $invoice;
        $currentUser = Auth::user();
        $branch = Branch::where('id', $currentUser->branch);

        $student = Student::findOrFail($invoice->student_id);
        $data['branch'] = $branch->first();
        $data['invoice'] = $invoice;
        $data['student'] = $student;
        $data['title'] = 'Invoices of ' . $student->first_name . ' ' . $student->last_name;
        $data['type'] = 'invoice';
        $data['url'] = 'student.invoices';
        $data['user'] = Auth::user();
        $data['documents'] = Document::all();

        // compute previous payments for this student (exclude current invoice)
        $prevQuery = Invoice::where('student_id', $invoice->student_id);
        if ($invoice->created_at) {
            $prevQuery->where('created_at', '<', $invoice->created_at);
        } else {
            $prevQuery->where('id', '<', $invoice->id);
        }
        $data['previousPaid'] = $prevQuery->sum('paid');
        // return $data;
        return view('admin.invoices.invoice')->with($data);
    }

    public function edit(Invoice $invoice)
    {
        $student = Student::find($invoice->student_id);
        $data['invoice'] = $invoice;
        $data['student'] = $student;
        $data['title'] = 'Invoice Edit';
        $data['type'] = 'student';
        $data['url'] = 'student.invoices';
        $data['universities'] = Post::select('id', 'post_title')->orderby('id', 'DESC')->where('post_type', 'university')->get();
        $data['studies'] = \App\Models\Study::all();
        $data['documents'] = \App\Models\Document::all();
        return view('admin.invoices.edit')->with($data);
    }

    // public function update(StudentRequest $request, Student $student)
    // {
    //     // return $request;
    //     DB::beginTransaction();
    //     try {
    //         // $student = new Student();
    //         $studentFillableData = $request->only($student->getFillable());
    //         $student->fill($studentFillableData)->save();
    //         DB::commit();
    //         Session::flash('success', 'Update successfully');
    //         return redirect()->route('students.index');
    //     } catch (\Throwable $th) {
    //         DB::rollback();
    //         Session::flash('error', 'Something went wrong!');
    //         return redirect()->back();
    //     }
    // }

        public function update(Request $request, Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            // Update invoice documents
            if ($request->has('documents')) {
                $invoice->documents = $request->input('documents');
            } else {
                $invoice->documents = [];
            }
            $invoice->save();

            // Update related student's study_id
            $student = Student::find($invoice->student_id);
            if ($student && $request->has('study_id')) {
                $student->study_id = $request->input('study_id');
                $student->save();
            }

            DB::commit();
            Session::flash('success', 'Invoice and student updated successfully');
            return redirect()->route('student.invoices.index', ['student' => $student->id]);
        } catch (\Throwable $th) {
            DB::rollback();
            // Show the actual error for debugging
            return response()->json(['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()], 500);
        }
    }
    

    public function destroy(Invoice $invoice)
    {
        if ($invoice) {
            // $invoice->delete();
            Session::flash('success', 'Invoice deleted successfully');
            return redirect()->to(route('student.invoices.index', ['student' => $invoice->student_id]));
        }
    }
    public function applicationForm()
    {
        $data['title'] = 'Student Application Form';
        $data['type'] = 'student';
        $data['url'] = 'students';
        $data['universities'] = Post::select('id', 'post_title')->orderby('id', 'DESC')->where('post_type', 'university')->get();
        return view('frontend.students.create')->with($data);
    }
    public function applicationFormSubmit(StudentRequest $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $student = new Student();
            $studentFillableData = $request->only($student->getFillable());
            $student->fill($studentFillableData)->save();
            DB::commit();
            Session::flash('success', 'Created successfully');
            // return redirect()->route('students.index');
            return redirect()->route('application.show', $student->id . '-' . $student->full_name);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }
    public function applicationShow(Request $request, $slug)
    {
        $data['student'] = Student::with('university')->findOrfail($slug);
        $data['title'] = 'Student Application Details';
        $data['type'] = 'student';
        $data['url'] = 'students';
        // return $data;
        return view('frontend.students.show')->with($data);
    }
}
