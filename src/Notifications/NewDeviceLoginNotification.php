<?php

namespace Sndpbag\AdminPanel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class NewDeviceLoginNotification extends Notification
{
    use Queueable;

    protected $ip_address;

    public function __construct($ip_address)
    {
        $this->ip_address = $ip_address;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $time = Carbon::now()->format('M d, Y h:i A');
        $appName = config('app.name');

        return (new MailMessage)
            ->subject("Security Alert: New Login from Unknown Device")
            ->greeting("Hello {$notifiable->name},")
            ->line("We detected a login to your account from a new IP address or device.")
            ->line("**Login Details:**")
            ->line("- **IP Address:** {$this->ip_address}")
            ->line("- **Time:** {$time}")
            ->line("If this was you, you can safely ignore this email. However, if you didn't log in, we recommend changing your password immediately to secure your account.")
            ->action('Secure Your Account', url(route('password.request')))
            ->line('Thank you for using our application!')
            ->salutation("Regards, \n {$appName}");
    }
}