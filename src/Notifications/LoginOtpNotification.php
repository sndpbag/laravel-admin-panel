<?php

namespace Sndpbag\AdminPanel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    use Queueable;
      public $otp;
    

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
       $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
        
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       return (new MailMessage)
                    ->subject('Your One-Time Password (OTP) for Login')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Here is your OTP to complete your login. Please use the code below:')
                    ->line($this->otp)
                    ->line('This OTP is valid for 5 minutes.');
    }
 

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
