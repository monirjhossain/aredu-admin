<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangedNotification extends Notification
{
    use Queueable;

    protected $model;
    protected $oldStatus;
    protected $newStatus;
    protected $type;
    protected $url;
    protected $actorName;

    public function __construct($model, $oldStatus, $newStatus, $type, $actorName = null)
    {
        $this->model = $model;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->type = $type;
        $this->actorName = $actorName;
        // Determine URL based on type
        if ($type === 'student') {
            $this->url = route('students.show', $model->id);
        } elseif ($type === 'university') {
            // University application notification will redirect to student details page
            $this->url = route('students.show', $model->student_id);
        } else {
            $this->url = url('/');
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Get the template for status change (create a new template in settings with name '[Status Change]')
        $template = \App\Models\SmsTemplate::where('name', '[Status Change]')->first();
        $body = '';
        // Try to get junior counselor info from model (student or application)
        $juniorCounselorName = '';
        $juniorCounselorMobile = '';
        if (isset($this->model->jncounselor) && $this->model->jncounselor) {
            $juniorCounselorName = $this->model->jncounselor->name;
            $juniorCounselorMobile = $this->model->jncounselor->mobile_no;
        }
        $counselorValue = $juniorCounselorName . ($juniorCounselorMobile ? ' - ' . $juniorCounselorMobile : '');
        if ($template) {
            $placeholders = [
                '[Student Name]'   => $notifiable->name,
                '[Status Name]'    => $this->newStatus,
                '[Counselor Name]' => $counselorValue,
            ];
            $body = str_replace(
                array_keys($placeholders),
                array_values($placeholders),
                $template->message
            );
        } else {
            $body = 'Dear ' . $notifiable->name . ',<br>Your status has been updated by <b>' . $counselorValue . '</b> to <b>' . $this->newStatus . '</b>.';
        }
        $body .= '<br><a href="' . $this->url . '">View Details</a>';
        $subject = 'Student Status Update';
        return (new \App\Mail\MailSystem($notifiable->name, $body, $subject));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $note = '';
        $remarks = '';
        if (isset($this->model->note) && $this->model->note) {
            $note = $this->model->note;
        }
        if (isset($this->model->remarks) && $this->model->remarks) {
            $remarks = $this->model->remarks;
        }
        $extra = '';
        if ($note) {
            $extra .= ' Note: ' . $note . '.';
        }
        if ($remarks) {
            $extra .= ' Remarks: ' . $remarks . '.';
        }

        // Build message with Student Name - University Name - old to new Status changed - by Actor
        $studentName = '';
        $universityName = '';
        if ($this->type === 'university') {
            $student = \App\Models\Student\Student::find($this->model->student_id);
            $studentName = $student ? $student->full_name : 'Unknown';
            $universityName = optional($this->model->university)->post_title ?? '';
        } else {
            $studentName = $this->model->full_name ?? ($this->model->first_name . ' ' . $this->model->last_name);
        }

        if (empty($this->oldStatus) && $universityName) {
            // New university application added
            $message = "{$studentName} - {$universityName} - {$this->newStatus} - by {$this->actorName}.";
        } elseif (empty($this->oldStatus)) {
            // New student created
            $message = "{$studentName} - {$this->newStatus} - by {$this->actorName}.";
        } elseif ($universityName) {
            $message = "{$studentName} - {$universityName} - {$this->oldStatus} to {$this->newStatus} Status changed - by {$this->actorName}.";
        } else {
            $message = "{$studentName} - {$this->oldStatus} to {$this->newStatus} Status changed - by {$this->actorName}.";
        }

        return [
            'message' => $message . $extra,
            'url' => $this->url,
            'type' => $this->type,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'actor' => $this->actorName,
            'model_id' => $this->model->id,
            'note' => $note,
            'remarks' => $remarks,
        ];
    }
}
