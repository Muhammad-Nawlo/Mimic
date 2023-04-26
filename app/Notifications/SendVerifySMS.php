<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class SendVerifySMS extends Notification
{
    public function __construct()
    {
        //
    }

    public function via($notifiable): array
    {
        return ['nexmo'];
    }

    public function toNexmo($notifiable): NexmoMessage
    {
        return (new NexmoMessage())
            ->from(env('Nexmo_SMS_FROM'))
            ->content(__('site.Mobile Number Verification Code', ['code' => $notifiable->mobile_verify_code]));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
