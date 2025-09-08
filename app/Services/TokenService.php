<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Generate a secure token for various purposes
     */
    public static function generateToken(int $length = 32): string
    {
        return Str::random($length);
    }
    
    /**
     * Generate a secure OTP token
     */
    public static function generateOtp(int $length = 6): string
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
    
    /**
     * Store token in cache with expiration
     */
    public static function storeToken(string $key, string $token, int $expirationMinutes = 5): void
    {
        Cache::put($key, $token, now()->addMinutes($expirationMinutes));
    }
    
    /**
     * Retrieve token from cache
     */
    public static function getToken(string $key): ?string
    {
        return Cache::get($key);
    }
    
    /**
     * Verify token and remove it from cache
     */
    public static function verifyAndConsumeToken(string $key, string $token): bool
    {
        $storedToken = Cache::get($key);
        
        if ($storedToken && hash_equals($storedToken, $token)) {
            Cache::forget($key);
            return true;
        }
        
        return false;
    }
    
    /**
     * Generate secure session token
     */
    public static function generateSessionToken(): string
    {
        return self::generateToken(64);
    }
    
    /**
     * Mask sensitive data for display
     */
    public static function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        
        $username = $parts[0];
        $domain = $parts[1];
        
        if (strlen($username) <= 1) {
            return $username . '@' . $domain;
        }
        
        return substr($username, 0, 1) . '***@' . $domain;
    }
    
    /**
     * Mask phone number for display
     */
    public static function maskPhone(string $phone): string
    {
        if (strlen($phone) <= 4) {
            return $phone;
        }
        
        return substr($phone, 0, 3) . '***' . substr($phone, -2);
    }
}
