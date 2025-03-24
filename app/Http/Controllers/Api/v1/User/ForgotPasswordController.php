<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Request password reset
     * User submits their email in the mobile app
     */
    public function requestReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Email not found in our records'
            ], 422);
        }

        // Generate a 6-digit reset code (more mobile-friendly than a long token)
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $email = $request->email;

        // Store code in database
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $resetCode,
            'created_at' => Carbon::now()
        ]);

        // Send email with the reset code
        try {
            Mail::send('emails.reset-code', ['resetCode' => $resetCode], function($message) use($email) {
                $message->to($email);
                $message->subject('Your Password Reset Code');
            });

            return response()->json([
                'status' => 1,
                'message' => 'Reset code has been sent to your email',
                'email' => $email // Return email to use in next step
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Could not send reset code. Please try again later.'
            ], 500);
        }
    }

    /**
     * Step 2: Verify the reset code
     * User enters the code they received via email
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->reset_code)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid reset code'
            ], 422);
        }

        // Check if code is expired (valid for 15 minutes)
        $codeCreatedAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($codeCreatedAt) > 15) {
            return response()->json([
                'status' => 0,
                'message' => 'Reset code has expired. Please request a new code.'
            ], 422);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Code verified successfully',
            'email' => $request->email
        ], 200);
    }

    /**
     * Step 3: Reset the password
     * User enters their new password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'reset_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Verify code again for security
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->reset_code)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid reset code'
            ], 422);
        }

        // Check expiration
        $codeCreatedAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($codeCreatedAt) > 15) {
            return response()->json([
                'status' => 0,
                'message' => 'Reset code has expired. Please request a new code.'
            ], 422);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used code
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Password has been reset successfully'
        ], 200);
    }
}
