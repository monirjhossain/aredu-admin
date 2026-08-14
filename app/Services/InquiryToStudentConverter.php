<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Study;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class InquiryToStudentConverter
{
    public function convert(Inquiry $inquiry): Student
    {
        if ($inquiry->student_id) {
            $existing = Student::find($inquiry->student_id);
            if ($existing) {
                return $existing;
            }
        }

        $branchId = $inquiry->branch_id;
        if (!$branchId && Auth::check()) {
            $branchId = Auth::user()->branch;
        }

        if (!$branchId) {
            throw new RuntimeException('Branch is required to convert inquiry to student. Please set branch on this inquiry first.');
        }

        $branch = \App\Models\Branch::find($branchId);
        if (!$branch) {
            throw new RuntimeException('Branch not found for this inquiry (branch_id: ' . $branchId . ').');
        }

        try {
            return DB::transaction(function () use ($inquiry, $branchId) {
                $study = null;
                if ($inquiry->course) {
                    $study = Study::where('name', $inquiry->course)->first();
                }

                $subjectParts = preg_split('/\s*,\s*/', (string) $inquiry->subject);
                $firstSubject = $subjectParts ? trim((string) $subjectParts[0]) : null;

                $payload = $this->filterExistingColumns([
                    'branch_id' => $branchId,
                    'user_id' => Auth::id(),
                    'first_name' => $inquiry->first_name,
                    'last_name' => $inquiry->last_name,
                    'email' => $inquiry->email,
                    'mobile_no' => $inquiry->mobile_no,
                    'country' => $inquiry->country,
                    'preferred_country' => $inquiry->country_preferred,
                    'study_level' => $inquiry->course,
                    'study_id' => $study ? $study->id : null,
                    'subject' => $firstSubject ?: null,
                    'last_education' => $inquiry->last_education,
                    'note' => $this->buildNote($inquiry),
                    'intake' => (string) date('Y'),
                    'ref' => $inquiry->tracking_id ?: ('Inquiry #' . $inquiry->id),
                    'status' => 1,
                ]);

                $student = Student::create($payload);

                $this->syncUniversities($student, $inquiry);
                $this->syncSubjects($student, $inquiry);

                $inquiry->forceFill([
                    'student_id' => $student->id,
                    'view' => 1,
                ])->save();

                return $student->fresh(['universities', 'subjects']);
            });
        } catch (\Throwable $e) {
            Log::error('Inquiry to student conversion failed', [
                'inquiry_id' => $inquiry->id,
                'branch_id' => $branchId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    /**
     * Keep only columns that actually exist on students table.
     * Live DB schema can differ from migrations/local.
     */
    private function filterExistingColumns(array $payload): array
    {
        $filtered = [];

        foreach ($payload as $column => $value) {
            if (Schema::hasColumn('students', $column)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }

    private function syncUniversities(Student $student, Inquiry $inquiry): void
    {
        if (!$inquiry->university) {
            return;
        }

        try {
            $universityNames = collect(preg_split('/\s*,\s*/', $inquiry->university))
                ->map(function ($name) {
                    return trim((string) $name);
                })
                ->filter()
                ->values();

            if ($universityNames->isEmpty()) {
                return;
            }

            $universityIds = Post::where('post_type', 'university')
                ->whereIn('post_title', $universityNames->all())
                ->pluck('id')
                ->all();

            if (!empty($universityIds)) {
                $student->universities()->syncWithoutDetaching($universityIds);
            }
        } catch (\Throwable $e) {
            Log::warning('Inquiry convert: university sync skipped', [
                'inquiry_id' => $inquiry->id,
                'student_id' => $student->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function syncSubjects(Student $student, Inquiry $inquiry): void
    {
        if (!$inquiry->subject) {
            return;
        }

        try {
            $subjectNames = collect(preg_split('/\s*,\s*/', $inquiry->subject))
                ->map(function ($name) {
                    return trim((string) $name);
                })
                ->filter()
                ->values();

            if ($subjectNames->isEmpty()) {
                return;
            }

            $subjectIds = Subject::whereIn('name', $subjectNames->all())
                ->pluck('id')
                ->all();

            if (!empty($subjectIds)) {
                $student->subjects()->syncWithoutDetaching($subjectIds);
            }
        } catch (\Throwable $e) {
            Log::warning('Inquiry convert: subject sync skipped', [
                'inquiry_id' => $inquiry->id,
                'student_id' => $student->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function buildNote(Inquiry $inquiry): ?string
    {
        $parts = array_filter([
            $inquiry->message,
            $inquiry->type ? 'Source: ' . $inquiry->type : null,
            $inquiry->tracking_id ? 'Tracking ID: ' . $inquiry->tracking_id : null,
            $inquiry->university ? 'University: ' . $inquiry->university : null,
            $inquiry->subject ? 'Subject: ' . $inquiry->subject : null,
        ]);

        if (empty($parts)) {
            return null;
        }

        return implode("\n", $parts);
    }
}
