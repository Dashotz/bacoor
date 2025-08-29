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

                    // Form validation
            const registerForm = document.getElementById('register');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;
                    
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
                });
            }
    }
});


