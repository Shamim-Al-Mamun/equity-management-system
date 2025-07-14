<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GreetingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We hope you are doing well.')
            ->line('This is your scheduled greeting from the Equity Management System.')
            ->salutation('Best regards, Equity Team');
    }
}








// <?php

// namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Notification;
// use Illuminate\Notifications\Messages\MailMessage;

// /**
//  * Sends a scheduled greeting email to users.
//  */
// class GreetingNotification extends Notification implements ShouldQueue
// {
//     use Queueable;

//     /**
//      * Determine how the notification will be delivered.
//      *
//      * @param  mixed  $notifiable
//      * @return array<int, string>
//      */
//     public function via($notifiable)
//     {
//         return ['mail'];
//     }

//     /**
//      * Build the mail message for the notification.
//      *
//      * @param  mixed  $notifiable
//      * @return \Illuminate\Notifications\Messages\MailMessage
//      */
//     public function toMail($notifiable)
//     {
//         return (new MailMessage)
//             ->subject('Greetings from Equity Management')
//             ->greeting('Hello ' . $notifiable->name . '!')
//             ->line('We hope you are doing well.')
//             ->line('This is your scheduled greeting from the Equity Management System.')
//             ->salutation('Best regards, Equity Team');
//     }
// }