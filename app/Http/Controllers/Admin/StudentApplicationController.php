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
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Models\Student\UniversityApplicationStatus;
use App\Models\UniversityStatus;
use App\Http\Requests\Student\UpdateUniversityApplicationRequest;
use App\Services\Student\UniversityApplicationEditPresenter;
use App\Services\Student\UniversityApplicationStatusService;
use App\Services\Student\UniversityApplicationUpdateService;
use App\Services\WalletService;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class StudentApplicationController extends Controller
{

    use UploadAble;

    /** @var UniversityApplicationStatusService */
    private $applicationStatusService;

    /** @var UniversityApplicationUpdateService */
    private $applicationUpdateService;

    /** @var UniversityApplicationEditPresenter */
    private $applicationEditPresenter;

    public function __construct(
        UniversityApplicationStatusService $applicationStatusService,
        UniversityApplicationUpdateService $applicationUpdateService,
        UniversityApplicationEditPresenter $applicationEditPresenter
    ) {
        $this->applicationStatusService = $applicationStatusService;
        $this->applicationUpdateService = $applicationUpdateService;
        $this->applicationEditPresenter = $applicationEditPresenter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $uni = Post::has('students')
                ->select('id', 'post_title')
                ->withCount('students');
                // ->get();

            $data['title'] = 'University Ways Student List';
            $data['type'] = 'report';
            $data['url'] = 'finance_entries';
            $data['universities'] = $uni->get();
            // return $data;
            return view('admin.students.university-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create(Request $request)
    // {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'university_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'status_id' => 'nullable|integer|exists:university_statuses,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $this->authorize('updateApplicationStatus', $student);

        DB::beginTransaction();
        try {
            $stUni = new StudentUniversity();
            $stUni->fill($request->only($stUni->getFillable()));
            $stUni->status_id = $request->input('status_id', 1);
            $stUni->updated_by = Auth::id();
            $stUni->save();

            $apStatus = new UniversityApplicationStatus();
            $apStatus->student_university_id = $stUni->id;
            $apStatus->status_id = $stUni->status_id;
            $apStatus->updated_by = $stUni->updated_by;
            $apStatus->active = 1;
            $apStatus->save();

            $student->load('jncounselor');
            $newStatusName = optional(UniversityStatus::find($stUni->status_id))->name ?? '';
            if($student && isset($student->email)){
                $JuniorCounselorName = $student->jncounselor ? $student->jncounselor->name : 'N/A';
                $JuniorCounselorMobile = $student->jncounselor ? $student->jncounselor->mobile_no : 'N/A';
                $template = \App\Models\SmsTemplate::where('name', '[Status Change]')->first();
                if ($template) {
                    $placeholders = [
                        '[Student Name]'   => $student->full_name,
                        '[Status Name]'    => $newStatusName,
                        '[Counselor Name]' => $JuniorCounselorName . ($JuniorCounselorMobile ? ' - ' . $JuniorCounselorMobile : ''),
                    ];
                    $body = str_replace(
                        array_keys($placeholders),
                        array_values($placeholders),
                        $template->message
                    );
                    $subject = 'AR EDUCATION – University Status Changed';
                    $name = $student->full_name;
                    $email = $student->email;
                    \Mail::to($email)->send(new \App\Mail\MailSystem($name, $body, $subject));
                }
            }

            // Send notification on university application add
            if ($student) {
                $actor = Auth::user();
                $studentUser = $student->user;
                $actorName = $actor ? $actor->name : 'System';
                $notification = new \App\Notifications\StatusChangedNotification($stUni, null, $newStatusName, 'university', $actorName);
                // Notify actor (if not the student user)
                if ($actor && (!$studentUser || $actor->id !== $studentUser->id) && method_exists($actor, 'notify')) {
                    $actor->notify($notification);
                }
                // Notify student user
                if ($studentUser) {
                    $studentUser->notify($notification);
                }
                // Notify all users with specific roles
                $roleNames = ['Admin', 'Super Admin', 'Assign Employee', 'Senior Counselor', 'Junior Counselor'];
                $roles = \App\Models\Role::whereIn('user_type', $roleNames)->pluck('id');
                $usersToNotify = \App\Models\User::whereIn('role_id', $roles)->get();
                foreach ($usersToNotify as $user) {
                    // Avoid duplicate notification to actor or student user
                    if (($actor && $user->id === $actor->id) || ($studentUser && $user->id === $studentUser->id)) {
                        continue;
                    }
                    if (method_exists($user, 'notify')) {
                        $user->notify($notification);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('students.show', $student->id)
                ->with('success', 'Application added successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            report($th);

            return redirect()
                ->route('students.show', $request->student_id)
                ->with('error', 'Could not add application. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // return $id;
        $students = Student::orderBy('id', 'DESC');
        $students->whereHas('universities', function ($q) use ($id) {
                $q->where('id', $id);
            });

        // Show details related to a student application (student_university record)
        // $uni = Post::has('students')
        //         ->select('id', 'post_title')
        //         ->withCount('students');
                // ->get();
            $data['students'] = $students->get();

            $data['title'] = 'University Ways Student List';
            $data['type'] = 'report';
            $data['url'] = 'finance_entries';
            // $data['universities'] = $uni->get();
            // return $data;
            return view('admin.students.university-student-list')->with($data);
    }

    public function edit(StudentUniversity $application)
    {
        $application->loadMissing('student');

        if (!$application->student) {
            abort(404, 'Student not found for this application.');
        }

        $this->authorize('updateApplicationStatus', $application->student);

        return view('admin.student_applications.edit', $this->applicationEditPresenter->viewData($application));
    }

    public function update(UpdateUniversityApplicationRequest $request, StudentUniversity $application)
    {
        DB::beginTransaction();
        try {
            $application->loadMissing('statusUni');
            $this->applicationUpdateService->update($application, $request);
            DB::commit();

            return redirect()
                ->route('student.application.edit', $application)
                ->with('success', 'Application updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            $message = config('app.debug')
                ? $th->getMessage()
                : 'Something went wrong! Please try again.';

            return redirect()
                ->route('student.application.edit', $application)
                ->withInput()
                ->with('error', $message);
        }
    }

    

    public function updateStatus(Request $request, StudentUniversity $application)
    {
        $application->loadMissing('student');
        $student = $application->student ?? Student::findOrFail($application->student_id);
        $this->authorize('updateApplicationStatus', $student);

        $validated = $request->validate([
            'status_id' => 'required|integer|exists:university_statuses,id',
        ]);

        DB::beginTransaction();
        try {
            $result = $this->applicationStatusService->update($application, (int) $validated['status_id']);
            DB::commit();

            $statusName = $result['status_name'] ?? 'new status';

            return response()->json([
                'success' => true,
                'status_name' => $result['status_name'],
                'message' => $result['changed']
                    ? "Status updated to {$statusName}."
                    : "Status is already set to {$statusName}.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Could not update status.',
            ], 422);
        }
    }

    public function destroy(StudentUniversity $application)
    {
        $application->loadMissing('student');

        if (!$application->student) {
            abort(404, 'Student not found for this application.');
        }

        $this->authorize('updateApplicationStatus', $application->student);

        $studentId = (int) $application->student_id;

        DB::beginTransaction();
        try {
            UniversityApplicationStatus::where('student_university_id', $application->id)->delete();
            $application->delete();
            DB::commit();

            return redirect()
                ->route('students.show', $studentId)
                ->with('success', 'Application deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);

            return redirect()
                ->route('students.show', $studentId)
                ->with('error', 'Could not delete application.');
        }
    }
}
