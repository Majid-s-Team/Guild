<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CommentModeratedNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['broadcast','database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Your comment is now moderated.'
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => 'Your comment is now moderated.'
        ]);
    }
}
