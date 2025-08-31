<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - City Government of Bacoor</title>
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
                <div class="reset-password-header">
                    <a href="/forgot-password" class="back-link">
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
                    <p>Remember your password? <a href="/" class="link">Back to Login</a></p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearEl = document.getElementById('year');
            if (yearEl) yearEl.textContent = String(new Date().getFullYear());

            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const eyeIcon = this.querySelector('.eye-icon');
                    const eyeSlashIcon = this.querySelector('.eye-slash-icon');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeIcon.style.display = 'none';
                        eyeSlashIcon.style.display = 'block';
                    } else {
                        input.type = 'password';
                        eyeIcon.style.display = 'block';
                        eyeSlashIcon.style.display = 'none';
                    }
                });
            });

            // Password validation
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const requirements = document.querySelectorAll('.requirement');
            const passwordMatch = document.getElementById('password-match');
            const passwordNoMatch = document.getElementById('password-no-match');

            function checkPasswordRequirements(password) {
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[@$!%*?&]/.test(password)
                };

                // Update requirement indicators
                requirements.forEach(req => {
                    const reqType = req.getAttribute('data-requirement');
                    if (checks[reqType]) {
                        req.classList.add('met');
                    } else {
                        req.classList.remove('met');
                    }
                });

                // Return true if all requirements are met
                return Object.values(checks).every(check => check);
            }

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword && password === confirmPassword) {
                    if (passwordMatch) passwordMatch.style.display = 'flex';
                    if (passwordNoMatch) passwordNoMatch.style.display = 'none';
                } else if (confirmPassword) {
                    if (passwordMatch) passwordMatch.style.display = 'none';
                    if (passwordNoMatch) passwordNoMatch.style.display = 'flex';
                } else {
                    if (passwordMatch) passwordMatch.style.display = 'none';
                    if (passwordNoMatch) passwordNoMatch.style.display = 'none';
                }
            }

            // Event listeners
            passwordInput.addEventListener('input', function() {
                checkPasswordRequirements(this.value);
                checkPasswordMatch();
            });

            confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Form submission
            const form = document.getElementById('reset-password-form');
            const submitBtn = form.querySelector('.cta');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                // Check if password meets all requirements
                const allRequirementsMet = checkPasswordRequirements(password);
                if (!allRequirementsMet) {
                    showError('Please ensure your password meets all requirements.');
                    return;
                }
                
                // Check if passwords match
                if (password !== confirmPassword) {
                    showError('Passwords do not match.');
                    return;
                }

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Resetting...';

                try {
                    const response = await fetch('/reset-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            token: document.getElementById('token').value,
                            email: document.getElementById('email').value,
                            password: password,
                            password_confirmation: confirmPassword
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showSuccess(data.message || 'Password reset successfully!');
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 2000);
                    } else {
                        showError(data.message || 'Failed to reset password. Please try again.');
                    }
                } catch (error) {
                    showError('An error occurred. Please try again.');
                } finally {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Reset Password';
                }
            });

            function showError(message) {
                const existingError = document.querySelector('.legal[style*="color:#b42318"]');
                if (existingError) {
                    existingError.remove();
                }

                const errorDiv = document.createElement('div');
                errorDiv.className = 'legal';
                errorDiv.style.cssText = 'color:#b42318; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca; margin-bottom: 16px;';
                errorDiv.textContent = message;
                
                form.insertBefore(errorDiv, form.firstChild);
            }

            function showSuccess(message) {
                const existingSuccess = document.querySelector('.legal[style*="color:#059669"]');
                if (existingSuccess) {
                    existingSuccess.remove();
                }

                const successDiv = document.createElement('div');
                successDiv.className = 'legal';
                successDiv.style.cssText = 'color:#059669; background: #d1fae5; padding: 8px 12px; border-radius: 6px; border: 1px solid #a7f3d0; margin-bottom: 16px;';
                successDiv.textContent = message;
                
                form.insertBefore(successDiv, form.firstChild);
            }
        });
    </script>
</body>
</html>
