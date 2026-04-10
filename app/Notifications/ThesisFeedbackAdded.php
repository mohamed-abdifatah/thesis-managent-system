<?php

namespace App\Notifications;

use App\Models\Feedback;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThesisFeedbackAdded extends Notification
{
    use Queueable;

    public function __construct(
        private Thesis $thesis,
        private Feedback $feedback,
        private ?ThesisVersion $version = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'thesis_feedback_added',
            'thesis_id' => $this->thesis->id,
            'thesis_title' => $this->thesis->title,
            'version_id' => $this->version?->id,
            'version_number' => $this->version?->version_number,
            'comment' => $this->feedback->comment,
        ];
    }
}
