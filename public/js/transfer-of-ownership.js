// Transfer of Ownership Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeTransferForm();
    initializeFileUpload();
    initializeFormValidation();
    initializeNotifications();
});

// Initialize the transfer form
function initializeTransferForm() {
    const form = document.getElementById('transfer-form');
    if (!form) return;

    // Add form submission handler
    form.addEventListener('submit', handleFormSubmission);
    
    // Add real-time validation
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
}

// Handle form submission
function handleFormSubmission(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Validate form before submission
    if (!validateForm(form)) {
        showNotification('Please fix the errors before submitting.', 'error');
        return;
    }
    
    // Show loading state
    setButtonLoading(submitBtn, true);
    
    // Submit form
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        setButtonLoading(submitBtn, false);
        
        if (data.success) {
            showNotification(data.message || 'Form submitted successfully!', 'success');
            // Redirect or show next step
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            showNotification(data.message || 'An error occurred. Please try again.', 'error');
            if (data.errors) {
                displayFormErrors(data.errors);
            }
        }
    })
    .catch(error => {
        setButtonLoading(submitBtn, false);
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Initialize file upload functionality
function initializeFileUpload() {
    const fileUploadArea = document.getElementById('file-upload-area');
    const fileInput = document.getElementById('application_photo');
    
    if (!fileUploadArea || !fileInput) return;

    // Click to upload
    fileUploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop
    fileUploadArea.addEventListener('dragover', handleDragOver);
    fileUploadArea.addEventListener('dragleave', handleDragLeave);
    fileUploadArea.addEventListener('drop', handleDrop);

    // File selection
    fileInput.addEventListener('change', handleFileSelect);
}

// Handle drag over
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('dragover');
}

// Handle drag leave
function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
}

// Handle file drop
function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('application_photo');
        fileInput.files = files;
        handleFileSelect({ target: fileInput });
    }
}

// Handle file selection
function handleFileSelect(e) {
    const file = e.target.files[0];
    const fileUploadArea = e.target.closest('.file-upload-area');
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('Please select an image file.', 'error');
            e.target.value = '';
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('File size must be less than 5MB.', 'error');
            e.target.value = '';
            return;
        }
        
        // Update UI
        fileUploadArea.classList.add('has-file');
        const uploadText = fileUploadArea.querySelector('.upload-text');
        uploadText.textContent = `Selected: ${file.name}`;
        
        // Show preview
        showImagePreview(file);
    }
}

// Show image preview
function showImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.createElement('img');
        preview.src = e.target.result;
        preview.style.maxWidth = '200px';
        preview.style.maxHeight = '200px';
        preview.style.borderRadius = '8px';
        preview.style.marginTop = '10px';
        
        const fileUploadArea = document.getElementById('file-upload-area');
        const existingPreview = fileUploadArea.querySelector('.image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        const previewContainer = document.createElement('div');
        previewContainer.className = 'image-preview';
        previewContainer.appendChild(preview);
        fileUploadArea.appendChild(previewContainer);
    };
    reader.readAsDataURL(file);
}

// Initialize form validation
function initializeFormValidation() {
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (password && passwordConfirmation) {
        passwordConfirmation.addEventListener('input', function() {
            if (this.value && this.value !== password.value) {
                setFieldError(this, 'Passwords do not match');
            } else {
                clearFieldError(this);
            }
        });
    }
    
    // Email validation
    const email = document.getElementById('email');
    if (email) {
        email.addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                setFieldError(this, 'Please enter a valid email address');
            } else {
                clearFieldError(this);
            }
        });
    }
    
    // Phone number validation
    const contactNumber = document.getElementById('contact_number');
    if (contactNumber) {
        contactNumber.addEventListener('input', function() {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
            if (this.value && !phoneRegex.test(this.value)) {
                setFieldError(this, 'Please enter a valid phone number');
            } else {
                clearFieldError(this);
            }
        });
    }
}

