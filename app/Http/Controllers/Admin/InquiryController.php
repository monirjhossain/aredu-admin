<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\CourseInquiryRequest;
use App\Http\Requests\Inquiry\InquiryRequest;
use App\Http\Requests\Inquiry\PublicApplicationRequest;
use App\Models\Branch;
use App\Models\CSCAExam;
use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Subject;
use App\Services\InquiryFormDataService;
use App\Services\InquirySelectionResolver;
use App\Services\InquiryToStudentConverter;
use App\Support\BranchApplicationQr;
use App\Support\InquiryBranchAccess;
use App\Traits\UploadAble;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InquiryController extends Controller
{

    use UploadAble;

    /** @var InquiryFormDataService */
    private $formDataService;

    /** @var InquiryToStudentConverter */
    private $inquiryToStudentConverter;

    /** @var InquirySelectionResolver */
    private $selectionResolver;

    public function __construct(
        InquiryFormDataService $formDataService,
        InquiryToStudentConverter $inquiryToStudentConverter,
        InquirySelectionResolver $selectionResolver
    ) {
        $this->formDataService = $formDataService;
        $this->inquiryToStudentConverter = $inquiryToStudentConverter;
        $this->selectionResolver = $selectionResolver;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $branchId = InquiryBranchAccess::resolveBranchFilter($currentUser, $request->input('branch_id'));
        $canManageAnyBranch = InquiryBranchAccess::canManageAnyBranch($currentUser);

        $inquiries = Inquiry::with(['student', 'branchInfo'])
            ->orderBy('created_at', 'DESC');

        InquiryBranchAccess::applyListScope($inquiries, $currentUser, $branchId);

        if ($request->filled('type')) {
            $inquiries->where('type', $request->input('type'));
        }

        $inquiries = $inquiries->paginate(20)->appends($request->query());

        $typeQuery = Inquiry::query()
            ->select('type')
            ->whereNotNull('type');

        InquiryBranchAccess::applyListScope($typeQuery, $currentUser, $branchId);

        $type = $typeQuery
            ->groupBy('type')
            ->orderBy('type')
            ->get();

        $branches = $canManageAnyBranch
            ? Branch::select('id', 'name')->orderBy('name')->get()
            : Branch::select('id', 'name')->where('id', $currentUser->branch)->orderBy('name')->get();

        return view('admin.inquiries.index', compact(
            'inquiries',
            'type',
            'branches',
            'branchId',
            'canManageAnyBranch'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array_merge($this->formDataService->getFormOptions(), [
            'title' => 'Inquiry Create',
            'type' => 'inquiry',
            'url' => 'inquiries',
            'branches' => $this->branchesForForm(Auth::user()),
            'cscaExams' => CSCAExam::orderBy('name')->get(),
        ]);

        return view('admin.inquiries.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InquiryRequest $request)
    {
        DB::beginTransaction();
        try {
            $inquiry = new Inquiry();
            $inquiryFillableData = $this->mapInquiryPayload($request);
            $inquiry->fill($inquiryFillableData)->save();
            DB::commit();
            Session::flash('success', 'Created successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
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
    public function show(Category $category)
    {
        //
    }

    public function edit(Inquiry $inquiry)
    {
        $this->ensureInquiryInUserBranch($inquiry);

        $data = array_merge($this->formDataService->getFormOptions(), [
            'inquiry' => $inquiry,
            'title' => 'Inquiry Edit',
            'type' => 'inquiry',
            'url' => 'inquiries',
            'branches' => $this->branchesForForm(Auth::user()),
            'cscaExams' => CSCAExam::orderBy('name')->get(),
        ]);

        return view('admin.inquiries.edit')->with($data);
    }

    public function update(InquiryRequest $request, Inquiry $inquiry)
    {
        $this->ensureInquiryInUserBranch($inquiry);

        DB::beginTransaction();
        try {
            $inquiryFillableData = $this->mapInquiryPayload($request);
            $inquiry->fill($inquiryFillableData)->save();
            DB::commit();
            Session::flash('success', 'Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function destroy(Inquiry $inquiry)
    {
        $this->ensureInquiryInUserBranch($inquiry);

        if($inquiry){
            $inquiry->delete();
            Session::flash('success', 'Inquiry deleted successfully');
            return redirect()->route('inquiries.index');
        }
    }

    public function applicationForm(Request $request): View
    {
        if (!$request->filled('branch')) {
            abort(404, 'Invalid application link. Please scan your branch QR code.');
        }

        $branch = Branch::find($request->input('branch'));

        if (!$branch) {
            abort(404, 'Branch not found. Please scan a valid branch QR code.');
        }

        $branchLogo = $branch->logo ? asset($branch->logo) : asset('media/custom/logo.png');

        return view('frontend.applications.create', array_merge(
            $this->formDataService->getFormOptions(),
            [
                'title' => 'Student Application Form',
                'branch' => $branch,
                'branchId' => $branch->id,
                'logoUrl' => $branchLogo,
            ]
        ));
    }

    public function applicationFormSubmit(PublicApplicationRequest $request): RedirectResponse
    {
        $branchId = (int) $request->query('branch', $request->input('branch_id'));
        $branch = Branch::find($branchId);

        if (!$branch) {
            abort(404, 'Invalid application link. Please scan your branch QR code.');
        }

        DB::beginTransaction();

        try {
            $nameParts = preg_split('/\s+/', trim($request->input('student_name')), 2);

            $inquiry = Inquiry::create([
                'type' => 'Public QR Application',
                'branch_id' => $branch->id,
                'first_name' => $nameParts[0] ?? '',
                'last_name' => $nameParts[1] ?? '',
                'email' => $request->input('email'),
                'mobile_no' => $request->input('mobile_no'),
                'country' => $request->input('country'),
                'country_preferred' => $request->input('country_preferred'),
                'last_education' => $request->input('last_education'),
                'result' => $request->input('result'),
                'course' => $request->input('course'),
                'university' => $this->selectionResolver->universityNamesFromRequest($request),
                'subject' => $this->selectionResolver->subjectNamesFromRequest($request),
                'message' => $request->input('message'),
                'status' => 1,
                'view' => 0,
            ]);

            DB::commit();

            return redirect()
                ->route('application-form.thank-you')
                ->with('success', 'Your application has been submitted successfully!')
                ->with('application_tracking_id', $inquiry->tracking_id);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function applicationThankYou(): View
    {
        return view('frontend.applications.thank-you', [
            'title' => 'Thank You',
            'logoUrl' => asset('media/custom/logo.png'),
        ]);
    }

    public function publicInquiryForm(Request $request, string $type): View
    {
        $config = $this->publicFormConfig($type);
        $branch = $this->resolvePublicFormBranch($request);

        $branchLogo = $branch->logo ? asset($branch->logo) : asset('media/custom/logo.png');

        return view('frontend.applications.create', array_merge(
            $this->formDataService->getFormOptions(),
            [
                'title' => $config['title'],
                'subtitle' => $config['subtitle'],
                'typeKey' => $type,
                'typeLabel' => $config['type'],
                'whatsappUrl' => $config['whatsapp'] ?? null,
                'showCscaField' => $type === 'csca',
                'branch' => $branch,
                'branchId' => $branch->id,
                'storeUrl' => route('inquiry-forms.store', ['type' => $type, 'branch' => $branch->id]),
                'cscaExams' => $type === 'csca' ? CSCAExam::orderBy('name')->get() : collect(),
                'logoUrl' => $branchLogo,
            ]
        ));
    }

    public function publicInquiryFormSubmit(CourseInquiryRequest $request, string $type): RedirectResponse
    {
        $config = $this->publicFormConfig($type);
        $branch = $this->resolvePublicFormBranch($request);

        DB::beginTransaction();

        try {
            $nameParts = preg_split('/\s+/', trim($request->input('student_name')), 2);

            $payload = $this->filterExistingInquiryColumns([
                'type' => $config['type'],
                'branch_id' => $branch->id,
                'first_name' => $nameParts[0] ?? '',
                'last_name' => $nameParts[1] ?? '',
                'email' => $request->input('email'),
                'mobile_no' => $request->input('mobile_no'),
                'country' => $request->input('country'),
                'country_preferred' => $request->input('country_preferred'),
                'last_education' => $request->input('last_education'),
                'result' => $request->input('result'),
                'year' => (string) date('Y'),
                'course' => $request->input('course'),
                'university' => $this->selectionResolver->universityNamesFromRequest($request),
                'subject' => $this->selectionResolver->subjectNamesFromRequest($request),
                'message' => $request->input('message'),
                'csca_subjects' => $type === 'csca' ? $this->cscaSubjectNamesFromRequest($request) : null,
                'status' => 1,
                'view' => 0,
            ]);

            $inquiry = Inquiry::create($payload);

            DB::commit();

            $redirect = redirect()
                ->route('application-form.thank-you')
                ->with('success', $config['success'])
                ->with('application_tracking_id', $inquiry->tracking_id)
                ->with('inquiry_form_url', route('inquiry-forms.show', ['type' => $type, 'branch' => $branch->id]))
                ->with('inquiry_form_type', $type);

            if ($type === 'csca') {
                $redirect->with(
                    'whatsapp_group_url',
                    $config['whatsapp'] ?? 'https://chat.whatsapp.com/GfdWN13HhmEBrZHDVGwqcX'
                );
            }

            return $redirect;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Public inquiry form submit failed', [
                'type' => $type,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Submit failed: ' . $th->getMessage());
        }
    }

    public function applicationSubjects(Request $request): JsonResponse
    {
        $universityIds = array_filter(array_map('intval', (array) $request->input('university_ids', [])));

        if (empty($universityIds) && $request->filled('university_id')) {
            $universityIds = [(int) $request->input('university_id')];
        }

        $subjects = $this->formDataService->getSubjectsForUniversities($universityIds);

        return response()->json([
            'subjects' => $subjects,
        ]);
    }

    public function applicationQrCodes(Request $request): View
    {
        return view('admin.inquiries.qr-codes', [
            'title' => 'Application Form QR Codes',
            'branches' => $this->branchesForQrCodes(),
            'publicForms' => config('inquiry.public_forms', []),
        ]);
    }

    public function applicationQr(Branch $branch): View
    {
        $this->ensureQrBranchAccess($branch);

        return view('admin.inquiries.qr-show', [
            'title' => 'Application QR — ' . $branch->name,
            'branch' => $branch,
            'formUrl' => BranchApplicationQr::formUrl($branch),
            'qrImageUrl' => BranchApplicationQr::imageUrl($branch, 360),
            'logoUrl' => BranchApplicationQr::logoUrl($branch),
        ]);
    }

    public function courseQrCodes(string $type): View
    {
        $config = $this->publicFormConfig($type);

        return view('admin.inquiries.qr-codes', [
            'title' => $config['type'] . ' QR Codes',
            'branches' => $this->branchesForQrCodes(),
            'formType' => $type,
        ]);
    }

    public function courseQr(string $type, Branch $branch): View
    {
        $config = $this->publicFormConfig($type);
        $this->ensureQrBranchAccess($branch);

        return view('admin.inquiries.qr-show', [
            'title' => $config['type'] . ' QR — ' . $branch->name,
            'branch' => $branch,
            'formUrl' => BranchApplicationQr::courseFormUrl($branch, $type),
            'qrImageUrl' => BranchApplicationQr::courseImageUrl($branch, $type, 360),
            'logoUrl' => BranchApplicationQr::logoUrl($branch),
            'subtitle' => $config['type'] . ' — Scan QR Code',
            'hint' => 'Scan this QR code to open the ' . $config['type'] . ' form.',
            'backUrl' => route('inquiry-qr-codes', ['type' => $type]),
        ]);
    }

    public function convertToStudent(Inquiry $inquiry): RedirectResponse
    {
        $this->ensureInquiryInUserBranch($inquiry);

        try {
            $alreadyLinked = (bool) $inquiry->student_id;
            $student = $this->inquiryToStudentConverter->convert($inquiry->fresh());

            Session::flash(
                'success',
                $alreadyLinked
                    ? 'This inquiry is already linked to a student.'
                    : 'Inquiry converted to student successfully. Tracking: ' . ($inquiry->fresh()->tracking_id ?: ('#' . $inquiry->id))
            );

            if (Auth::user()->can('view', $student)) {
                return redirect()->route('students.show', $student->id);
            }

            return redirect()->route('inquiries.index');
        } catch (\Throwable $th) {
            \Log::error('Inquiry convertToStudent failed', [
                'inquiry_id' => $inquiry->id,
                'user_id' => Auth::id(),
                'message' => $th->getMessage(),
            ]);

            Session::flash(
                'error',
                'Convert failed: ' . ($th->getMessage() ?: 'Could not convert inquiry to student.')
            );

            return redirect()->back();
        }
    }

    private function mapInquiryPayload(InquiryRequest $request): array
    {
        $inquiry = new Inquiry();
        $payload = $request->only($inquiry->getFillable());

        if ($request->filled('student_name')) {
            $nameParts = preg_split('/\s+/', trim($request->input('student_name')), 2);
            $payload['first_name'] = $nameParts[0] ?? '';
            $payload['last_name'] = $nameParts[1] ?? '';
        }

        $payload['university'] = $this->selectionResolver->universityNamesFromRequest($request);
        $payload['subject'] = $this->selectionResolver->subjectNamesFromRequest($request);
        $payload['csca_subjects'] = $this->cscaSubjectNamesFromRequest($request);

        if (empty($payload['type'])) {
            $payload['type'] = 'Manual Inquiry';
        }

        $payload['branch_id'] = $this->resolveBranchIdForSave($request);

        return $payload;
    }

    private function resolveBranchIdForSave(InquiryRequest $request): ?int
    {
        $user = Auth::user();

        if ($user && !InquiryBranchAccess::canManageAnyBranch($user)) {
            return $user->branch ? (int) $user->branch : null;
        }

        if ($request->filled('branch_id')) {
            return (int) $request->input('branch_id');
        }

        return $user && $user->branch ? (int) $user->branch : null;
    }

    private function branchesForForm($user)
    {
        if (!$user) {
            return collect();
        }

        if (InquiryBranchAccess::canManageAnyBranch($user)) {
            return Branch::select('id', 'name')->orderBy('name')->get();
        }

        if ($user->branch) {
            return Branch::select('id', 'name')->where('id', $user->branch)->orderBy('name')->get();
        }

        return collect();
    }

    private function ensureInquiryInUserBranch(Inquiry $inquiry): void
    {
        $currentUser = Auth::user();

        if (!$currentUser || InquiryBranchAccess::canAccessInquiry($currentUser, $inquiry)) {
            return;
        }

        abort(403, 'You can only manage inquiries for your branch.');
    }

    private function branchesForQrCodes()
    {
        $currentUser = Auth::user();
        $branchesQuery = Branch::query()->orderBy('name');

        if ($currentUser && !InquiryBranchAccess::canManageAnyBranch($currentUser) && $currentUser->branch) {
            $branchesQuery->where('id', $currentUser->branch);
        }

        return $branchesQuery->get();
    }

    private function ensureQrBranchAccess(Branch $branch): void
    {
        $currentUser = Auth::user();

        if ($currentUser && !InquiryBranchAccess::canManageAnyBranch($currentUser)) {
            if ((int) $currentUser->branch !== (int) $branch->id) {
                abort(403, 'You can only view QR codes for your branch.');
            }
        }
    }

    private function resolvePublicFormBranch(Request $request): Branch
    {
        $branchId = (int) $request->query('branch', $request->input('branch_id'));
        $branch = $branchId ? Branch::find($branchId) : null;

        if (!$branch) {
            $branch = Branch::orderBy('id')->first();
        }

        if (!$branch) {
            abort(404, 'Invalid form link. Please use a valid branch inquiry form.');
        }

        return $branch;
    }

    private function publicFormConfig(string $type): array
    {
        $config = config('inquiry.public_forms.' . $type);

        if (!$config) {
            abort(404, 'Inquiry form not found.');
        }

        return $config;
    }

    private function cscaSubjectNamesFromRequest($request): ?string
    {
        $examIds = array_values(array_unique(array_filter(array_map('intval', (array) $request->input('csca_subject_ids', [])))));

        if (empty($examIds)) {
            return null;
        }

        $names = CSCAExam::whereIn('id', $examIds)->orderBy('name')->pluck('name')->filter()->implode(', ');

        return $names !== '' ? $names : null;
    }

    private function filterExistingInquiryColumns(array $payload): array
    {
        $filtered = [];

        foreach ($payload as $column => $value) {
            if (Schema::hasColumn('inquiries', $column)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }
}
