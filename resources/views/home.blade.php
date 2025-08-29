<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Government of Bacoor - Access</title>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js'])
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
                    <form id="login" class="tab-panel {{ $activeTab ?? 'login' === 'login' ? 'active' : '' }}" method="POST" action="{{ route('login') }}">
                        @csrf
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
                            <input type="password" id="login_password" name="password" placeholder="••••••••" required />
                        </div>
                        <div class="form-actions">
                            <label class="remember">
                                <input type="checkbox" name="remember" />
                                <span>Remember me</span>
                            </label>
                            <a class="link" href="#">Forgot password?</a>
                        </div>
                        <button type="submit" class="cta">Log In</button>
                    </form>

                    <form id="register" class="tab-panel {{ $activeTab ?? 'login' === 'register' ? 'active' : '' }}" method="POST" action="{{ route('register') }}">
                        @csrf
                        <h2 class="panel-title">Create your account</h2>
                        @if ($errors->any())
                        <div class="form-field" role="alert">
                            <div class="legal" style="color:#b42318">Please fix the errors below.</div>
                        </div>
                        @endif
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="reg_name">Full Name</label>
                                <input type="text" id="reg_name" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required />
                                @error('name')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_email">Email</label>
                                <input type="email" id="reg_email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                                @error('email')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_password">Password</label>
                                <input type="password" id="reg_password" name="password" placeholder="Create a strong password" required />
                                @error('password')<div class="legal" style="color:#b42318">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-field">
                                <label for="reg_password_confirmation">Confirm Password</label>
                                <input type="password" id="reg_password_confirmation" name="password_confirmation" placeholder="Re-enter password" required />
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
</body>
</html>