<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $allStudent = Student::orderBy('id', 'DESC')->count();
        // $intakes = $students->whereIn('intake', ['March-2024', 'September-2024']);
        // $totalStudents = $students->count();
        $admittedToUs = Student::orderBy('id', 'DESC')->whereIn('intake', ['March-2024', 'September-2024'])->where('status', 'Admitted to us')->count();
        $Rejected = Student::orderBy('id', 'DESC')->whereIn('intake', ['March-2024', 'September-2024'])->where('status', 'Rejected')->count();
        $paperSubmitted = Student::orderBy('id', 'DESC')->whereIn('intake', ['March-2024', 'September-2024'])->where('status', 'LIKE', '%Paper submitted%')->count();
        $AdmissionCancel = Student::orderBy('id', 'DESC')->whereIn('intake', ['March-2024', 'September-2024'])->where('status', 'LIKE', '%Admission cancel%')->count();
        $totalStudents = $admittedToUs + $paperSubmitted + $Rejected + $AdmissionCancel;
        
        $data['total_user'] = User::all()->count();
        $data['total_todo'] = Todo::all()->count();
        $data['universities'] = Post::where('post_type', 'university')->count();
        $data['page_title'] = 'Dashboard - AR EDUCATION';
        $data['admittedToUs'] = $admittedToUs;
        $data['totalStudents'] = $totalStudents;
        $data['allStudent'] = $allStudent;
        $data['paperSubmitted'] = $paperSubmitted;
        $data['Rejected'] = $Rejected;
        $data['AdmissionCancel'] = $AdmissionCancel;

        // return $data;
        return view('dashboard.main')->with($data);

    }
    public function logincheck()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return redirect('login');
        }
    }
}
