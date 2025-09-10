// Transfer Apply Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is authenticated with JWT
    setTimeout(() => {
        console.log('Transfer Apply page loaded');
        console.log('Session Data:', window.sessionData);
        console.log('JWT Auth available:', !!window.jwtAuth);
        console.log('JWT Auth authenticated:', window.jwtAuth ? window.jwtAuth.isAuthenticated() : 'N/A');
        
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
            console.log('User not authenticated, redirecting to home');
            window.location.href = '/';
            return;
        }
        
        // Initialize form functionality
        initializeTransferApplyForm();
        initializePasswordToggle();
        initializePhotoUpload();
        initializeFormValidation();
    }, 1000); // Wait 1 second for JWT auth to initialize
});

// Initialize the transfer apply form
function initializeTransferApplyForm() {
    const form = document.getElementById('transfer-apply-form');
    if (!form) return;

    // Add form submission handler
    form.addEventListener('submit', handleFormSubmission);
    
    // Add real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => clearFieldError(input));
    });
}

// Handle form submission
function handleFormSubmission(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    // Validate form before submission
    if (!validateForm(form)) {
        return;
    }
    
    // Show loading state
    setButtonLoading(true);
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': `Bearer ${window.sessionData?.jwt_token || ''}`
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to next step
            window.location.href = data.redirect_url || '/transfer-apply/step2';
        } else {
            // Show errors
            showFormErrors(data.errors || {});
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        setButtonLoading(false);
    });
}

// Validate entire form
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate password confirmation
    const password = form.querySelector('#password');
    const passwordConfirmation = form.querySelector('#password_confirmation');
    
    if (password && passwordConfirmation) {
        if (password.value !== passwordConfirmation.value) {
            showFieldError(passwordConfirmation, 'Passwords do not match');
            isValid = false;
        }
    }
    
    // Validate photo upload
    const photoInput = form.querySelector('#application_photo');
    if (photoInput && !photoInput.files.length) {
        showFieldError(photoInput, 'Application photo is required');
        isValid = false;
    }
    
    return isValid;
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    
    // Clear previous errors
    clearFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, `${getFieldLabel(field)} is required`);
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Phone number validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^09[0-9]{9}$/;
        if (!phoneRegex.test(value.replace(/\s/g, ''))) {
            showFieldError(field, 'Please enter a valid 11-digit mobile number starting with 09');
            return false;
        }
    }
    
    // Password validation
    if (field.type === 'password' && value) {
        if (value.length < 8) {
            showFieldError(field, 'Password must be at least 8 characters long');
            return false;
        }
    }
    
    // Date validation
    if (field.type === 'date' && value) {
        const selectedDate = new Date(value);
        const today = new Date();
        const minAge = new Date();
        minAge.setFullYear(today.getFullYear() - 100);
        
        if (selectedDate > today) {
            showFieldError(field, 'Birth date cannot be in the future');
            return false;
        }
        
        if (selectedDate < minAge) {
            showFieldError(field, 'Please enter a valid birth date');
            return false;
        }
    }
    
    return true;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('error');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('error');
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

// Get field label
function getFieldLabel(field) {
    const label = field.parentNode.querySelector('.field-label');
    return label ? label.textContent.replace('*', '').trim() : field.name;
}

// Show form errors
function showFormErrors(errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            showFieldError(field, errors[fieldName][0]);
        }
    });
}

// Initialize password toggle functionality
function initializePasswordToggle() {
    const toggleButtons = document.querySelectorAll('.password-toggle');
    
    toggleButtons.forEach(button => {
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

// Initialize photo upload functionality
function initializePhotoUpload() {
    const uploadArea = document.getElementById('photo-upload-area');
    const fileInput = document.getElementById('application_photo');
    
    if (!uploadArea || !fileInput) return;
    
    // Click to upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    // File selection
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
}

// Handle file selection
function handleFileSelect(file) {
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showNotification('Please select an image file', 'error');
        return;
    }
    
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        showNotification('File size must be less than 5MB', 'error');
        return;
    }
    
    // Show preview
    const reader = new FileReader();
    reader.onload = (e) => {
        showPhotoPreview(e.target.result);
    };
    reader.readAsDataURL(file);
}

// Show photo preview
function showPhotoPreview(imageSrc) {
    const uploadArea = document.getElementById('photo-upload-area');
    const uploadContent = uploadArea.querySelector('.upload-content');
    
    // Remove existing preview
    const existingPreview = uploadArea.querySelector('.photo-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Create preview
    const preview = document.createElement('img');
    preview.className = 'photo-preview';
    preview.src = imageSrc;
    preview.alt = 'Photo preview';
    
    // Update upload area
    uploadArea.classList.add('has-photo');
    uploadArea.appendChild(preview);
}

// Initialize form validation
function initializeFormValidation() {
    // Real-time validation for specific fields
    const contactNumber = document.getElementById('contact_number');
    if (contactNumber) {
        contactNumber.addEventListener('input', function() {
            // Format phone number as user types
            let value = this.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Format as 09XX XXX XXXX
            if (value.length > 4) {
                value = value.substring(0, 4) + ' ' + value.substring(4);
            }
            if (value.length > 8) {
                value = value.substring(0, 8) + ' ' + value.substring(8);
            }
            
            this.value = value;
        });
    }
}

// Set button loading state
function setButtonLoading(loading) {
    const nextButton = document.querySelector('.btn-next');
    if (nextButton) {
        if (loading) {
            nextButton.disabled = true;
            nextButton.classList.add('loading');
            nextButton.textContent = 'Processing...';
        } else {
            nextButton.disabled = false;
            nextButton.classList.remove('loading');
            nextButton.textContent = 'Next';
        }
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}-notification`;
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">${type === 'error' ? '✗' : '✓'}</div>
            <div class="notification-message">${message}</div>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Initialize notifications
function initializeNotifications() {
    // Check for session messages
    const successMessage = document.querySelector('[data-success]');
    if (successMessage) {
        showNotification(successMessage.textContent, 'success');
    }
    
    const errorMessage = document.querySelector('[data-error]');
    if (errorMessage) {
        showNotification(errorMessage.textContent, 'error');
    }
}

// Initialize notifications on page load
document.addEventListener('DOMContentLoaded', initializeNotifications);
