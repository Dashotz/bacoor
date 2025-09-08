<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
});

// Protected API Routes (authentication required)
Route::prefix('v1')->middleware('auth:api')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.refresh');
    
    // User profile routes
    Route::prefix('user')->group(function () {
        Route::get('/profile', function () {
            $user = auth()->user();
            $email = $user->email;
            $emailParts = explode('@', $email);
            $maskedEmail = substr($emailParts[0], 0, 1) . '***@' . $emailParts[1];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'display_name' => $user->full_name,
                    'first_name' => $user->first_name,
                    'email_masked' => $maskedEmail,
                    'member_since' => $user->created_at->format('M Y')
                ]
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, private')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');
        })->name('api.user.profile');
        
        Route::get('/data', [AuthController::class, 'getUserData'])->name('api.user.data');
    });
});
