<?php

namespace App\Notifications\Frontend;

use Illuminate\Notifications\Notification;

class StreamChannel 
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toStreamActivity($notifiable);

        // Send notification to the $notifiable instance...
    }
}
