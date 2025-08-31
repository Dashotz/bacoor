<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('home', ['activeTab' => 'login']);
})->name('login.form');



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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['jwt.web', 'otp.verified'])->name('dashboard');


