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
}
