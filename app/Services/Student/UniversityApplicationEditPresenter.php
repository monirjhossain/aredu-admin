<?php

namespace App\Services\Student;

use App\Models\Student\StudentUniversity;
use App\Support\StatusTone;
use App\Support\StudentDisplay;

class UniversityApplicationEditPresenter
{
    /** @var array<int, string> */
    private const RELATIONS = ['university', 'subject', 'statusUni', 'student'];

    /**
     * @return array<string, mixed>
     */
    public function viewData(StudentUniversity $application): array
    {
        $application->load(self::RELATIONS);
        $student = $application->student;

        if (!$student) {
            abort(404, 'Student not found for this application.');
        }

        return [
            'application' => $application,
            'student' => $student,
            'title' => 'Edit Application',
            'type' => 'application',
            'url' => 'student.application',
            'studentInitials' => StudentDisplay::initials($student),
            'statusTone' => StatusTone::forStatusId((int) ($application->status_id ?? 0)),
            'portalType' => (int) old('portal_type', $application->portal_type ?? 1),
        ];
    }
}
