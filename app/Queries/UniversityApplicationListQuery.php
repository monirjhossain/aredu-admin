<?php

namespace App\Queries;

use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Students joined to student_university for a single university application list.
 */
final class UniversityApplicationListQuery
{
    public static function make(int $universityId, ?int $applicationStatusId = null, ?int $branchId = null): Builder
    {
        $query = Student::query()
            ->select('students.*')
            ->join('student_university', function ($join) use ($universityId) {
                $join->on('students.id', '=', 'student_university.student_id')
                    ->where('student_university.university_id', $universityId);
            });

        if ($applicationStatusId !== null) {
            $query->where('student_university.status_id', $applicationStatusId);
        }

        if ($branchId !== null) {
            $query->where('students.branch_id', $branchId);
        }

        return $query->orderBy('student_university.status_id');
    }

    /**
     * @return array<string, mixed>
     */
    public static function listEagerLoads(int $universityId): array
    {
        return [
            'educationQualifications',
            'branchInfo',
            'universitiesAppStatus' => function ($query) use ($universityId) {
                $query->where('university_id', $universityId)
                    ->with(['statusUni', 'subject']);
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function exportEagerLoads(int $universityId): array
    {
        return [
            'student_status',
            'branchInfo',
            'subjects',
            'universitiesAppStatus' => function ($query) use ($universityId) {
                $query->where('university_id', $universityId)
                    ->with(['statusUni', 'subject']);
            },
            'educationQualifications',
            'sncounselor',
            'jncounselor',
        ];
    }

    public static function resolveApplicationStatusId(?string $raw): ?int
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $id = (int) $raw;

        return $id > 0 ? $id : null;
    }

    public static function pageTitle(Post $university, ?string $statusName): string
    {
        $title = 'Application List - ' . $university->post_title;

        return $statusName ? $title . ' (' . $statusName . ')' : $title;
    }

    public static function exportFilename(Post $university, ?string $statusName): string
    {
        $suffix = $statusName
            ? '_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $statusName)
            : '';

        return $university->post_title . '_Application_Report' . $suffix . '.csv';
    }

    /**
     * @param Collection<int, Student> $students
     * @return array<int, array<string, string>>
     */
    public static function toExportRows(Collection $students, Post $university): array
    {
        if ($students->isEmpty()) {
            return [self::emptyExportRow('No data found for selected filters')];
        }

        $rows = [];
        $sl = 1;

        foreach ($students as $student) {
            $application = $student->universityById($university->id);
            $education = $student->educationQualifications->sortByDesc('id')->first();

            $rows[] = [
                'Sl' => (string) $sl++,
                'Application No' => $application->application_no ?? '',
                'Name' => $student->full_name ?? '',
                'Passport' => $student->passport ?? '',
                'CGPA' => optional($education)->result ?? '',
                'Passing Year' => optional(optional($education)->attend_to)->format('Y') ?? '',
                'Branch' => optional($student->branchInfo)->name ?? '',
                'Subject' => optional(optional($application)->subject)->name ?? '',
                'Mobile' => $student->mobile_no ?? '',
                'Status' => optional(optional($application)->statusUni)->name ?? '',
                'language' => $student->language_cer ?? '',
                'Link' => $university->getMeta('link') ?? '',
                'User' => self::exportLoginUser($application, $university),
                'Password' => self::exportLoginPassword($application, $university),
                'CSCA' => '-',
                'Junior Counselor' => optional($student->jncounselor)->name ?? '',
                'Note' => $application->note ?? '',
                'Remarks' => $application->remarks ?? '',
            ];
        }

        return $rows;
    }

    /**
     * @return array<string, string>
     */
    private static function emptyExportRow(string $message): array
    {
        return [
            'Sl' => '',
            'Application No' => '',
            'Name' => $message,
            'Passport' => '',
            'CGPA' => '',
            'Passing Year' => '',
            'Branch' => '',
            'Subject' => '',
            'Mobile' => '',
            'Status' => '',
            'language' => '',
            'Link' => '',
            'User' => '',
            'Password' => '',
            'CSCA' => '',
            'Junior Counselor' => '',
            'Note' => '',
            'Remarks' => '',
        ];
    }

    private static function exportLoginUser(?StudentUniversity $application, Post $university): string
    {
        if (!$application) {
            return '';
        }

        return (int) $application->portal_type === 1
            ? (string) ($university->getMeta('user') ?? '')
            : (string) ($application->login_id ?? '');
    }

    private static function exportLoginPassword(?StudentUniversity $application, Post $university): string
    {
        if (!$application) {
            return '';
        }

        return (int) $application->portal_type === 1
            ? (string) ($university->getMeta('password') ?? '')
            : (string) ($application->login_password ?? '');
    }
}
