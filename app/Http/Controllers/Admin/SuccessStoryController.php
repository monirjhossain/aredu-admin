<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Models\Branch;
use App\Models\Document;
use App\Models\EducationLevel;
use App\Models\EducationQualification;
use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
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
use PhpParser\Node\Stmt\Return_;

class SuccessStoryController extends Controller
{

    use UploadAble;

    public function show($slug)
    {
        // return $slug;
        $student = User::where('id', $slug)->firstOrFail();
        // $student = Student::where('id', $slug)->firstOrFail();

        return view('admin.students.successstory', [
            'student' => $student,
            'title' => $student->full_name,
            'url' => 'student.application',
            // 'url' => 'successstory.show',
            // 'university_statuses' => UniversityStatus::select('id','name')->orderBy('id')->get()
        ]);
    }

}
