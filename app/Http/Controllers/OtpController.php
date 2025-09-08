<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class OtpController extends Controller
{
    public function show()
    {
        // Don't require JWT authentication here - the frontend will send it
        // when making OTP verification requests
        return view('otp');
    }

    public function generate(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Generate OTP for the user
        $this->sendOtp($user);

        return response()->json(['message' => 'OTP generated successfully']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 400);
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // OTP verification is now tracked in the database, no need for session
        
        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function resend()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Generate new OTP
        $this->sendOtp($user);

        return response()->json(['message' => 'New OTP code has been sent to your email.']);
    }

    public static function sendOtp(User $user)
    {
        // Delete any existing unused OTPs for this user
        Otp::where('user_id', $user->id)->where('used', false)->delete();

        // Generate new 6-digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create OTP record (expires in 10 minutes)
        Otp::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::to($user->email)->send(new OtpMail($user, $code));
            \Log::info("OTP email sent successfully to {$user->email}: {$code}");
        } catch (\Exception $e) {
            // Log the error but don't fail the OTP generation
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            
            // Fallback: still log the OTP for development
            \Log::info("OTP for user {$user->email}: {$code}");
        }

        return $code;
    }

    public static function sendRegistrationOtp(string $email, string $firstName)
    {
        // Generate new 6-digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create a temporary user object for the email
        $tempUser = new User();
        $tempUser->first_name = $firstName;
        $tempUser->email = $email;
        
        // Send OTP email
        try {
            \Log::info('Attempting to send OTP email', [
                'email' => $email,
                'first_name' => $firstName,
                'otp_code' => $code
            ]);
            
            Mail::to($email)->send(new OtpMail($tempUser, $code));
            
            \Log::info('OTP email sent successfully', [
                'email' => $email,
                'otp_code' => $code
            ]);
            
            // Store OTP in session for registration verification
            session([
                'registration_otp' => $code,
                'registration_email' => $email,
                'registration_otp_expires' => Carbon::now()->addMinutes(10)->timestamp,
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send registration OTP email: ' . $e->getMessage(), [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Store OTP in session for fallback verification
            session([
                'registration_otp' => $code,
                'registration_email' => $email,
                'registration_otp_expires' => Carbon::now()->addMinutes(10)->timestamp,
            ]);
            
            return true;
        }
    }

    public function sendRegistrationOtpRequest(Request $request)
    {
        \Log::info('Registration OTP request received', [
            'email' => $request->email,
            'first_name' => $request->first_name
        ]);

        $request->validate([
            'email' => 'required|email',
            'first_name' => 'nullable|string|max:255',
        ]);

        $email = $request->email;
        $firstName = $request->first_name ?? 'User';

        // Check if email already exists
        if (User::where('email', $email)->exists()) {
            \Log::info('Email already exists', ['email' => $email]);
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered.'
            ], 400);
        }

        // Send OTP
        $success = self::sendRegistrationOtp($email, $firstName);

        if ($success) {
            \Log::info('Registration OTP sent successfully', ['email' => $email]);
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email.'
            ]);
        } else {
            \Log::error('Failed to send registration OTP', ['email' => $email]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }
}
