<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In - City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js', 'resources/js/jwt-auth.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />
    <style>
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
                <div class="forgot-password-header" style="margin-bottom:12px">
                    <a href="/" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back to Home
                    </a>
                </div>

                <div class="tab-panels">
                    <form id="login" class="tab-panel active">
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
                            <a class="link" href="/forgot-password">Forgot password?</a>
                        </div>
                        <div style="text-align:right;margin-top:8px">
                            <span>Don’t have an account? </span><a href="/register" class="link">Register here</a>
                        </div>
                        <button type="submit" class="cta">Log In</button>
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
</body>
</html>


