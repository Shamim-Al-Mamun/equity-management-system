<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\GreetingNotification;

class SendGreetingEmails extends Command
{
    protected $signature = 'email:greetings';
    protected $description = 'Send greeting email to all users';

    public function handle()
    {
        User::all()->each(function ($user) {
            $user->notify(new GreetingNotification());
        });

        $this->info('Greeting emails sent!');
    }
}
