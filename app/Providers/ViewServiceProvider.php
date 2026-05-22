<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Providers\AppServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Branch;
use App\Models\StudentStatus;
use App\Models\UniversityStatus;
use App\Support\StatusDropdownCache;
use App\Support\StatusTone;
use Illuminate\Support\Facades\Cache;


class ViewServiceProvider extends AppServiceProvider
{
    public function boot()
    {
        view()->composer('layouts.base.aside', function ($view) {
        $branches = Branch::all();
            $view->with('branches', $branches);
        });

        view()->composer([
            'admin.students.index',
            'admin.students.university-student-list',
        ], function ($view) {
            $view->with([
                'student_statuses' => Cache::remember(StatusDropdownCache::STUDENT_STATUSES, 3600, function () {
                    return StudentStatus::select('id', 'name')->orderBy('id')->get();
                }),
                'university_statuses' => Cache::remember(StatusDropdownCache::UNIVERSITY_STATUSES, 3600, function () {
                    return UniversityStatus::select('id', 'name')->orderBy('id')->get();
                }),
                'status_tone_map' => StatusTone::map(),
            ]);
        });
    }
    public function register()
    {
        //
    }
}
