<?php

namespace Eduka\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class SendSMSNotification extends Notification
{
    use Queueable;

    public $earnings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($checkout)
    {
        $this->earnings = $checkout['earnings'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['nexmo'];
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
                    ->content('Purchase completed with earnings USD '.$this->earnings)
                    ->from('Mastering Nova');
    }
}
