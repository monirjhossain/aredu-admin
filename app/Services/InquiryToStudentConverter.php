<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Study;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InquiryToStudentConverter
{
    public function convert(Inquiry $inquiry): Student
    {
        if ($inquiry->student_id) {
            return Student::findOrFail($inquiry->student_id);
        }

        $branchId = $inquiry->branch_id;
        if (!$branchId && Auth::check()) {
            $branchId = Auth::user()->branch;
        }

        if (!$branchId) {
            throw new RuntimeException('Branch is required to convert inquiry to student.');
        }

        return DB::transaction(function () use ($inquiry, $branchId) {
            $study = null;
            if ($inquiry->course) {
                $study = Study::where('name', $inquiry->course)->first();
            }

            $student = Student::create([
                'branch_id' => $branchId,
                'user_id' => Auth::id(),
                'first_name' => $inquiry->first_name,
                'last_name' => $inquiry->last_name,
                'email' => $inquiry->email,
                'mobile_no' => $inquiry->mobile_no,
                'country' => $inquiry->country,
                'preferred_country' => $inquiry->country_preferred,
                'last_education' => $inquiry->last_education,
                'study_level' => $inquiry->course,
                'study_id' => $study ? $study->id : null,
                'subject' => $inquiry->subject,
                'note' => $this->buildNote($inquiry),
                'intake' => (string) date('Y'),
                'ref' => 'Inquiry #' . $inquiry->id,
                'status' => 1,
            ]);

            if ($inquiry->university) {
                $university = Post::where('post_type', 'university')
                    ->where('post_title', $inquiry->university)
                    ->first();

                if ($university) {
                    $student->universities()->syncWithoutDetaching([$university->id]);
                }
            }

            if ($inquiry->subject) {
                $subject = Subject::where('name', $inquiry->subject)->first();

                if ($subject) {
                    $student->subjects()->syncWithoutDetaching([$subject->id]);
                }
            }

            $inquiry->update([
                'student_id' => $student->id,
                'view' => 1,
            ]);

            return $student->fresh(['universities', 'subjects']);
        });
    }

    private function buildNote(Inquiry $inquiry): ?string
    {
        $parts = array_filter([
            $inquiry->message,
            $inquiry->type ? 'Source: ' . $inquiry->type : null,
            $inquiry->university ? 'University: ' . $inquiry->university : null,
            $inquiry->subject ? 'Subject: ' . $inquiry->subject : null,
        ]);

        if (empty($parts)) {
            return null;
        }

        return implode("\n", $parts);
    }
}
