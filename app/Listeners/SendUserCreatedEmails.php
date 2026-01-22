<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\AdminNewUserMail;
use App\Mail\UserCreatedMail;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendUserCreatedEmails implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private UserRepository $users)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        // // Safety checks so not accidentally spam.
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) return;

        // Email new user.
        Mail::to($user->email)->send(new UserCreatedMail($user));

        // Email all admins from DB.
        $adminEmails = $this->users->adminEmails();
        // Safety checks so not accidentally spam.
        $adminEmails = array_filter($adminEmails, fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));

        if (!$adminEmails) return;
        if (empty($adminEmails)) return;
        
        Mail::to($adminEmails)->send(new AdminNewUserMail($user));
    }
}
