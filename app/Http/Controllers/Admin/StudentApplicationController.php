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
use App\Services\WalletService;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentApplicationController extends Controller
{

    use UploadAble;

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
        // return $request;
        DB::beginTransaction();
        try {
            $stUni = new StudentUniversity();
            $stUniFillableData = $request->only($stUni->getFillable());
            $stUni->fill($stUniFillableData)->save();
            $stUni->updated_by = Auth::user()->id;
           
            $stUni->save();

            $apStatus = new UniversityApplicationStatus();
            $apStatus->student_university_id = $stUni->id;
            $apStatus->status_id = $stUni->status_id;
            $apStatus->updated_by = $stUni->updated_by;
            $apStatus->active = 1;
            $apStatus->save();

            DB::commit();
            Session::flash('success', 'Added successfully');
            return redirect()->to(route('students.show', $request->student_id));
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
        // Show dedicated edit page for a student application.
        $student = Student::findOrFail($application->student_id);
        $data['application'] = $application;
        $data['student'] = $student;
        $data['title'] = 'Edit Application';
        $data['type'] = 'application';
        $data['url'] = 'student.application';
        $data['universities'] = Post::select('id', 'post_title')->orderby('id', 'DESC')->where('post_type', 'university')->get();
        $data['university_statuses'] = UniversityStatus::select('id','name')->orderBy('id')->get();

        return view('admin.student_applications.edit')->with($data);
    }

    /**
     * Update the specified application resource.
     * This method will be used by the resource route (student.application.update).
     */
    public function update(Request $request, StudentUniversity $application)
    {
        $request->validate([
            'status_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $application->status_id;
            $oldStatusName = optional($application->statusUni)->name;
            $application->status_id = $request->status_id;
            if ($request->has('note')) {
                $application->note = $request->note;
            }
            if ($request->has('remarks')) {
                $application->remarks = $request->remarks;
            }
            if ($request->has('application_no')) {
                $application->application_no = $request->application_no;
            }
            $application->updated_by = Auth::id();
            $application->save();
            $application->refresh(); // Ensure latest status

            // Status change notification logic
            if ($oldStatus != $application->status_id) {
                $actor = Auth::user();
                $student = Student::find($application->student_id);
                $studentUser = $student ? $student->user : null; // Assuming Student model has user() relation
                $newStatusName = optional($application->statusUni)->name;
                $actorName = $actor ? $actor->name : 'System';
                $notification = new \App\Notifications\StatusChangedNotification($application, $oldStatusName, $newStatusName, 'university', $actorName);
                if ($actor && (!$studentUser || $actor->id !== $studentUser->id) && method_exists($actor, 'notify')) {
                    $actor->notify($notification);
                }
                if ($studentUser) {
                    $studentUser->notify($notification);
                }

                // Send email to student on university status change using '[Status Change]' template
                if($student && isset($student->email)){
                    $template = \App\Models\SmsTemplate::where('name', '[Status Change]')->first();
                    if ($template) {
                        $placeholders = [
                            '[Student Name]'   => $student->full_name,
                            '[Status Name]'    => $newStatusName,
                            '[Actor Name]'     => $actorName,
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
            }

            // record the status change
            UniversityApplicationStatus::where('student_university_id', $application->id)->update(['active' => 0]);
            UniversityApplicationStatus::create([
                'student_university_id' => $application->id,
                'status_id' => $request->status_id,
                'updated_by' => Auth::id(),
                'active' => 1
            ]);

            DB::commit();
            Session::flash('success', 'Application updated successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    

    public function destroy(Request $request)
    {
        // return $request;
        if ($request->id) {
            StudentUniversity::where('id', $request->id)->delete();
            UniversityApplicationStatus::where('student_university_id', $request->id)->delete();
            Session::flash('success', 'Deleted successfully');
            return redirect()->to(route('students.show', $request->student_id));
        }
    }
}
