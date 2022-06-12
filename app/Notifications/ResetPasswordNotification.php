<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    private $resetUrl = "";
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $resetUrl
     */
    public function __construct(string $resetUrl = "")
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
                    ->line(__('auth.resetEmailIntro'))
                    ->action(__('auth.resetEmailAction'), $this->resetUrl)
                    ->line(__('auth.resetEmailOutro'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            //
        ];
    }
}
