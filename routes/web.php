<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ApplicationStatusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login.form');

Route::get('/register', function () {
    return view('register');
})->name('register.form');

// Authentication Routes
Route::post('/register', [AuthController::class, 'webRegister'])->name('register.submit');

// Password Reset Routes
Route::prefix('password')->group(function () {
    Route::get('/forgot', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// OTP Routes
Route::prefix('otp')->group(function () {
    Route::get('/', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/generate', [OtpController::class, 'generate'])->name('otp.generate');
    Route::post('/verify', [OtpController::class, 'verify'])->name('otp.verify');
    Route::get('/resend', [OtpController::class, 'resend'])->name('otp.resend');
    Route::post('/send-registration', [OtpController::class, 'sendRegistrationOtpRequest'])->name('otp.send.registration');
});

// Protected Routes
Route::middleware(['jwt.web', 'otp.verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Application Status Routes
Route::prefix('application-status')->group(function () {
    Route::get('/', [ApplicationStatusController::class, 'show'])->name('application-status.show');
    Route::post('/verify', [ApplicationStatusController::class, 'verify'])->name('application-status.verify');
});


