<?php
// app/Notifications/ResetPasswordNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;          // ← add this
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;                                     // ← add this

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
        // optional: you can delay it or set a specific queue
        // $this->delay(now()); 
        // $this->onQueue('emails');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = config('app.frontend_url')
             . "http://127.0.0.1:8000/reset-password?token={$this->token}&email="
             . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello!')
            ->line('You requested a password reset. Click the button below to choose a new one:')
            ->action('Reset Password', $url)
            ->line('If you didn’t request this, just ignore this email.');
    }
}
