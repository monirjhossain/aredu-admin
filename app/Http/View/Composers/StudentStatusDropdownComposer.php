<?php

namespace App\Http\View\Composers;

use App\Models\StudentStatus;
use App\Models\UniversityStatus;
use App\Support\StatusDropdownCache;
use App\Support\StatusTone;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class StudentStatusDropdownComposer
{
    /** @var array<int, string> */
    private static $views = [
        'admin.students.index',
        'admin.students.university-student-list',
        'admin.students.show',
        'admin.student_applications.edit',
    ];

    public function compose(View $view): void
    {
        $view->with([
            'student_statuses' => Cache::remember(StatusDropdownCache::STUDENT_STATUSES, 3600, function () {
                return StudentStatus::select('id', 'name')->orderBy('id')->get();
            }),
            'university_statuses' => Cache::remember(StatusDropdownCache::UNIVERSITY_STATUSES, 3600, function () {
                return UniversityStatus::select('id', 'name')->orderBy('id')->get();
            }),
            'status_tone_map' => StatusTone::map(),
        ]);
    }

    public static function views(): array
    {
        return self::$views;
    }
}
