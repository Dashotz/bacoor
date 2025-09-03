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

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // User profile route
    Route::get('/user/profile', function () {
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
    });
    
    // Full user data route for dashboard table
    Route::get('/user-data', [AuthController::class, 'getUserData']);
});
