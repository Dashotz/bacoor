<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password - City Government of Bacoor</title>
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
                <div class="forgot-password-header">
                    <a href="/login" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/> 
                        </svg>
                        Back to Login
                    </a>
                    <h2 class="panel-title">Forgot Password</h2>
                    <p class="forgot-subtitle">Enter your email address and we'll send you a link to reset your password.</p>
                </div>

                @if (session('status'))
                <div class="form-field" role="alert">
                    <div class="legal" style="color:#059669; background: #d1fae5; padding: 8px 12px; border-radius: 6px; border: 1px solid #a7f3d0;">
                        {{ session('status') }}
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="form-field" role="alert">
                    <div class="legal" style="color:#b42318; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca;">
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif

                <form id="forgot-password-form" class="forgot-password-form">
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                    </div>
                    <button type="submit" class="cta">Send Reset Link</button>
                </form>                
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

            const form = document.getElementById('forgot-password-form');
            const submitBtn = form.querySelector('.cta');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value.trim();
                
                if (!email) {
                    showError('Please enter your email address.');
                    return;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showError('Please enter a valid email address.');
                    return;
                }

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                try {
                    const response = await fetch('/forgot-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ email: email })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showSuccess(data.message || 'Password reset link sent successfully!');
                        form.reset();
                    } else {
                        showError(data.message || 'Failed to send reset link. Please try again.');
                    }
                } catch (error) {
                    showError('An error occurred. Please try again.');
                } finally {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Reset Link';
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
