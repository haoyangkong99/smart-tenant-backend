<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RentReminderNotification extends Notification
{
    use Queueable;

    protected $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Rent Reminder')
                    ->line("This is a reminder that your rent of \${$this->amount} is due.")
                    ->action('Pay Now', url('/payments'))
                    ->line('Thank you for your prompt payment!');
    }
}


?>