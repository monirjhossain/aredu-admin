<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceIndexFilterRequest;
use App\Http\Requests\Student\StudentRequest;
use App\Models\Account\Invoice;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\Document;
use App\Models\Post\Post;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Models\Student\Student;
use App\Services\InvoiceCreationService;
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

    public function index(InvoiceIndexFilterRequest $request)
    {
        $currentUser = Auth::user();
        $filters = $request->validated();
        $perPage = (int) ($filters['perpage'] ?? 15);
        $studentId = isset($filters['student']) ? (int) $filters['student'] : null;
        $student = $studentId ? Student::findOrFail($studentId) : null;

        $filterBranchId = isset($filters['branch_id']) ? (int) $filters['branch_id'] : null;
        $effectiveBranchId = $filterBranchId ?? (int) $currentUser->branch;

        $invoices = Invoice::query()
            ->with(['student', 'user'])
            ->orderByDesc('id')
            ->when($studentId, fn($q) => $q->forStudent($studentId), fn($q) => $q->forBranch($effectiveBranchId))
            ->searchTerm($filters['search'] ?? null)
            ->invoiceDateBetween($filters['date_from'] ?? null, $filters['date_to'] ?? null)
            ->collectedBy($filters['user_id'] ?? null);

        $data['invoices'] = $invoices->paginate($perPage);
        $data['perPage'] = $perPage;
        $data['totalAmount'] = number_format($data['invoices']->sum('paid'), 2);
        $data['openingBalanceBDT'] = 0;
        $data['request'] = $request;
        $data['collectors'] = User::select('id', 'name')
            ->where('branch', $currentUser->branch)
            ->orderBy('name', 'asc')
            ->get();
        $data['branches'] = Branch::select('id', 'name')->orderBy('name')->get();
        $data['title'] = 'Invoices';
        $data['type'] = 'invoice';
        $data['student'] = $student;
        $data['url'] = $student ? 'student.invoices' : 'finance_entries.invoices';

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
    public function store(Request $request, InvoiceCreationService $invoiceCreationService)
    {
        $request->validate([
            'wallet_id' => 'nullable|integer|exists:wallets,id',
        ]);

        try {
            $currentUser = Auth::user();
            $invoiceCreationService->create($request, $currentUser);

            Session::flash('success', 'Created successfully');
            return redirect()->to(route('student.invoices.index', ['student' => $request->student_id]));
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage() ?: 'Something went wrong!');
            return redirect()->back()->withInput();
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
            Session::flash('error', $th->getMessage() ?: 'Something went wrong!');
            return redirect()->back()->withInput();
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
