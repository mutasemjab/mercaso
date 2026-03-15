<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\TwilioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OTPPasswordResetController extends Controller
{
    /**
     * Step 1: Send OTP to phone number
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Phone number not found in our records'
            ], 422);
        }

        $phone = $request->phone;

        //try {
            // Delete old OTP records for this phone
            DB::table('otp_codes')->where('phone', $phone)->delete();

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in database
            DB::table('otp_codes')->insert([
                'phone' => $phone,
                'code' => $otp,
                'verified' => false,
                'attempts' => 0,
                'expires_at' => Carbon::now()->addMinutes(15),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Send OTP via Twilio
            $message = "Your password reset code is: $otp. Valid for 15 minutes.";
            $sent = TwilioController::sendSMS($phone, $message);

            if (!$sent) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to send OTP. Please try again later.'
                ], 500);
            }

            return response()->json([
                'status' => 1,
                'message' => 'OTP has been sent to your phone',
                'phone' => $phone
            ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'An error occurred. Please try again later.'
        //     ], 500);
        // }
    }

    /**
     * Step 2: Resend OTP with rate limiting
     */
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Phone number not found in our records'
            ], 422);
        }

        $phone = $request->phone;

        try {
            $otpRecord = DB::table('otp_codes')
                ->where('phone', $phone)
                ->orderBy('created_at', 'desc')
                ->first();

            // Check if record exists and has not expired
            if (!$otpRecord || Carbon::parse($otpRecord->expires_at)->isPast()) {
                // Delete old record and create new one
                DB::table('otp_codes')->where('phone', $phone)->delete();

                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                DB::table('otp_codes')->insert([
                    'phone' => $phone,
                    'code' => $otp,
                    'verified' => false,
                    'attempts' => 0,
                    'expires_at' => Carbon::now()->addMinutes(15),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                // Check resend attempts limit (max 3 times per 15 minutes)
                if ($otpRecord->attempts >= 3) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Too many resend attempts. Please try again later.'
                    ], 429);
                }

                // Increment attempts and regenerate OTP
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                DB::table('otp_codes')
                    ->where('id', $otpRecord->id)
                    ->update([
                        'code' => $otp,
                        'attempts' => $otpRecord->attempts + 1,
                        'updated_at' => Carbon::now(),
                    ]);
            }

            // Send OTP via Twilio
            $message = "Your password reset code is: $otp. Valid for 15 minutes.";
            $sent = TwilioController::sendSMS($phone, $message);

            if (!$sent) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to send OTP. Please try again later.'
                ], 500);
            }

            return response()->json([
                'status' => 1,
                'message' => 'OTP has been resent to your phone',
                'phone' => $phone
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Step 3: Verify OTP and reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $otpRecord = DB::table('otp_codes')
                ->where('phone', $request->phone)
                ->orderBy('created_at', 'desc')
                ->first();

            // Verify OTP exists
            if (!$otpRecord) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No OTP found for this phone number'
                ], 422);
            }

            // Verify OTP code matches
            if ($otpRecord->code !== $request->otp) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid OTP code'
                ], 422);
            }

            // Check if OTP is expired
            if (Carbon::parse($otpRecord->expires_at)->isPast()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'OTP has expired. Please request a new one.'
                ], 422);
            }

            // Update user password
            $user = User::where('phone', $request->phone)->first();
            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found'
                ], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            // Mark OTP as verified and delete
            DB::table('otp_codes')->where('id', $otpRecord->id)->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Password has been reset successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
