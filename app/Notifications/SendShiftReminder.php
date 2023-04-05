<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendShiftReminder extends Notification
{
    use Queueable;

    private string $planLink;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct(string $planLink)
    {
        $this->planLink = $planLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        # TODO: remember the lang of the subscription
        return (new MailMessage)
            ->subject(__('subscription.reminder', locale: 'de') . " / " . __('subscription.reminder', locale: 'en'))
            ->line(__('subscription.reminderBody', locale: 'de'))
            ->line(__('subscription.reminderBody', locale: 'en'))
            ->line($this->planLink);
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
            //
        ];
    }
}
