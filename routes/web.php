<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\TransferApplyController;

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
    return view('pages.landing');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'webLogin'])->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
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
        return view('pages.dashboard');
    })->name('dashboard');
});

// Transfer of Ownership Routes
Route::middleware(['jwt.web'])->prefix('transfer-of-ownership')->group(function () {
    Route::get('/', [ApplicationStatusController::class, 'show'])->name('transfer-of-ownership.show');
    Route::post('/submit', [ApplicationStatusController::class, 'submitTransfer'])->name('transfer-of-ownership.submit');
});

// Application Status Routes (for backward compatibility)
Route::prefix('application-status')->group(function () {
    Route::get('/', [ApplicationStatusController::class, 'show'])->name('application-status.show');
    Route::post('/verify', [ApplicationStatusController::class, 'verify'])->name('application-status.verify');
});

// Transfer Apply Routes
Route::middleware(['jwt.web'])->prefix('transfer-apply')->group(function () {
    Route::get('/step1', [TransferApplyController::class, 'showStep1'])->name('transfer-apply.step1');
    Route::post('/step1', [TransferApplyController::class, 'submitStep1'])->name('transfer-apply.step1.submit');
    Route::get('/step2', [TransferApplyController::class, 'showStep2'])->name('transfer-apply.step2');
    Route::post('/step2', [TransferApplyController::class, 'submitStep2'])->name('transfer-apply.step2.submit');
    Route::get('/step3', [TransferApplyController::class, 'showStep3'])->name('transfer-apply.step3');
    Route::post('/step3', [TransferApplyController::class, 'submitStep3'])->name('transfer-apply.step3.submit');
    Route::get('/status', [TransferApplyController::class, 'getApplicationStatus'])->name('transfer-apply.status');
    Route::post('/cancel', [TransferApplyController::class, 'cancelApplication'])->name('transfer-apply.cancel');
});

// TCT Routes
Route::middleware(['jwt.web'])->prefix('tct')->group(function () {
    Route::get('/step1', function (Request $request) {
        // Get the authenticated user from JWT
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Please log in to access this page');
        }
        
        return view('pages.tct.step1', compact('user'));
    })->name('tct.step1');
    
    Route::post('/step1', function () {
        // Handle TCT step 1 submission
        return redirect()->route('tct.step1')->with('success', 'TCT application submitted successfully!');
    })->name('tct.step1.submit');
});


