document.addEventListener('DOMContentLoaded', () => {
    const buttons = Array.from(document.querySelectorAll('.tab-button'));
    const panels = Array.from(document.querySelectorAll('.tab-panel'));
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    function activate(targetSelector){
        buttons.forEach(b=>b.classList.toggle('active', b.getAttribute('data-target') === targetSelector));
        panels.forEach(p=>p.classList.toggle('active', `#${p.id}` === targetSelector));
    }

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-target') || '#login';
            activate(target);
        });
    });

    // Check for activeTab parameter from server-side
    const activeTab = document.querySelector('.tab-button.active')?.getAttribute('data-target') || '#login';
    activate(activeTab);

    // If URL contains ?tab=register or #register, activate register
    const url = new URL(window.location.href);
    const tabParam = url.searchParams.get('tab');
    const hash = window.location.hash;
    if (tabParam === 'register' || hash === '#register') {
        activate('#register');
    } else if (tabParam === 'login' || hash === '#login') {
        activate('#login');
    }

    // Remember Me functionality
    const rememberMeCheckbox = document.getElementById('remember_me');
    const loginEmailInput = document.getElementById('login_email');
    const loginPasswordInput = document.getElementById('login_password');

    // Load saved credentials if they exist
    function loadSavedCredentials() {
        const savedEmail = localStorage.getItem('remembered_email');
        const savedPassword = localStorage.getItem('remembered_password');
        const isRemembered = localStorage.getItem('remember_me') === 'true';
        
        if (savedEmail && savedPassword && isRemembered) {
            loginEmailInput.value = savedEmail;
            loginPasswordInput.value = savedPassword;
            rememberMeCheckbox.checked = true;
        }
    }

    // Save credentials when form is submitted
    function saveCredentials() {
        if (rememberMeCheckbox.checked) {
            localStorage.setItem('remembered_email', loginEmailInput.value);
            localStorage.setItem('remembered_password', loginPasswordInput.value);
            localStorage.setItem('remember_me', 'true');
        } else {
            // Clear saved credentials if unchecked
            localStorage.removeItem('remembered_email');
            localStorage.removeItem('remembered_password');
            localStorage.removeItem('remember_me');
        }
    }

    // Handle remember me checkbox change
    if (rememberMeCheckbox) {
        rememberMeCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                // Clear saved credentials when unchecked
                localStorage.removeItem('remembered_email');
                localStorage.removeItem('remembered_password');
                localStorage.setItem('remember_me', 'false');
                
                // Clear the input fields when unchecked
                loginEmailInput.value = '';
                loginPasswordInput.value = '';
            }
        });
    }

    // Load saved credentials on page load
    loadSavedCredentials();

    // Password functionality
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
    const passwordInput = document.getElementById('reg_password');
    const confirmPasswordInput = document.getElementById('reg_password_confirmation');
    const requirements = document.querySelectorAll('.requirement');
    const passwordMatch = document.getElementById('password-match');
    const passwordNoMatch = document.getElementById('password-no-match');

    if (passwordInput && confirmPasswordInput && requirements.length > 0) {
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

        // OTP functionality
        const sendOtpBtn = document.getElementById('send-otp-btn');
        const emailInput = document.getElementById('reg_email');
        const firstNameInput = document.getElementById('reg_first_name');
        
        if (sendOtpBtn && emailInput && firstNameInput) {
            sendOtpBtn.addEventListener('click', function() {
                const email = emailInput.value.trim();
                const firstName = firstNameInput.value.trim();
                
                if (!firstName) {
                    alert('Please enter your first name first.');
                    return;
                }
                
                if (!email) {
                    alert('Please enter your email address first.');
                    return;
                }
                
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }
                
                // Disable button and show loading state
                this.disabled = true;
                this.textContent = 'Sending...';
                
                // Send OTP request to server
                fetch('/otp/send-registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: email,
                        first_name: firstName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.textContent = 'OTP Sent!';
                        this.style.background = '#059669';
                        
                        // Re-enable after 60 seconds
                        setTimeout(() => {
                            this.disabled = false;
                            this.textContent = 'Send OTP';
                            this.style.background = '';
                        }, 60000);
                    } else {
                        alert(data.message || 'Failed to send OTP. Please try again.');
                        this.disabled = false;
                        this.textContent = 'Send OTP';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send OTP. Please try again.');
                    this.disabled = false;
                    this.textContent = 'Send OTP';
                });
            });
        }

        // Form validation
        const registerForm = document.getElementById('register');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const otp = document.getElementById('reg_otp')?.value;
                
                // Check if password meets all requirements
                const allRequirementsMet = checkPasswordRequirements(password);
                if (!allRequirementsMet) {
                    e.preventDefault();
                    alert('Please ensure your password meets all requirements.');
                    return false;
                }
                
                // Check if passwords match
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return false;
                }
                
                // Check if OTP is entered
                if (!otp || otp.trim() === '') {
                    e.preventDefault();
                    alert('Please enter the verification code.');
                    return false;
                }
            });
        }
    }
});

// Global functions for success popup
function showSuccessPopup() {
    const popup = document.getElementById('success-popup');
    const overlay = document.getElementById('success-popup-overlay');
    
    if (popup && overlay) {
        overlay.style.display = 'block';
        popup.style.display = 'block';
        
        // Add animation classes
        setTimeout(() => {
            popup.classList.add('popup-enter-active');
        }, 10);
    }
}

// Test function - can be called from browser console for testing
function testSuccessPopup() {
    showSuccessPopup();
}

function closeSuccessPopup() {
    const popup = document.getElementById('success-popup');
    const overlay = document.getElementById('success-popup-overlay');
    
    if (popup && overlay) {
        popup.classList.add('popup-exit-active');
        
        setTimeout(() => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
            popup.classList.remove('popup-exit-active');
        }, 300);
    }
}

// Check for success message on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message in the session
    const successMessage = document.querySelector('.legal[style*="color:#059669"]');
    if (successMessage && successMessage.textContent.includes('Registration successful')) {
        // Show popup after a short delay
        setTimeout(() => {
            showSuccessPopup();
        }, 500);
    }
    
    // Add click event to overlay to close popup
    const overlay = document.getElementById('success-popup-overlay');
    if (overlay) {
        overlay.addEventListener('click', closeSuccessPopup);
    }
    
    // Add escape key to close popup
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSuccessPopup();
        }
    });
    
    // Add click event to close button
    const closeButton = document.getElementById('close-popup-btn');
    if (closeButton) {
        closeButton.addEventListener('click', closeSuccessPopup);
    }
});


