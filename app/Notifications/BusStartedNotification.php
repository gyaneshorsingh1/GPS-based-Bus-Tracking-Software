<?php

namespace App\Notifications;

use App\Models\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BusStartedNotification extends Notification
{
    use Queueable;

    public function __construct(public Bus $bus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'bus',
            'title' => 'Bus Started',
            'message' => 'Bus '.$this->bus->bus_number.' has started its journey and is now online.',
            'bus_id' => $this->bus->id,
            'bus_number' => $this->bus->bus_number,
        ];
    }
}
