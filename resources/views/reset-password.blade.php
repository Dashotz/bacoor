<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js', 'resources/js/password-reset.js'])
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
                <div class="reset-password-header">
                    <a href="/password/forgot" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back to Forgot Password
                    </a>
                    <h2 class="panel-title">Reset Password</h2>
                    <p class="reset-subtitle">Enter your new password below.</p>
                </div>

                @if (session('error'))
                <div class="form-field" role="alert">
                    <div class="legal" style="color:#b42318; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca;">
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                <form id="reset-password-form" class="reset-password-form">
                    <input type="hidden" id="token" name="token" value="{{ $token }}">
                    <input type="hidden" id="email" name="email" value="{{ $email }}">
                    
                    <div class="form-field">
                        <label for="password">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter new password" required />
                            <button type="button" class="password-toggle" data-target="password">
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
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required />
                            <button type="button" class="password-toggle" data-target="password_confirmation">
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

                    <button type="submit" class="cta">Reset Password</button>
                </form>

                <div class="reset-password-footer">
                    <p>Remember your password? <a href="/login" class="link">Back to Login</a></p>
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

</body>
</html>
