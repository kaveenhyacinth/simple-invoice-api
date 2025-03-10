<?php

    namespace App\Listeners;

    use App\Events\UserCreated;
    use App\Mail\UserCreatedMail;
    use Mail;

    class SendUserCreatedEmail
    {
        /**
         * Create the event listener.
         */
        public function __construct()
        {
            //
        }

        /**
         * Handle the event.
         */
        public function handle(UserCreated $event): void
        {
            // Sending user created email
            Mail::to($event->user->email)->send(new UserCreatedMail($event->user));
        }
    }
