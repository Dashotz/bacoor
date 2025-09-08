@extends('layouts.app')

@section('title', 'Log In - City Government of Bacoor')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@push('scripts')
    <script src="{{ config('assets.external.recaptcha.script_url') }}" async defer></script>
@endpush

@section('content')

    <div class="login-container">
        <!-- Back to Home Link -->
        <div class="back-to-home">
            <a href="/" class="back-link">
                <span class="back-arrow">←</span>
                <span class="back-text">Back to home</span>
            </a>
        </div>

        <!-- Left Panel - Dark Blue -->
        <div class="login-left-panel">
            <div class="placeholder-content">
                <h2>INSERT IMAGES</h2>
                <p>(BRB)</p>
            </div>
        </div>

        <!-- Right Panel - White with Form -->
        <div class="login-right-panel">
            <div class="login-content">
                <!-- Header -->
                <div class="login-header">
                    <h3 class="welcome-text">WELCOME TO THE</h3>
                    <h1 class="city-name">CITY OF <span class="bacoor-highlight">BACOOR</span></h1>
                    
                    <!-- Bacoor Logo/Seal -->
                    <div class="bacoor-seal">
                        <img src="/images/bacoor-logo.png" alt="Bacoor Seal" class="seal-image" />
                    </div>
                </div>

                <!-- Login Form -->
                <div class="login-form-container">
                    <h2 class="signin-title">Sign in</h2>
                    
                    <form id="login">
                        
                        @if ($errors->has('email'))
                        <div class="form-field" role="alert">
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        </div>
                        @endif

                        <div class="form-field">
                            <label for="login_email">Email</label>
                            <input type="email" id="login_email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required />
                        </div>

                        <div class="form-field">
                            <label for="login_password">Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="login_password" name="password" placeholder="Enter your password" required />
                                <button type="button" class="password-toggle" data-target="login_password">
                                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-actions">
                            <label class="remember">
                                <input type="checkbox" id="remember_me" name="remember" />
                                <span>Remember me</span>
                            </label>
                            <a class="forgot-link" href="/forgot-password">Forgot Password?</a>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>

                        <button type="submit" class="continue-btn">CONTINUE</button>
                    </form>

                    <!-- Additional Links -->
                    <div class="additional-links">
                        <p class="signup-link">New User? <a href="/register" class="link-bold">SIGN UP HERE</a></p>
                        <a href="/application-status" class="status-link">Check My Application Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/home.js', 'resources/js/jwt-auth.js', 'resources/js/login.js'])
@endpush
