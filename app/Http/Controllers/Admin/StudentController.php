<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Mail\MailSystem;
use App\Models\Branch;
use App\Models\Document;
use App\Models\EducationLevel;
use App\Models\EducationQualification;
use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\SmsTemplate;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Models\StudentStatus;
use App\Models\Study;
use App\Models\UniversityStatus;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\User;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Return_;

class StudentController extends Controller
{

    use UploadAble;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // return $request;
        $currentUser = Auth::user();
        $students = Student::orderBy('id', 'DESC');
        if ($request->input('universitylist')) {
            // $uni = StudentUniversity::orderBy('id', 'DESC')
            //     ->select('university_id')
            //     ->selectRaw('COUNT(*) as total_rows')
            //     ->groupBy('university_id');
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
        if ($request->input('branch_id')) {
            $students->where('branch_id', $request->input('branch_id'));
        }else{
            $students->where('branch_id', $currentUser->branch);
        }
        if ($request->input('subject')) {
            $students->whereHas('subjects', function ($q) use ($request) {
                $q->where('id', $request->input('subject'));
            });
        }
        if ($request->input('university')) {
            $students->whereHas('universities', function ($q) use ($request) {
                $q->where('id', $request->input('university'));
            });
        }
        // if ($request->input('university')) {
        //     $students->whereHas('universitiesAppStatus', function ($q) use ($request) {
        //         $q->where('university_id', $request->input('university'));
        //     });
        // }
        if ($request->input('study_level')) {
            // Temporarily commented out until study_level column is added to DB
            // $students->where('study_level', $request->input('study_level'));
        }
        if ($request->input('intake')) {
            $students->where('intake', $request->input('intake'));
        }
        if ($request->input('student_statuses')) {
            $students->where('status', $request->input('student_statuses'));
        }
        // Passport Number Search
        if ($request->input('passport_search')) {
            $passport = $request->input('passport_search');
            if (strlen($passport) >= 3) {
                $students->where('passport', 'like', '%' . $passport . '%');
            }
        }
        if ($request->input('search')) {
            $students->where(function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('last_name', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('study_level', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('subject', 'like', '%' . $request->input('search') . '%')
                ->orWhereHas('subjects', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('search') . '%');
                })
                ->orWhere('preferred_country', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('preferred_subject', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('last_education', 'like', '%' . $request->input('search') . '%')
                ->orWhere('intake', 'like', '%' . $request->input('search') . '%')
                ->orWhere('passport', 'like', '%' . $request->input('search') . '%')
                ->orWhere('email', 'like', '%' . $request->input('search') . '%')
                ->orWhere('mobile_no', 'like', '%' . $request->input('search') . '%')
                ->orWhere('address', 'like', '%' . $request->input('search') . '%')
                ->orWhere('city', 'like', '%' . $request->input('search') . '%')
                ->orWhere('country', 'like', '%' . $request->input('search') . '%')
                ->orWhere('zip', 'like', '%' . $request->input('search') . '%')
                ->orWhere('father_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('father_occoupation', 'like', '%' . $request->input('search') . '%')
                ->orWhere('father_mobile_no', 'like', '%' . $request->input('search') . '%')
                ->orWhere('mother_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('mother_occoupation', 'like', '%' . $request->input('search') . '%')
                ->orWhere('mother_mobile_no', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('responsible_person', 'like', '%' . $request->input('search') . '%')
                ->orWhere('note', 'like', '%' . $request->input('search') . '%')
                ->orWhere('status', 'like', '%' . $request->input('search') . '%')
                ->orWhereHas('universities', function ($q) use ($request) {
                    $q->where('post_title', 'like', '%' . $request->input('search') . '%');
                
                });
                // ->orWhere('ssc_result', 'like', '%' . $request->input('search') . '%')
                // ->orWhere('hsc_result', 'like', '%' . $request->input('search') . '%');
            });
        }
        // if ($request->input('branch_id')) {
        //     $students->where('branch_id', $request->input('branch_id'));
        // }else{
        //     $students->where('branch_id', $currentUser->branch);
        // }

        $totalStudent = $students->count();
        $data['request'] = $request;
        $data['students'] = $students->paginate(20);
        $data['title'] = 'Student List - Total: ' . $totalStudent;
        $data['type'] = 'student';
        $data['url'] = 'students';
        $data['print'] = $request->input('print');
        $data['study_levels'] = Study::all();
        $data['subjects'] = Subject::all();
        $data['intakes'] = $this->intakeCreate();
        $data['universities'] = Post::select('id', 'post_title')->where('post_type', 'university')->get();
        $data['student_statuses'] = StudentStatus::select('id', 'name')->get();
        // return $data;
        if ($request->input('print') && $request->input('print') == 'yes') {
            $data['students'] = $students->get();
            return view('admin.students.print')->with($data);
        }
        return view('admin.students.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentYear= now()->year;
        $intake = [];
        for ($i=-2; $i < 3 && $i >-3; $i++) {
            $intake [] = 'March-' . ($currentYear+$i);
            $intake [] = 'September-' . ($currentYear+$i);
        }
        $data['intake'] = $intake;
        $users = User::select('id', 'name');
        $currentUser = Auth::user();
        $users->where('branch', $currentUser->branch);
        $data['student_statuses'] = StudentStatus::select('id', 'name')->get();
        $data['users'] = $users->get();
        $data['education_levels'] = EducationLevel::select('id', 'name')->get();
        $data['subjects'] = Subject::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['documents'] = Document::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['studies'] = Study::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['universities'] = Post::select('id', 'post_title')->orderby('post_title', 'ASC')->where('post_type', 'university')->get();

        $data['title'] = 'Student Create';
        $data['type'] = 'student';
        $data['url'] = 'students';

        // return $data;
        return view('admin.students.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        // $template = SmsTemplate::where('name', '[File Open]')->firstOrFail();
        // $body = "Minar khan 1995";
        // $subject = 'AR EDUCATION – File Processing Notification';
        // $name = 'Monir';
        // $email = 'minarkhan1995@gmail.com';
        // return $res = Mail::to($email)->send(new MailSystem($name, $body, $subject));
        
        // return $request;
        DB::beginTransaction();
        try {
            $student = new Student();
            $studentFillableData = $request->only($student->getFillable());
            $student->fill($studentFillableData)->save();
            $student->user_id = Auth::user()->id;
            $student->save();

            $student->subjects()->attach($request->subjects);
            $student->documents()->attach($request->documents);
            $student->universities()->attach($request->universities);


            $educations = $request->educations;
            $count = count($educations['education_level_id']); // total rows

            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $education = new EducationQualification();
                $education->education_level_id = $educations['education_level_id'][$i] ?? null;
                $education->attend_from        = $educations['attend_from'][$i] ?? null;
                $education->attend_to          = $educations['attend_to'][$i] ?? null;
                $education->result          = $educations['result'][$i] ?? null;
                $education->out_of          = $educations['out_of'][$i] ?? null;
                $education->country            = $educations['country'][$i] ?? null;
                $education->institution        = $educations['institution'][$i] ?? null;
                $education->group_subject      = $educations['group_subject'][$i] ?? null;
                $education->student_id         = $student->id; // if linked to a student
                $education->save();
            }

            if(isset($student->email)){
                $template = SmsTemplate::where('name', '[File Open]')->firstOrFail();
                $juniorCounselorName = $student->jncounselor ? $student->jncounselor->name : '';
                $juniorCounselorMobile = $student->jncounselor ? $student->jncounselor->mobile_no : '';
                $placeholders = [
                    '[Student Name]'   => $student->full_name,
                    '[Counselor Name]' => $juniorCounselorName . ($juniorCounselorMobile ? ' - ' . $juniorCounselorMobile : ''),
                ];
                $body = str_replace(
                    array_keys($placeholders),
                    array_values($placeholders),
                    $template->message
                );
                $subject = 'AR EDUCATION – File Processing Notification';
                $name = $student->full_name;
                $email = $student->email;
                $res = Mail::to($email)->send(new MailSystem($name, $body, $subject));
            }

            // Send notification to student after creation
            if (isset($student->user_id)) {
                $actor = Auth::user();
                $studentUser = $student->user;
                $actorName = $actor ? $actor->name : 'System';
                $notification = new \App\Notifications\StatusChangedNotification($student, null, 'New Student Created Successfully', 'student', $actorName);
                if ($actor && (!$studentUser || $actor->id !== $studentUser->id) && method_exists($actor, 'notify')) {
                    $actor->notify($notification);
                }
                if ($studentUser) {
                    $studentUser->notify($notification);
                }
            }

            DB::commit();
            Session::flash('success', 'Created successfully');
            // return redirect()->route('students.index');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return $th;
            // return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $student->load('subjects', 'universities', 'documents', 'universitiesAppStatus'); // relation load koro

        return view('admin.students.show', [
            'student' => $student,
            'title' => $student->full_name,
            'url' => 'student.application',
            // 'university_statuses' => UniversityStatus::select('id','name')->orderBy('id')->get()
        ]);
    }

    public function edit(Student $student)
    {
        // return $student->educationQualifications;
        $currentYear= now()->year;
        $intake = [];
        for ($i=-2; $i < 3 && $i >-3; $i++) {
            $intake [] = 'March-' . ($currentYear+$i);
            $intake [] = 'September-' . ($currentYear+$i);
        }
        $data['intake'] = $intake;

        $data['branches'] = Branch::select('id', 'name')->get();
        $data['student_statuses'] = StudentStatus::select('id', 'name')->get();
        $data['users'] = User::select('id', 'name')->get();
        $data['education_levels'] = EducationLevel::select('id', 'name')->get();
        $data['subjects'] = Subject::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['documents'] = Document::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['studies'] = Study::select('id', 'name')->orderBy('name', 'asc')->get();
        $data['universities'] = Post::select('id', 'post_title')->orderby('post_title', 'ASC')->where('post_type', 'university')->get();

        $data['student'] = $student;
        $data['title'] = 'Student Edit';
        $data['type'] = 'student';
        $data['url'] = 'students';
        // return $data;
        return view('admin.students.edit')->with($data);
    }

    public function update(StudentRequest $request, Student $student)
    {   
        // return $request;
        DB::beginTransaction();
        try {
            $oldStatus = $student->status;
            $oldStatusName = optional($student->student_status)->name;
            // $student = new Student();
            $studentFillableData = $request->only($student->getFillable());
            $student->fill($studentFillableData)->save();
            $student->user_id = Auth::user()->id;
            $student->save();

            // Status change notification logic
            if ($oldStatus != $student->status) {
                $actor = Auth::user();
                $studentUser = $student->user; // Assuming Student model has user() relation
                $student->load('student_status'); // Reload relationship to get new status name
                $newStatusName = optional($student->student_status)->name;
                $actorName = $actor ? $actor->name : 'System';
                $notification = new \App\Notifications\StatusChangedNotification($student, $oldStatusName, $newStatusName, 'student', $actorName);

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

                // Send email to student on status change using '[Student Status]' template
                if(isset($student->email)){
                    $template = \App\Models\SmsTemplate::where('name', '[Student Status]')->first();
                    if ($template) {
                        $juniorCounselorName = $student->jncounselor ? $student->jncounselor->name : '';
                        $juniorCounselorMobile = $student->jncounselor ? $student->jncounselor->mobile_no : '';
                        $placeholders = [
                            '[Student Name]'   => $student->full_name,
                            '[Status Name]'    => $newStatusName,
                            '[Counselor Name]' => $juniorCounselorName . ($juniorCounselorMobile ? ' - ' . $juniorCounselorMobile : ''),
                        ];
                        $body = str_replace(
                            array_keys($placeholders),
                            array_values($placeholders),
                            $template->message
                        );
                        $subject = 'AR EDUCATION – Student Status Changed';
                        $name = $student->full_name;
                        $email = $student->email;
                        \Mail::to($email)->send(new \App\Mail\MailSystem($name, $body, $subject));
                    }
                }
            }

            $student->subjects()->sync($request->subjects);
            $student->documents()->sync($request->documents);
            $student->universities()->sync($request->universities);

            $educations = $request->educations;
            $count = count($educations['education_level_id']); // total rows

            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $education = EducationQualification::findOrfail($educations['id'][$i]);
                $education->education_level_id = $educations['education_level_id'][$i] ?? null;
                $education->attend_from        = $educations['attend_from'][$i] ?? null;
                $education->attend_to          = $educations['attend_to'][$i] ?? null;
                $education->result          = $educations['result'][$i] ?? null;
                $education->out_of          = $educations['out_of'][$i] ?? null;
                $education->country            = $educations['country'][$i] ?? null;
                $education->institution        = $educations['institution'][$i] ?? null;
                $education->group_subject      = $educations['group_subject'][$i] ?? null;
                $education->student_id         = $student->id; // if linked to a student
                $education->save();
            }


            DB::commit();
            Session::flash('success', 'Update successfully');
            return redirect()->route('students.index');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }

        // return $request;
        DB::beginTransaction();
        try {
            $educations = $request->educations;
            $count = count($educations['education_level_id']); // total rows

            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                $education = new EducationQualification();
                $education->education_level_id = $educationsData['education_level_id'][$i] ?? null;
                $education->attend_from        = $educationsData['attend_from'][$i] ?? null;
                $education->attend_to          = $educationsData['attend_to'][$i] ?? null;
                $education->result          = $educationsData['result'][$i] ?? null;
                $education->out_of          = $educationsData['out_of'][$i] ?? null;
                $education->country            = $educationsData['country'][$i] ?? null;
                $education->institution        = $educationsData['institution'][$i] ?? null;
                $education->group_subject      = $educationsData['group_subject'][$i] ?? null;
                $education->student_id         = $student->id; // if linked to a student
                $education->save();
            }

            DB::commit();
            Session::flash('success', 'Created successfully');
            // return redirect()->route('students.index');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return $th;
            // return redirect()->back();
        }




        DB::beginTransaction();
        try {
            // $student = new Student();
            $studentFillableData = $request->only($student->getFillable());
            $student->fill($studentFillableData)->save();

            $student->subjects()->sync($request->subjects);
            $student->documents()->sync($request->documents);
            $student->universities()->sync($request->universities);
            DB::commit();
            Session::flash('success', 'Update successfully');
            return redirect()->route('students.index');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function destroy(Student $student)
    {
        if ($student) {
            // $student->delete();
            Session::flash('success', 'student deleted successfully');
            return redirect()->route('students.index');
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
    public function intakeCreate()
    {
        $currentYear= now()->year;
        $intake = [];
        for ($i=-2; $i < 3 && $i >-3; $i++) {
            $intake [] = 'March-' . ($currentYear+$i);
            $intake [] = 'September-' . ($currentYear+$i);
        }
        return $intake;
    }
}
