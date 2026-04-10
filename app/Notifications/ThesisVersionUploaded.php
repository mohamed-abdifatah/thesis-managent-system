<?php

namespace App\Notifications;

use App\Models\Thesis;
use App\Models\ThesisVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThesisVersionUploaded extends Notification
{
    use Queueable;

    public function __construct(
        private Thesis $thesis,
        private ThesisVersion $version
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'thesis_version_uploaded',
            'thesis_id' => $this->thesis->id,
            'thesis_title' => $this->thesis->title,
            'version_id' => $this->version->id,
            'version_number' => $this->version->version_number,
        ];
    }
}
