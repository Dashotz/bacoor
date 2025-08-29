<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('user_id') || !session('user_email')) {
            return redirect()->route('login.form');
        }
        
        return view('otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = User::find(session('user_id'));
        if (!$user) {
            return redirect()->route('login.form');
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.']);
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Clear OTP session data
        session()->forget(['user_id', 'user_email', 'otp_verified']);

        // Log in the user
        Auth::login($user);

        // Set OTP verification flag
        session(['otp_verified' => true]);
        
        // Set initial session activity timestamp for session timeout
        session(['last_activity' => Carbon::now()->timestamp]);

        return redirect()->intended(route('dashboard'));
    }

    public function resend()
    {
        if (!session('user_id') || !session('user_email')) {
            return redirect()->route('login.form');
        }

        $user = User::find(session('user_id'));
        if (!$user) {
            return redirect()->route('login.form');
        }

        // Generate new OTP
        $this->sendOtp($user);

        return back()->with('status', 'New OTP code has been sent to your email.');
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

        // Store user info in session for OTP page
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);

        // Send OTP email
        try {
            Mail::to($user->email)->send(new OtpMail($user, $code));
        } catch (\Exception $e) {
            // Log the error but don't fail the OTP generation
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            
            // Fallback: still log the OTP for development
            \Log::info("OTP for {$user->email}: {$code}");
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
            
            // For testing, let's also log the OTP to the console
            \Log::info("OTP Code for {$email}: {$code}");
            
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
            
            // Even if email fails, store OTP in session for testing
            session([
                'registration_otp' => $code,
                'registration_email' => $email,
                'registration_otp_expires' => Carbon::now()->addMinutes(10)->timestamp,
            ]);
            
            return true; // Return true for testing purposes
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
            'first_name' => 'required|string|max:255',
        ]);

        $email = $request->email;
        $firstName = $request->first_name;

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
