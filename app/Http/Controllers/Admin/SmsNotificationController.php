<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\TwilioController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsNotificationController extends Controller
{
    /**
     * Show SMS notification form
     */
    public function create()
    {
        // Get all active users with phone numbers for multi-select dropdown
        $users = User::where('activate', 1)
            ->whereNotNull('phone')
            ->select('id', 'name', 'phone', 'user_type')
            ->orderBy('name')
            ->get();

        return view('admin.sms.create', compact('users'));
    }

    /**
     * Send SMS notification
     */
    public function send(Request $request)
    {
        // Validate form inputs
        $this->validate($request, [
            'message' => 'required|min:10|max:1600',
            'send_type' => 'required|in:all,by_type,selected',
            'user_type' => 'required_if:send_type,by_type|in:1,2',
            'selected_users' => 'required_if:send_type,selected|array',
            'selected_users.*' => 'exists:users,id'
        ]);

        // Build query based on send_type
        $query = User::where('activate', 1)->whereNotNull('phone');

        switch ($request->send_type) {
            case 'all':
                // No additional filter - send to all active users with phone
                break;

            case 'by_type':
                // Filter by user_type (1 = user, 2 = wholesale)
                $query->where('user_type', $request->user_type);
                break;

            case 'selected':
                // Send only to selected users
                $query->whereIn('id', $request->selected_users);
                break;
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No users found matching your criteria')
                ->withInput();
        }

        try {
            // Send bulk SMS using TwilioController
            $result = TwilioController::sendBulkSMS($users, $request->message);

            // Build success message
            $message = "SMS sent to {$result['success']} users";
            if ($result['failed'] > 0) {
                $message .= " ({$result['failed']} failed)";
            }

            Log::info('Admin SMS Campaign Sent', [
                'send_type' => $request->send_type,
                'user_type_filter' => $request->send_type === 'by_type' ? $request->user_type : null,
                'total_users' => $users->count(),
                'success' => $result['success'],
                'failed' => $result['failed'],
                'admin_id' => auth()->guard('admin')->user()->id ?? null
            ]);

            return redirect()->back()
                ->with('message', $message)
                ->with('success_count', $result['success'])
                ->with('failed_count', $result['failed']);
        } catch (\Exception $e) {
            Log::error('Admin SMS Campaign Error', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->guard('admin')->user()->id ?? null,
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while sending SMS: ' . $e->getMessage())
                ->withInput();
        }
    }
}
