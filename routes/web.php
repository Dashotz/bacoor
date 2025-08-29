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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP routes
Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
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
})->middleware(['auth', 'otp.verified'])->name('dashboard');

// Session timeout check route
Route::post('/session/check', function () {
    if (Auth::check()) {
        return response()->json(['active' => true, 'last_activity' => Session::get('last_activity')]);
    }
    return response()->json(['active' => false], 401);
})->middleware('auth')->name('session.check');

// Force logout route for session timeout
Route::post('/session/logout', function () {
    Auth::logout();
    Session::flush();
    return response()->json(['message' => 'Logged out due to inactivity']);
})->name('session.logout');
