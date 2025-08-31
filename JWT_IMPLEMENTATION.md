# JWT Authentication Implementation

This document describes the JWT (JSON Web Token) authentication system implemented in the Bacoor Laravel application, replacing the previous CSRF-based authentication.

## Overview

The application now uses JWT tokens for secure, stateless authentication instead of traditional session-based authentication with CSRF tokens. This provides better security, scalability, and API support.

## What Changed

### 1. **Authentication Method**
- **Before**: Session-based authentication with CSRF tokens
- **After**: JWT-based authentication with Bearer tokens

### 2. **Security Improvements**
- ✅ No more CSRF tokens visible in view source
- ✅ Stateless authentication (no server-side session storage)
- ✅ Better API support
- ✅ Cross-domain authentication support
- ✅ Improved scalability

### 3. **Files Modified**
- `app/Models/User.php` - Added JWT interface implementation
- `app/Http/Controllers/AuthController.php` - Complete rewrite for JWT
- `config/auth.php` - Added JWT guard configuration
- `config/queue.php` - Updated to use JWT authentication
- `routes/web.php` - Updated routes to use JWT middleware
- `resources/views/home.blade.php` - Removed CSRF tokens
- `resources/views/dashboard.blade.php` - Removed CSRF tokens
- `resources/js/jwt-auth.js` - New JWT authentication handler
- `app/Providers/AppServiceProvider.php` - Added JWT configuration

## How It Works

### 1. **User Registration**
```
POST /api/register
Content-Type: application/json

{
    "first_name": "Juan",
    "middle_name": "Dela",
    "surname": "Cruz",
    "suffix": "Jr",
    "email": "juan@example.com",
    "password": "SecurePass123!",
    "password_confirmation": "SecurePass123!"
}
```

**Response:**
```json
{
    "message": "User successfully registered",
    "user": { ... },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

### 2. **User Login**
```
POST /api/login
Content-Type: application/json

{
    "email": "juan@example.com",
    "password": "SecurePass123!"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": { ... }
}
```

### 3. **Authenticated Requests**
```
GET /api/user/profile
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

### 4. **Token Refresh**
```
POST /api/refresh
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

### 5. **Logout**
```
POST /api/logout
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

## Frontend Integration

### 1. **JavaScript Usage**
```javascript
// Check if user is authenticated
if (window.jwtAuth.isAuthenticated()) {
    console.log('User is logged in');
}

// Get user profile
const profile = await window.jwtAuth.getUserProfile();

// Manual logout
window.jwtAuth.handleLogout();
```

### 2. **Token Storage**
- JWT tokens are stored in `localStorage`
- User data is cached locally
- Automatic token refresh on expiration

### 3. **Form Handling**
- Forms no longer need CSRF tokens
- All authentication is handled via JavaScript
- Automatic redirect after successful login/registration

## Security Features

### 1. **Token Security**
- Tokens are signed with a secret key
- Configurable expiration times
- Automatic token refresh
- Secure token storage

### 2. **Rate Limiting**
- Login attempts are rate-limited (5 per minute per email/IP)
- Prevents brute force attacks

### 3. **Validation**
- Server-side validation for all inputs
- Password strength requirements
- Email format validation

## Configuration

### 1. **JWT Configuration** (`config/jwt.php`)
```php
'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token lifetime
'secret' => env('JWT_SECRET'), // JWT secret key
```

### 2. **Auth Configuration** (`config/auth.php`)
```php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

### 3. **Environment Variables**
```env
JWT_SECRET=your-secret-key-here
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

## API Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/register` | User registration | No |
| POST | `/api/login` | User login | No |
| POST | `/api/logout` | User logout | Yes |
| GET | `/api/me` | Get current user | Yes |
| POST | `/api/refresh` | Refresh token | Yes |
| GET | `/api/user/profile` | Get user profile | Yes |

## Error Handling

### 1. **Validation Errors**
```json
{
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### 2. **Authentication Errors**
```json
{
    "message": "Invalid credentials"
}
```

### 3. **Token Errors**
```json
{
    "message": "Token has expired"
}
```

## Testing

### 1. **Test Registration**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Test",
    "surname": "User",
    "email": "test@example.com",
    "password": "TestPass123!",
    "password_confirmation": "TestPass123!"
  }'
```

### 2. **Test Login**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "TestPass123!"
  }'
```

### 3. **Test Protected Route**
```bash
curl -X GET http://127.0.0.1:8000/api/user/profile \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## Troubleshooting

### 1. **Common Issues**
- **Token expired**: Use refresh endpoint or re-login
- **Invalid token**: Check token format and signature
- **CORS issues**: Ensure proper CORS configuration

### 2. **Debug Mode**
```php
// In config/jwt.php
'debug' => env('JWT_DEBUG', true),
```

### 3. **Logs**
Check Laravel logs for JWT-related errors:
```bash
tail -f storage/logs/laravel.log
```

## Migration from CSRF

### 1. **What Was Removed**
- `@csrf` directives in Blade templates
- CSRF token meta tags
- Session-based authentication middleware

### 2. **What Was Added**
- JWT authentication middleware
- Token-based API endpoints
- JavaScript authentication handler

### 3. **Benefits**
- Better security (no visible tokens)
- Improved performance (no session storage)
- API-first architecture
- Better mobile app support

## Future Enhancements

### 1. **Planned Features**
- Refresh token rotation
- Multi-factor authentication
- Social login integration
- API rate limiting per user

### 2. **Monitoring**
- Token usage analytics
- Failed authentication tracking
- User session monitoring

## Support

For issues or questions about the JWT implementation:
1. Check the Laravel logs
2. Verify JWT configuration
3. Test with Postman or similar tools
4. Review this documentation

---

**Note**: This implementation follows Laravel best practices and JWT security standards. Always keep your JWT secret key secure and never expose it in client-side code.