// Validate entire form
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField({ target: field })) {
            isValid = false;
        }
    });
    
    // Validate password confirmation
    const password = form.querySelector('#password');
    const passwordConfirmation = form.querySelector('#password_confirmation');
    if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
        setFieldError(passwordConfirmation, 'Passwords do not match');
        isValid = false;
    }
    
    return isValid;
}

// Validate individual field
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    
    // Clear previous errors
    clearFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        setFieldError(field, 'This field is required');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            setFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Phone validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        if (!phoneRegex.test(value)) {
            setFieldError(field, 'Please enter a valid phone number');
            return false;
        }
    }
    
    // Password strength validation
    if (field.type === 'password' && value) {
        if (value.length < 8) {
            setFieldError(field, 'Password must be at least 8 characters long');
            return false;
        }
    }
    
    // File validation
    if (field.type === 'file' && field.files.length > 0) {
        const file = field.files[0];
        if (!file.type.startsWith('image/')) {
            setFieldError(field, 'Please select an image file');
            return false;
        }
        if (file.size > 5 * 1024 * 1024) {
            setFieldError(field, 'File size must be less than 5MB');
            return false;
        }
    }
    
    return true;
}

// Set field error
function setFieldError(field, message) {
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

// Display form errors from server
function displayFormErrors(errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            setFieldError(field, errors[fieldName][0]);
        }
    });
}

// Initialize notifications
function initializeNotifications() {
    // Auto-hide success notifications after 5 seconds
    const successNotification = document.getElementById('success-notification');
    if (successNotification) {
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const container = document.getElementById('notification-container');
    if (!container) return;
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}-notification`;
    
    const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
    
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">${icon}</div>
            <div class="notification-message">${message}</div>
            <button class="notification-close" onclick="closeNotification(this)">&times;</button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Close notification
function closeNotification(button) {
    const notification = button ? button.closest('.notification') : document.querySelector('.notification');
    if (notification) {
        notification.remove();
    }
}

// Set button loading state
function setButtonLoading(button, loading) {
    if (loading) {
        button.disabled = true;
        button.classList.add('loading');
        button.dataset.originalText = button.textContent;
        button.textContent = 'Processing...';
    } else {
        button.disabled = false;
        button.classList.remove('loading');
        button.textContent = button.dataset.originalText || 'Next';
    }
}

// Go back function
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '/';
    }
}

// Utility function to format phone number
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 10) {
        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }
    input.value = value;
}

// Add phone number formatting
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('contact_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    }
});

// Add smooth scrolling for better UX
function smoothScrollTo(element) {
    element.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Add keyboard navigation support
document.addEventListener('keydown', function(e) {
    // Enter key on form inputs
    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
        const form = e.target.closest('form');
        if (form) {
            const inputs = Array.from(form.querySelectorAll('input, select'));
            const currentIndex = inputs.indexOf(e.target);
            const nextInput = inputs[currentIndex + 1];
            
            if (nextInput) {
                nextInput.focus();
            } else {
                // If it's the last input, submit the form
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.click();
                }
            }
        }
    }
});

// Add accessibility improvements
function addAccessibilityFeatures() {
    // Add ARIA labels to form sections
    const sections = document.querySelectorAll('.form-section');
    sections.forEach((section, index) => {
        section.setAttribute('role', 'group');
        section.setAttribute('aria-labelledby', `section-${index + 1}`);
        
        const title = section.querySelector('.section-title');
        if (title) {
            title.id = `section-${index + 1}`;
        }
    });
    
    // Add ARIA labels to file upload
    const fileUpload = document.getElementById('file-upload-area');
    if (fileUpload) {
        fileUpload.setAttribute('role', 'button');
        fileUpload.setAttribute('tabindex', '0');
        fileUpload.setAttribute('aria-label', 'Upload application photo');
    }
}

// Initialize accessibility features
document.addEventListener('DOMContentLoaded', addAccessibilityFeatures);
