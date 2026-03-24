<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Http\Controllers\Admin\TwilioController;
use App\Models\Admin;
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

        // Send SMS to customer
        TwilioController::sendOrderConfirmation($order);

        // Send SMS to Admin (ID = 1) if they have a mobile number
        $admin = Admin::find(1);
        if ($admin && $admin->mobile) {
            $adminMessage = "New order created! Order #{$order->number} from {$order->user->name}. Total: {$order->total_prices}";
            TwilioController::sendSMS($admin->mobile, $adminMessage, null);
        }
    }
}
