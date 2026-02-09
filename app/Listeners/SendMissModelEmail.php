<?php

namespace App\Listeners;

use App\Events\MissModelEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvalidModelMail;

class SendMissModelEmail
{
    /**
     * Create the event listener.
     */
    public function __construct( MissModelEvent $event ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MissModelEvent $event): void {
        Mail::to('feketekard811@gmail.com')->send(new InvalidModelMail($event->logData));
    }
}
