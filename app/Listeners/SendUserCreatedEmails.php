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

        // Email new user.
        Mail::to($user->email)->send(new UserCreatedMail($user));

        // Email all admins from DB.
        $adminEmails = $this->users->adminEmails();
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new AdminNewUserMail($user));
        }
    }
}
