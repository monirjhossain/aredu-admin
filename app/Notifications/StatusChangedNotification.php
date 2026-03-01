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
        if ($template) {
            $placeholders = [
                '[Student Name]'   => $notifiable->name,
                '[Status Name]'    => $this->newStatus,
                '[Actor Name]'     => $this->actorName,
            ];
            $body = str_replace(
                array_keys($placeholders),
                array_values($placeholders),
                $template->message
            );
        } else {
            $body = 'Dear ' . $notifiable->name . ',<br>Your status has been updated by <b>' . $this->actorName . '</b> to <b>' . $this->newStatus . '</b>.';
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
        return [
            'message' => ucfirst($this->type) . " status changed by {$this->actorName} to {$this->newStatus}.",
            'url' => $this->url,
            'type' => $this->type,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'actor' => $this->actorName,
            'model_id' => $this->model->id,
        ];
    }
}
