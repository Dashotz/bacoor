// TCT Application JavaScript

// Initialize session data from data attributes
document.addEventListener('DOMContentLoaded', function() {
    // Get session data from body data attributes
    const body = document.body;
    const jwtToken = body.getAttribute('data-jwt-token');
    const jwtUser = body.getAttribute('data-jwt-user');
    
    // Set up session data
    window.sessionData = {
        jwt_token: jwtToken || null,
        jwt_user: jwtUser ? JSON.parse(jwtUser) : null
    };
    // Password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
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
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('password-confirmation-error');

    function validatePassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Clear previous errors
        passwordError.style.display = 'none';
        confirmPasswordError.style.display = 'none';
        passwordInput.classList.remove('error');
        confirmPasswordInput.classList.remove('error');
        
        let hasError = false;
        
        // Validate password length
        if (password && password.length < 8) {
            passwordError.style.display = 'block';
            passwordInput.classList.add('error');
            hasError = true;
        }
        
        // Validate password confirmation
        if (confirmPassword && password !== confirmPassword) {
            confirmPasswordError.textContent = 'Passwords do not match';
            confirmPasswordError.style.display = 'block';
            confirmPasswordInput.classList.add('error');
            hasError = true;
        }
        
        return !hasError;
    }

    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validatePassword);

    // Photo upload functionality
    const photoUploadArea = document.getElementById('photo-upload-area');
    const photoInput = document.getElementById('application_photo');

    photoUploadArea.addEventListener('click', function() {
        photoInput.click();
    });

    photoUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    photoUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    photoUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0]);
        }
    });

    photoInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileUpload(e.target.files[0]);
        }
    });

    function handleFileUpload(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }
        
        // Update upload area with file info
        const uploadText = photoUploadArea.querySelector('.upload-text');
        uploadText.textContent = `Selected: ${file.name}`;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove existing preview
            const existingPreview = photoUploadArea.querySelector('.photo-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Create new preview
            const preview = document.createElement('img');
            preview.className = 'photo-preview';
            preview.src = e.target.result;
            preview.style.maxWidth = '100px';
            preview.style.maxHeight = '100px';
            preview.style.objectFit = 'cover';
            preview.style.borderRadius = '8px';
            preview.style.marginTop = '1rem';
            
            photoUploadArea.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }

    // Form validation
    const form = document.getElementById('tct-form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        // Validate password
        if (!validatePassword()) {
            isValid = false;
        }
        
        // Validate email format
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value && !emailRegex.test(emailInput.value)) {
            emailInput.classList.add('error');
            isValid = false;
        }
        
        // Validate phone number
        const phoneInput = document.getElementById('contact_number');
        const phoneRegex = /^09\d{9}$/;
        if (phoneInput.value && !phoneRegex.test(phoneInput.value.replace(/\s/g, ''))) {
            phoneInput.classList.add('error');
            isValid = false;
        }
        
        if (isValid) {
            // Show success message or proceed to next step
            alert('Form submitted successfully!');
            // Uncomment the line below to actually submit the form
            // form.submit();
        } else {
            alert('Please fill in all required fields correctly.');
        }
    });

    // Real-time validation for email
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });

    // Real-time validation for phone number
    const phoneInput = document.getElementById('contact_number');
    phoneInput.addEventListener('input', function() {
        // Format phone number as user types
        let value = this.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        if (value.length > 0) {
            value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
        }
        
        this.value = value;
    });

    phoneInput.addEventListener('blur', function() {
        const phoneRegex = /^09\d{9}$/;
        const cleanValue = this.value.replace(/\s/g, '');
        if (this.value && !phoneRegex.test(cleanValue)) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });

    // Clear error styling on input
    const allInputs = form.querySelectorAll('input, select');
    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
        });
    });
});
