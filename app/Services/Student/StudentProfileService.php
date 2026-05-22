<?php

namespace App\Services\Student;

use App\Models\Student\Student;
use App\Support\StatusTone;
use App\Support\StudentDisplay;
use Illuminate\Support\Facades\Gate;

class StudentProfileService
{
    /** @var array<int, string> */
    private const RELATIONS = [
        'subjects',
        'universities',
        'documents',
        'student_status',
        'responsiblePerson',
        'sncounselor',
        'jncounselor',
        'studentCscaExams.cscaExam',
        'universitiesAppStatus.university',
        'universitiesAppStatus.subject',
        'universitiesAppStatus.statusUni',
        'universitiesAppStatus.statusAll.statusUni',
    ];

    /**
     * @return array<string, mixed>
     */
    public function viewData(Student $student): array
    {
        $student->load(self::RELATIONS);

        $applicationTones = [];
        foreach ($student->universitiesAppStatus as $application) {
            $applicationTones[$application->id] = StatusTone::forStatusId((int) ($application->status_id ?? 0));
        }

        return [
            'student' => $student,
            'title' => $student->full_name,
            'url' => 'student.application',
            'initials' => StudentDisplay::initials($student),
            'officeTone' => StatusTone::forStatusId((int) ($student->status ?? 0)),
            'canViewCredentials' => Gate::allows('viewPortalCredentials', $student),
            'applicationCount' => $student->universitiesAppStatus->count(),
            'applicationTones' => $applicationTones,
            'csca' => StudentDisplay::cscaPresentation($student),
            'personalDetails' => StudentDisplay::detailRows([
                'Full Name' => $student->full_name,
                'Email' => $student->email,
                'Phone' => $student->mobile_no,
                'Address' => StudentDisplay::addressLine($student),
                'Intake' => $student->intake,
                'Preferred Country' => $student->preferred_country,
                'Gender' => $student->gender,
            ]),
            'fatherDetails' => StudentDisplay::detailRows([
                'Name' => $student->father_name,
                'Phone' => $student->father_mobile_no,
                'Occupation' => $student->father_occoupation,
            ]),
            'motherDetails' => StudentDisplay::detailRows([
                'Name' => $student->mother_name,
                'Phone' => $student->mother_mobile_no,
                'Occupation' => $student->mother_occoupation,
            ]),
            'teamDetails' => StudentDisplay::detailRows([
                'Employee' => optional($student->responsiblePerson)->name,
                'Senior Counselor' => optional($student->sncounselor)->name,
                'Junior Counselor' => optional($student->jncounselor)->name,
            ]),
        ];
    }
}
