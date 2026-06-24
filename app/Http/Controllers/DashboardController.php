<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\User;
use App\Services\Dashboard\DashboardAnalyticsService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /** @var DashboardAnalyticsService */
    private $analytics;

    public function __construct(DashboardAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        $currentUser = Auth::user();
        $analytics = $this->analytics->forUser($currentUser);

        $students = Student::query()->orderByDesc('id');
        $students->where(function ($q) use ($currentUser) {
            $q->where('junior_counselor', $currentUser->id);
        });

        return view('dashboard.main', [
            'page_title' => 'Dashboard - AR EDUCATION',
            'analytics' => $analytics,
            'cards' => $analytics['cards'],
            'charts' => $analytics['charts'],
            'counselor_only' => $analytics['counselor_only'],
            'myStudents' => $students->count(),
            'allStudent' => Student::where('branch_id', $currentUser->branch)->count(),
            'total_user' => User::where('branch', $currentUser->branch)->count(),
            'universities' => Post::where('post_type', 'university')->count(),
        ]);
    }

    public function logincheck()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }

        return redirect('login');
    }
}
