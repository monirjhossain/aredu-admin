<?php

namespace App\Services\Student;

use App\Mail\MailSystem;
use App\Models\SmsTemplate;
use App\Models\Student\Student;
use App\Notifications\StatusChangedNotification;
use App\Services\Status\RoleBasedStatusNotifier;
use Illuminate\Support\Facades\Mail;

class OfficeStatusService
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
    public function update(Student $student, int $newStatusId): array
    {
        if ((int) $student->status === $newStatusId) {
            $student->loadMissing('student_status');

            return [
                'changed' => false,
                'status_name' => optional($student->student_status)->name,
            ];
        }

        $oldStatusName = optional($student->student_status)->name;
        $student->status = $newStatusId;
        $student->save();
        $student->load('student_status');
        $newStatusName = optional($student->student_status)->name;

        app(StudentIntakeAttemptService::class)->syncStatusToActiveAttempt($student, $newStatusId);

        $this->notifyChange($student, $oldStatusName, $newStatusName, 'status_changed', true);

        return ['changed' => true, 'status_name' => $newStatusName];
    }

    public function notifyChange(
        Student $student,
        ?string $oldStatusName,
        ?string $newStatusName,
        string $eventType = 'status_changed',
        bool $sendStatusEmail = true
    ): void {
        try {
            $actor = auth()->user();
            $actorName = $actor ? $actor->name : 'System';
            $notification = new StatusChangedNotification(
                $student,
                $oldStatusName,
                $newStatusName,
                'student',
                $actorName,
                $eventType
            );

            $this->notifier->broadcast($notification, $student->user);

            if ($sendStatusEmail && $eventType === 'status_changed' && !empty($student->email)) {
                $this->sendStatusEmail($student, $newStatusName);
            }
        } catch (\Throwable $e) {
            // Status is saved; notification/mail must not block the request.
        }
    }

    private function sendStatusEmail(Student $student, ?string $newStatusName): void
    {
        $template = SmsTemplate::where('name', '[Student Status]')->first();

        if (!$template) {
            return;
        }

        $student->loadMissing('jncounselor');
        $juniorCounselorName = $student->jncounselor ? $student->jncounselor->name : '';
        $juniorCounselorMobile = $student->jncounselor ? $student->jncounselor->mobile_no : '';
        $placeholders = [
            '[Student Name]' => $student->full_name,
            '[Status Name]' => $newStatusName,
            '[Counselor Name]' => $juniorCounselorName . ($juniorCounselorMobile ? ' - ' . $juniorCounselorMobile : ''),
        ];
        $body = str_replace(array_keys($placeholders), array_values($placeholders), $template->message);

        Mail::to($student->email)->send(
            new MailSystem($student->full_name, $body, 'AR EDUCATION – Student Status Changed')
        );
    }
}
