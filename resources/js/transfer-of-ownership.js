// Application Status Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is authenticated with JWT
    setTimeout(() => {
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
            window.location.href = '/';
            return;
        }
        
        // Initialize form only if authenticated
        initializeTransferForm();
        initializeNotifications();
    }, 1000); // Wait 1 second for JWT auth to initialize
});

// Initialize the transfer of ownership form
function initializeTransferForm() {
    const form = document.getElementById('transfer-form');
    if (!form) return;

    // Add form submission handler
    form.addEventListener('submit', handleFormSubmission);
    
    // Add real-time validation
    const inputs = form.querySelectorAll('input');
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
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        return response.text();
    })
    .then(html => {
        setButtonLoading(submitBtn, false);
        if (html) {
            // Replace the page content with the response
            document.body.innerHTML = html;
            // Re-initialize after content replacement
            initializeApplicationStatus();
            initializeNotifications();
        }
    })
    .catch(error => {
        setButtonLoading(submitBtn, false);
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// Initialize form validation
function initializeFormValidation() {
    // Add any specific validation rules here
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
    
    // Check if at least one field is provided
    const tctNumber = form.querySelector('#tct_number').value.trim();
    const taxDeclarationNumber = form.querySelector('#tax_declaration_number').value.trim();
    const ownerNumber = form.querySelector('#owner_number').value.trim();
    
    if (!tctNumber && !taxDeclarationNumber && !ownerNumber) {
        showNotification('Please provide at least one of the required fields.', 'error');
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
        button.textContent = button.dataset.originalText || 'VERIFY';
    }
}

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
            const inputs = Array.from(form.querySelectorAll('input'));
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
    const form = document.getElementById('status-form');
    if (form) {
        form.setAttribute('role', 'form');
        form.setAttribute('aria-label', 'Application Status Check Form');
    }
}

// Initialize accessibility features
document.addEventListener('DOMContentLoaded', addAccessibilityFeatures);