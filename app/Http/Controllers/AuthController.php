<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'suffix' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'otp' => ['required', 'string', 'size:6'],
                'password' => [
                    'required', 
                    'confirmed', 
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                ],
            ], [
                'first_name.required' => 'First name is required.',
                'surname.required' => 'Surname is required.',
                'otp.required' => 'Verification code is required.',
                'otp.size' => 'Verification code must be 6 digits.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.regex' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]);

            // Validate OTP
            $storedOtp = session('registration_otp');
            $storedEmail = session('registration_email');
            $otpExpires = session('registration_otp_expires');
            
            if (!$storedOtp || $storedEmail !== $validated['email'] || !$otpExpires || time() > $otpExpires) {
                return back()
                    ->withErrors(['otp' => 'Invalid or expired verification code. Please request a new one.'])
                    ->withInput($request->except('password', 'password_confirmation', 'otp'));
            }
            
            if ($storedOtp !== $validated['otp']) {
                return back()
                    ->withErrors(['otp' => 'Invalid verification code. Please check and try again.'])
                    ->withInput($request->except('password', 'password_confirmation', 'otp'));
            }
            
            // Clear OTP session data
            session()->forget(['registration_otp', 'registration_email', 'registration_otp_expires']);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'surname' => $validated['surname'],
                'suffix' => $validated['suffix'],
                'email' => $validated['email'],
                'password' => $validated['password'], // hashed via cast in User model
            ]);

            // Log the successful creation
            \Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name
            ]);

            return redirect('/')
                ->with('status', 'Registration successful! You may now log in.');
                
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->except('password', 'password_confirmation')
            ]);
            
            return back()
                ->withErrors(['general' => 'Registration failed. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation', 'otp'));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Send OTP instead of redirecting to dashboard
            $user = Auth::user();
            OtpController::sendOtp($user);
            
            // Logout temporarily until OTP is verified
            Auth::logout();
            
            return redirect()->route('otp.show');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
