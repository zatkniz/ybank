<?php

namespace App\Listeners;

use App\Events\NewTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSendersBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewTransaction  $event
     * @return void
     */
    public function handle(NewTransaction $event)
    {
        $event->sender->balance = $event->sender->balance - $event->amount;
        $event->sender->save();
    }
}
