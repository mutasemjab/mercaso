<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

class TwilioController extends Controller
{
    /**
     * Send SMS to a single phone number
     *
     * @param string $phoneNumber - E.164 format (+965XXXXXXXX)
     * @param string $message - SMS body text
     * @param int|null $userId - For logging purposes
     * @return bool
     */
    public static function sendSMS($phoneNumber, $message, $userId = null)
    {
        if (!$phoneNumber) {
            Log::error("Twilio Error: No phone number provided", ['user_id' => $userId]);
            return false;
        }

        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');

            if (!$sid || !$token || !$from) {
                Log::error("Twilio Error: Missing credentials in config");
                return false;
            }

            $client = new Client($sid, $token);

            $message = $client->messages->create(
                $phoneNumber, // To number
                [
                    'from' => $from,
                    'body' => $message
                ]
            );

            Log::info("Twilio SMS Sent Successfully", [
                'user_id' => $userId,
                'phone' => $phoneNumber,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return true;
        } catch (RestException $e) {
            Log::error("Twilio REST Error", [
                'user_id' => $userId,
                'phone' => $phoneNumber,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("Twilio General Error", [
                'user_id' => $userId,
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Send order confirmation SMS
     * Implements phone number fallback logic
     *
     * @param \App\Models\Order $order
     * @return bool
     */
    public static function sendOrderConfirmation($order)
    {
        // Phone number fallback: phone_in_order → user->phone
        $phoneNumber = $order->phone_in_order ?? ($order->user->phone ?? null);

        if (!$phoneNumber) {
            Log::warning("Twilio: No phone number available for order", [
                'order_id' => $order->id,
                'user_id' => $order->user_id
            ]);
            return false;
        }


        // Build message in English only
        $messageBody = "Dear customer, your order #{$order->number} has been received successfully. Total: {$order->total_prices}. We will contact you soon. Thank you for choosing California Cash & Carry!";

        return self::sendSMS($phoneNumber, $messageBody, $order->user_id);
    }

    /**
     * Send bulk SMS to multiple users
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param string $message
     * @return array ['success' => int, 'failed' => int]
     */
    public static function sendBulkSMS($users, $message)
    {
        $success = 0;
        $failed = 0;

        foreach ($users as $user) {
            if (!$user->phone) {
                $failed++;
                Log::warning("Twilio Bulk SMS: User has no phone", ['user_id' => $user->id]);
                continue;
            }

            $phoneNumber = $user->phone;

            if (self::sendSMS($phoneNumber, $message, $user->id)) {
                $success++;
            } else {
                $failed++;
            }

            // Rate limiting: Sleep 1 second between messages
            sleep(1);
        }

        Log::info("Twilio Bulk SMS Completed", [
            'total' => $users->count(),
            'success' => $success,
            'failed' => $failed
        ]);

        return ['success' => $success, 'failed' => $failed];
    }
}
