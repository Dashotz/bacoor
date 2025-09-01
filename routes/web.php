<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ForgotPasswordController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', function () {
    return view('login');
})->name('login.form');

Route::get('/register', function () {
    return view('register');
})->name('register.form');

// Forgot Password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// OTP routes
Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/generate', [OtpController::class, 'generate'])->name('otp.generate');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::get('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
Route::post('/otp/send-registration', [OtpController::class, 'sendRegistrationOtpRequest'])->name('otp.send.registration');



// Test route for OTP functionality
Route::get('/test-otp', function() {
    $user = new App\Models\User();
    $user->first_name = 'Test';
    $user->email = 'test@example.com';
    
    try {
        $result = App\Http\Controllers\OtpController::sendRegistrationOtp('test@example.com', 'Test');
        return response()->json([
            'success' => $result,
            'message' => $result ? 'OTP sent successfully' : 'Failed to send OTP',
            'session_data' => session()->all()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test route for Forgot Password functionality
Route::get('/test-forgot-password', function() {
    try {
        $user = App\Models\User::first();
        if (!$user) {
            return response()->json(['error' => 'No users found in database']);
        }
        
        $controller = new App\Http\Controllers\ForgotPasswordController();
        $request = new Illuminate\Http\Request();
        $request->merge(['email' => $user->email]);
        
        $response = $controller->sendResetLink($request);
        
        return response()->json([
            'success' => true,
            'message' => 'Forgot password test completed',
            'user_email' => $user->email,
            'response' => $response->getData()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['jwt.web', 'otp.verified'])->name('dashboard');


