<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function show()
    {
        return view('forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We could not find a user with that email address.',
        ]);

        try {
            // Check if user exists
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'message' => 'We could not find a user with that email address.'
                ], 404);
            }

            // Generate reset token
            $token = Str::random(64);
            
            // Store reset token in database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]
            );

            // Send reset email
            $resetUrl = url("/password/reset?token={$token}&email=" . urlencode($request->email));
            
            Mail::send('emails.reset-password', [
                'user' => $user,
                'resetUrl' => $resetUrl
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Your Password - City Government of Bacoor');
            });

            return response()->json([
                'message' => 'Password reset link sent successfully! Please check your email.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send reset link. Please try again later.'
            ], 500);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/forgot-password')->with('error', 'Invalid reset link.');
        }

        // Check if token is valid and not expired
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->first();

        if (!$resetRecord) {
            return redirect('/forgot-password')->with('error', 'Reset link has expired or is invalid.');
        }

        return view('reset-password', compact('token', 'email'));
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Check if token is valid and not expired
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->where('created_at', '>', Carbon::now()->subHours(24))
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'message' => 'Reset link has expired or is invalid.'
                ], 400);
            }

            // Update user password
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'User not found.'
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete used reset token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            // Fire password reset event
            event(new PasswordReset($user));

            return response()->json([
                'message' => 'Password reset successfully! You can now login with your new password.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to reset password. Please try again.'
            ], 500);
        }
    }
}
