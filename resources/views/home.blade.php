<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Government of Bacoor - Access</title>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js', 'resources/js/jwt-auth.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />

    <style>
        /* Fallback minimal styles if Vite hasn't built yet */
        body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f2f6fb;color:#0a2540}
    </style>
</head>
<body>
    <div class="bacoor-auth">
        <header class="bacoor-header">
            <div class="bacoor-brand">
                <img src="/favicon.ico" alt="Bacoor Seal" class="brand-logo" />
                <div class="brand-text">
                    <span class="brand-top">City Government of</span>
                    <span class="brand-name">Bacoor</span>
                </div>
            </div>
        </header>

        <main class="auth-container">
            <section class="auth-card">
                <div class="auth-tabs">
                    <button class="tab-button {{ $activeTab ?? 'login' === 'login' ? 'active' : '' }}" data-target="#login">Log In</button>
                    <button class="tab-button {{ $activeTab ?? 'login' === 'register' ? 'active' : '' }}" data-target="#register">Register</button>
                </div>

                <div class="tab-panels">
                    <form id="login" class="tab-panel {{ $activeTab ?? 'login' === 'login' ? 'active' : '' }}">
                        <h2 class="panel-title">Welcome back</h2>
                        @if ($errors->has('email'))
                        <div class="form-field" role="alert">
                            <div class="legal" style="color:#b42318">{{ $errors->first('email') }}</div>
                        </div>
                        @endif
                        <div class="form-field">
                            <label for="login_email">Email</label>
                            <input type="email" id="login_email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                        </div>
                        <div class="form-field">
                            <label for="login_password">Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="login_password" name="password" placeholder="••••••••" required />
                                <button type="button" class="password-toggle" data-target="login_password">
                                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.45 18.45 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
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
                            <a class="link" href="/forgot-password">Forgot password?</a>
                        </div>
                        <button type="submit" class="cta">Log In</button>
                    </form>

                    <form id="register" class="tab-panel {{ $activeTab ?? 'login' === 'register' ? 'active' : '' }}">
                        <h2 class="panel-title">Create your account</h2>
                        @if (session('status'))
                        <div class="form-field" role="alert">
                            <div class="legal" style="color:#059669; background: #d1fae5; padding: 8px 12px; border-radius: 6px; border: 1px solid #a7f3d0;">
                                {{ session('status') }}
                            </div>
                        </div>
                        @endif
                        
                        @if ($errors->any())
                        <div class="form-field" role="alert">
                            @if ($errors->has('general'))
                                <div class="legal" style="color:#b42318">{{ $errors->first('general') }}</div>
                            @else
                                <div class="legal" style="color:#b42318">Please fix the errors below.</div>
                            @endif
                        </div>
                        @endif
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="reg_first_name">First Name</label>
                                <input type="text" id="reg_first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required />
                                @error('first_name')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_middle_name">Middle Name</label>
                                <input type="text" id="reg_middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Leave blank if not applicable" />
                                @error('middle_name')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_surname">Surname</label>
                                <input type="text" id="reg_surname" name="surname" value="{{ old('surname') }}" placeholder="Dela Cruz" required />
                                @error('surname')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_suffix">Suffix</label>
                                <input type="text" id="reg_suffix" name="suffix" value="{{ old('suffix') }}" placeholder="Leave blank if not applicable" />
                                @error('suffix')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_email">Email</label>
                                <input type="email" id="reg_email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                                @error('email')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_otp">Verification Code</label>
                                <div class="otp-input-wrapper">
                                    <input type="text" id="reg_otp" name="otp" placeholder="Enter verification code" required />
                                    <button type="button" class="otp-button" id="send-otp-btn">Send OTP</button>
                                </div>
                                @error('otp')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_password">Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="reg_password" name="password" placeholder="Create a strong password" required />
                                    <button type="button" class="password-toggle" data-target="reg_password">
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
                                <div class="password-requirements">
                                    <div class="requirement" data-requirement="length">
                                        <span class="requirement-icon"></span>
                                        <span>At least 8 characters</span>
                                    </div>
                                    <div class="requirement" data-requirement="uppercase">
                                        <span class="requirement-icon"></span>
                                        <span>1 uppercase letter</span>
                                    </div>
                                    <div class="requirement" data-requirement="lowercase">
                                        <span class="requirement-icon"></span>
                                        <span>1 lowercase letter</span>
                                    </div>
                                    <div class="requirement" data-requirement="number">
                                        <span class="requirement-icon"></span>
                                        <span>1 number</span>
                                    </div>
                                    <div class="requirement" data-requirement="special">
                                        <span class="requirement-icon"></span>
                                        <span>1 special character</span>
                                    </div>
                                </div>

                                @error('password')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_password_confirmation">Confirm Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="reg_password_confirmation" name="password_confirmation" placeholder="Re-enter password" required />
                                    <button type="button" class="password-toggle" data-target="reg_password_confirmation">
                                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.45 18.45 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                            <line x1="1" y1="1" x2="23" y2="23"></line>
                                        </svg>
                                    </button>
                                </div>
                                <div class="password-match" id="password-match" style="display: none;">
                                    <span class="match-icon">✅</span>
                                    <span>Passwords match</span>
                                </div>
                                <div class="password-no-match" id="password-no-match" style="display: none;">
                                    <span class="no-match-icon">❌</span>
                                    <span>Passwords do not match</span>
                                </div>
                            </div>
                        </div>
                        <div class="legal">
                            By registering, you agree to the <a href="#" class="link">Terms</a> and <a href="#" class="link">Privacy Policy</a>.
                        </div>
                        <button type="submit" class="cta">Create Account</button>
                    </form>
                </div>
            </section>

            <aside class="auth-aside">
                <div class="hero">
                    <h1>Serbisyong Tapat, Serbisyong Maaasahan</h1>
                    <p>Access services and programs of the City Government of Bacoor through your citizen account.</p>
                </div>
            </aside>
        </main>

        <footer class="bacoor-footer">
            <p>© <span id="year"></span> City Government of Bacoor</p>
        </footer>
    </div>

    <!-- Success Registration Popup -->
    <div id="success-popup" class="success-popup" style="display: none;">
        <div class="success-popup-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3>Registration Successful! 🎉</h3>
        <p>Your account has been created successfully. You can now log in with your email and password.</p>
        <button class="success-popup-button" id="close-popup-btn">Close</button>
    </div>
    
    <div id="success-popup-overlay" class="success-popup-overlay" style="display: none;"></div>

</body>
</html>