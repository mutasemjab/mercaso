<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Http\Controllers\Admin\TwilioController;
use Illuminate\Support\Facades\Log;

class SendOrderSMS
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
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // Load user relationship if not eager loaded
        if (!$order->relationLoaded('user')) {
            $order->load('user');
        }

        TwilioController::sendOrderConfirmation($order);
    }
}
