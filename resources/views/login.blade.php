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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f2f6fb;color:#0a2540}
    </style>
</head>
<body>
    <div class="login-container">
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
                    
                    @if (session('status'))
                    <div class="success-message" style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; border: 1px solid #a7f3d0; margin-bottom: 20px; text-align: center;">
                        {{ session('status') }}
                    </div>
                    @endif
                    
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
                        <a href="#" class="status-link">Check My Application Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up password toggle');
            const passwordToggles = document.querySelectorAll('.password-toggle');
            console.log('Found password toggles:', passwordToggles.length);
            
            passwordToggles.forEach((toggle, index) => {
                console.log('Setting up toggle', index);
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Password toggle clicked');
                    
                    const targetId = this.getAttribute('data-target');
                    console.log('Target ID:', targetId);
                    
                    const passwordInput = document.getElementById(targetId);
                    console.log('Password input found:', !!passwordInput);
                    
                    const eyeIcon = this.querySelector('.eye-icon');
                    const eyeSlashIcon = this.querySelector('.eye-slash-icon');
                    console.log('Icons found - eye:', !!eyeIcon, 'eye-slash:', !!eyeSlashIcon);
                    
                    if (passwordInput && eyeIcon && eyeSlashIcon) {
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.style.display = 'none';
                            eyeSlashIcon.style.display = 'block';
                            console.log('Password shown');
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.style.display = 'block';
                            eyeSlashIcon.style.display = 'none';
                            console.log('Password hidden');
                        }
                    } else {
                        console.error('Missing elements for password toggle');
                    }
                });
            });
        });
    </script>
</body>
</html>


