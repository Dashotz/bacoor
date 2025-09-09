/**
 * Password Reset JavaScript Functions
 * Handles forgot password and reset password functionality
 */

class PasswordReset {
    constructor() {
        this.init();
    }

    init() {
        // Initialize forgot password form
        this.initForgotPasswordForm();
        // Initialize reset password form
        this.initResetPasswordForm();
        // Initialize password toggles
        this.initPasswordToggles();
        // Initialize password validation
        this.initPasswordValidation();
    }

    initForgotPasswordForm() {
        const form = document.getElementById('forgot-password-form');
        if (!form) return;

        const submitBtn = form.querySelector('.cta');
        if (!submitBtn) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            
            if (!email) {
                this.showError('Please enter your email address.');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                this.showError('Please enter a valid email address.');
                return;
            }

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            try {
                const response = await fetch('/password/forgot', {
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
                    this.showSuccess(data.message || 'Password reset link sent successfully!');
                    form.reset();
                } else {
                    this.showError(data.message || 'Failed to send reset link. Please try again.');
                }
            } catch (error) {
                this.showError('An error occurred. Please try again.');
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Reset Link';
            }
        });
    }

    initResetPasswordForm() {
        const form = document.getElementById('reset-password-form');
        if (!form) return;

        const submitBtn = form.querySelector('.cta');
        if (!submitBtn) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            // Check if password meets all requirements
            const allRequirementsMet = this.checkPasswordRequirements(password);
            if (!allRequirementsMet) {
                this.showError('Please ensure your password meets all requirements.');
                return;
            }
            
            // Check if passwords match
            if (password !== confirmPassword) {
                this.showError('Passwords do not match.');
                return;
            }

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Resetting...';

            try {
                const response = await fetch('/password/reset', {
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
                    this.showSuccess(data.message || 'Password reset successfully!');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    this.showError(data.message || 'Failed to reset password. Please try again.');
                }
            } catch (error) {
                this.showError('An error occurred. Please try again.');
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }
        });
    }

    initPasswordToggles() {
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
    }

    initPasswordValidation() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const requirements = document.querySelectorAll('.requirement');
        const passwordMatch = document.getElementById('password-match');
        const passwordNoMatch = document.getElementById('password-no-match');

        if (passwordInput) {
            passwordInput.addEventListener('input', (e) => {
                this.checkPasswordRequirements(e.target.value);
                this.checkPasswordMatch();
            });
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', () => {
                this.checkPasswordMatch();
            });
        }
    }

    checkPasswordRequirements(password) {
        const requirements = document.querySelectorAll('.requirement');
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

    checkPasswordMatch() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('password-match');
        const passwordNoMatch = document.getElementById('password-no-match');
        
        if (!passwordInput || !confirmPasswordInput) return;
        
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

    showError(message) {
        // Remove all existing messages
        const existingMessages = document.querySelectorAll('.legal[style*="color:#b42318"], .legal[style*="color:#059669"]');
        existingMessages.forEach(msg => msg.remove());

        const form = document.getElementById('forgot-password-form') || document.getElementById('reset-password-form');
        if (!form) return;

        const errorDiv = document.createElement('div');
        errorDiv.className = 'legal notification-message';
        errorDiv.style.cssText = 'color:#b42318; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border: 1px solid #fecaca; margin-bottom: 16px; opacity: 1; transition: opacity 0.3s ease;';
        errorDiv.textContent = message;
        
        form.insertBefore(errorDiv, form.firstChild);
        
        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.style.opacity = '0';
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 300);
            }
        }, 6000);
    }

    showSuccess(message) {
        // Remove all existing messages
        const existingMessages = document.querySelectorAll('.legal[style*="color:#b42318"], .legal[style*="color:#059669"]');
        existingMessages.forEach(msg => msg.remove());

        const form = document.getElementById('forgot-password-form') || document.getElementById('reset-password-form');
        if (!form) return;

        const successDiv = document.createElement('div');
        successDiv.className = 'legal notification-message';
        successDiv.style.cssText = 'color:#059669; background: #d1fae5; padding: 8px 12px; border-radius: 6px; border: 1px solid #a7f3d0; margin-bottom: 16px; opacity: 1; transition: opacity 0.3s ease;';
        successDiv.textContent = message;
        
        form.insertBefore(successDiv, form.firstChild);
        
        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.style.opacity = '0';
                setTimeout(() => {
                    if (successDiv.parentNode) {
                        successDiv.remove();
                    }
                }, 300);
            }
        }, 6000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set current year
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());
    
    // Initialize password reset functionality
    new PasswordReset();
});
