// Application Status Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize notification system
    initializeNotifications();
    
    // Initialize form interactions
    initializeFormInteractions();
});

function initializeFormValidation() {
    const form = document.getElementById('status-form');
    if (!form) return;

    const tctInput = document.getElementById('tct_number');
    const taxDeclarationInput = document.getElementById('tax_declaration_number');
    const ownerNumberInput = document.getElementById('owner_number');
    const termsCheckbox = document.getElementById('terms_accepted');

    // Real-time validation
    [tctInput, taxDeclarationInput, ownerNumberInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        }
    });

    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            clearFieldError(this);
        });
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Show loading state
            showLoadingState();
            
            // Submit the form
            form.submit();
        }
    });
}

function validateForm() {
    const tctInput = document.getElementById('tct_number');
    const taxDeclarationInput = document.getElementById('tax_declaration_number');
    const ownerNumberInput = document.getElementById('owner_number');
    const termsCheckbox = document.getElementById('terms_accepted');

    let isValid = true;

    // Check if at least one field is provided
    const tctValue = tctInput ? tctInput.value.trim() : '';
    const taxDeclarationValue = taxDeclarationInput ? taxDeclarationInput.value.trim() : '';
    const ownerNumberValue = ownerNumberInput ? ownerNumberInput.value.trim() : '';

    if (!tctValue && !taxDeclarationValue && !ownerNumberValue) {
        showFieldError(tctInput, 'Please provide at least one of the required fields.');
        isValid = false;
    }

    // Check terms acceptance
    if (termsCheckbox && !termsCheckbox.checked) {
        showFieldError(termsCheckbox, 'You must agree to the Terms and Privacy Policy.');
        isValid = false;
    }

    return isValid;
}

function showFieldError(field, message) {
    if (!field) return;

    clearFieldError(field);

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
    field.style.borderColor = '#d32f2f';
}

function clearFieldError(field) {
    if (!field) return;

    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
    field.style.borderColor = '';
}

function showLoadingState() {
    const submitButton = document.querySelector('.continue-btn');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'VERIFYING...';
        submitButton.style.opacity = '0.7';
    }
}

function initializeNotifications() {
    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            hideNotification(notification);
        }, 5000);
    });
}

function hideNotification(notification) {
    if (notification) {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

function closeNotification() {
    const notification = document.querySelector('.notification');
    hideNotification(notification);
}

function initializeFormInteractions() {
    // Add focus effects to form fields
    const formFields = document.querySelectorAll('.form-field input');
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });

        field.addEventListener('blur', function() {
            this.parentNode.classList.remove('focused');
        });
    });

    // Add click effect to buttons (with touch support)
    const buttons = document.querySelectorAll('.continue-btn');
    buttons.forEach(button => {
        // Handle both click and touch events
        const handleButtonPress = function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            
            // Get coordinates from either mouse or touch event
            let x, y;
            if (e.touches && e.touches[0]) {
                x = e.touches[0].clientX - rect.left - size / 2;
                y = e.touches[0].clientY - rect.top - size / 2;
            } else {
                x = e.clientX - rect.left - size / 2;
                y = e.clientY - rect.top - size / 2;
            }
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        };

        button.addEventListener('click', handleButtonPress);
        button.addEventListener('touchstart', handleButtonPress);
    });

    // Add mobile-specific form improvements
    initializeMobileFormImprovements();
}

function initializeMobileFormImprovements() {
    // Prevent zoom on input focus for iOS
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        // Add inputmode for better mobile keyboard
        if (input.type === 'text') {
            input.setAttribute('inputmode', 'text');
        }
        
        // Prevent zoom on focus for iOS
        input.addEventListener('focus', function() {
            if (window.innerWidth <= 768) {
                const viewport = document.querySelector('meta[name="viewport"]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                }
            }
        });
        
        input.addEventListener('blur', function() {
            if (window.innerWidth <= 768) {
                const viewport = document.querySelector('meta[name="viewport"]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                }
            }
        });
    });

    // Add touch-friendly checkbox styling
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Add visual feedback for mobile
            this.parentNode.classList.add('checked');
            setTimeout(() => {
                this.parentNode.classList.remove('checked');
            }, 200);
        });
    });

    // Handle orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            // Recalculate any layout-dependent elements
            const statusCard = document.querySelector('.status-card');
            if (statusCard) {
                statusCard.style.height = 'auto';
            }
        }, 100);
    });
}

// Add CSS for ripple effect and animations
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .form-field.focused label {
        color: #1976d2;
    }
    
    .form-field.focused input {
        border-color: #1976d2;
        box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
    }
    
    /* Mobile-specific improvements */
    @media (max-width: 768px) {
        .remember.checked {
            background-color: rgba(25, 118, 210, 0.1);
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }
        
        /* Touch-friendly button sizing */
        .continue-btn {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Better touch targets */
        input[type="checkbox"] {
            min-width: 18px;
            min-height: 18px;
        }
        
        /* Prevent text selection on buttons */
        .continue-btn {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Smooth scrolling for mobile */
        html {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Better form field spacing on mobile */
        .form-field {
            margin-bottom: 20px;
        }
        
        .form-field:last-child {
            margin-bottom: 0;
        }
    }
    
    /* Very small screens */
    @media (max-width: 360px) {
        .continue-btn {
            min-height: 40px;
            font-size: 12px;
        }
        
        .form-field input[type="text"] {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
    
    /* Landscape mobile adjustments */
    @media (max-width: 768px) and (orientation: landscape) {
        .status-card {
            padding: 15px;
        }
        
        .detail-row {
            padding: 5px 0;
        }
        
        .action-buttons {
            flex-direction: row;
            gap: 10px;
        }
        
        .action-buttons .continue-btn {
            flex: 1;
        }
    }
`;
document.head.appendChild(style);