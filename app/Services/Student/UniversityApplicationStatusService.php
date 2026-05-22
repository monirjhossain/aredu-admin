<?php

namespace App\Services\Student;

use App\Mail\MailSystem;
use App\Models\SmsTemplate;
use App\Models\Student\Student;
use App\Models\Student\StudentUniversity;
use App\Models\Student\UniversityApplicationStatus;
use App\Notifications\StatusChangedNotification;
use App\Services\Status\RoleBasedStatusNotifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UniversityApplicationStatusService
{
    /** @var RoleBasedStatusNotifier */
    private $notifier;

    public function __construct(RoleBasedStatusNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @return array{changed: bool, status_name: string|null}
     */
    public function update(StudentUniversity $application, int $newStatusId): array
    {
        if ((int) $application->status_id === $newStatusId) {
            $application->loadMissing('statusUni');

            return [
                'changed' => false,
                'status_name' => optional($application->statusUni)->name,
            ];
        }

        $oldStatusName = optional($application->statusUni)->name;
        $application->status_id = $newStatusId;
        $application->updated_by = Auth::id();
        $application->save();
        $application->load('statusUni');
        $newStatusName = optional($application->statusUni)->name;

        $this->recordHistory($application, $newStatusId);
        $this->notifyChange($application, $oldStatusName, $newStatusName);

        return ['changed' => true, 'status_name' => $newStatusName];
    }

    public function recordHistory(StudentUniversity $application, int $statusId): void
    {
        UniversityApplicationStatus::where('student_university_id', $application->id)->update(['active' => 0]);
        UniversityApplicationStatus::create([
            'student_university_id' => $application->id,
            'status_id' => $statusId,
            'updated_by' => Auth::id(),
            'active' => 1,
        ]);
    }

    public function notifyChange(
        StudentUniversity $application,
        ?string $oldStatusName,
        ?string $newStatusName,
        string $eventType = 'status_changed',
        bool $sendStatusEmail = true
    ): void {
        try {
            $actor = Auth::user();
            $student = Student::find($application->student_id);
            $studentUser = $student ? $student->user : null;
            $actorName = $actor ? $actor->name : 'System';
            $notification = new StatusChangedNotification(
                $application,
                $oldStatusName,
                $newStatusName,
                'university',
                $actorName,
                $eventType
            );

            $this->notifier->broadcast($notification, $studentUser);

            if ($sendStatusEmail && $eventType === 'status_changed' && $student && !empty($student->email)) {
                $this->sendStatusEmail($student, $newStatusName);
            }
        } catch (\Throwable $e) {
            // Status is saved; notification/mail must not block the request.
        }
    }

    private function sendStatusEmail(Student $student, ?string $newStatusName): void
    {
        $template = SmsTemplate::where('name', '[Status Change]')->first();

        if (!$template) {
            return;
        }

        $student->loadMissing('jncounselor');
        $juniorCounselorName = $student->jncounselor ? $student->jncounselor->name : 'N/A';
        $juniorCounselorMobile = $student->jncounselor ? $student->jncounselor->mobile_no : 'N/A';
        $placeholders = [
            '[Student Name]' => $student->full_name,
            '[Status Name]' => $newStatusName,
            '[Counselor Name]' => $juniorCounselorName . ($juniorCounselorMobile ? ' - ' . $juniorCounselorMobile : ''),
        ];
        $body = str_replace(array_keys($placeholders), array_values($placeholders), $template->message);

        Mail::to($student->email)->send(
            new MailSystem($student->full_name, $body, 'AR EDUCATION – University Status Changed')
        );
    }
}
