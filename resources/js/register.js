// Contact number validation - only allow numbers and format as 0912 345 6789
const contactNumberInput = document.getElementById('contact_number');
if (contactNumberInput) {
    contactNumberInput.addEventListener('input', function() {
        // Remove any non-numeric characters
        let value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 11 digits (0912 345 6789 = 11 digits)
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        // Format with spaces: 0912 345 6789
        if (value.length > 0) {
            if (value.length <= 4) {
                this.value = value;
            } else if (value.length <= 7) {
                this.value = value.substring(0, 4) + ' ' + value.substring(4);
            } else {
                this.value = value.substring(0, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7);
            }
        } else {
            this.value = '';
        }
    });
    
    // Prevent pasting non-numeric content
    contactNumberInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const numericOnly = paste.replace(/[^0-9]/g, '').slice(0, 11);
        
        // Format the pasted content
        if (numericOnly.length > 0) {
            if (numericOnly.length <= 4) {
                this.value = numericOnly;
            } else if (numericOnly.length <= 7) {
                this.value = numericOnly.substring(0, 4) + ' ' + numericOnly.substring(4);
            } else {
                this.value = numericOnly.substring(0, 4) + ' ' + numericOnly.substring(4, 7) + ' ' + numericOnly.substring(7);
            }
        } else {
            this.value = '';
        }
    });
}

// Password validation
const passwordInput = document.getElementById('password');
const passwordConfirmation = document.getElementById('password_confirmation');

passwordInput.addEventListener('input', function() {
    const password = this.value;
    
    // Desktop password requirements
    const lengthReq = document.getElementById('req-length');
    const upperReq = document.getElementById('req-uppercase');
    const lowerReq = document.getElementById('req-lowercase');
    const numberReq = document.getElementById('req-number');
    const specialReq = document.getElementById('req-special');
    
    // Mobile password requirements
    const mobileLengthReq = document.getElementById('mobile-req-length');
    const mobileUpperReq = document.getElementById('mobile-req-uppercase');
    const mobileLowerReq = document.getElementById('mobile-req-lowercase');
    const mobileNumberReq = document.getElementById('mobile-req-number');
    const mobileSpecialReq = document.getElementById('mobile-req-special');
    
    
    // Check length
    if (password.length >= 8) {
        if (lengthReq) lengthReq.classList.add('valid');
        if (mobileLengthReq) mobileLengthReq.classList.add('valid');
    } else {
        if (lengthReq) lengthReq.classList.remove('valid');
        if (mobileLengthReq) mobileLengthReq.classList.remove('valid');
    }
    
    // Check uppercase
    if (/[A-Z]/.test(password)) {
        if (upperReq) upperReq.classList.add('valid');
        if (mobileUpperReq) mobileUpperReq.classList.add('valid');
    } else {
        if (upperReq) upperReq.classList.remove('valid');
        if (mobileUpperReq) mobileUpperReq.classList.remove('valid');
    }
    
    // Check lowercase
    if (/[a-z]/.test(password)) {
        if (lowerReq) lowerReq.classList.add('valid');
        if (mobileLowerReq) mobileLowerReq.classList.add('valid');
    } else {
        if (lowerReq) lowerReq.classList.remove('valid');
        if (mobileLowerReq) mobileLowerReq.classList.remove('valid');
    }
    
    // Check number
    if (/\d/.test(password)) {
        if (numberReq) numberReq.classList.add('valid');
        if (mobileNumberReq) mobileNumberReq.classList.add('valid');
    } else {
        if (numberReq) numberReq.classList.remove('valid');
        if (mobileNumberReq) mobileNumberReq.classList.remove('valid');
    }
    
    // Check special character
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        if (specialReq) specialReq.classList.add('valid');
        if (mobileSpecialReq) mobileSpecialReq.classList.add('valid');
    } else {
        if (specialReq) specialReq.classList.remove('valid');
        if (mobileSpecialReq) mobileSpecialReq.classList.remove('valid');
    }
    
    // Check password match
    checkPasswordMatch();
});

// Password confirmation
passwordConfirmation.addEventListener('input', function() {
    checkPasswordMatch();
});

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmation = passwordConfirmation.value;
    
    // Remove existing validation classes
    passwordConfirmation.classList.remove('password-match-valid', 'password-match-invalid');
    
    if (confirmation && password === confirmation) {
        passwordConfirmation.classList.add('password-match-valid');
    } else if (confirmation) {
        passwordConfirmation.classList.add('password-match-invalid');
    }
}


// File upload display
const fileInput = document.getElementById('government_id_file');
const fileLabel = document.querySelector('.file-upload-label');

fileInput.addEventListener('change', function() {
    if (this.files.length > 0) {
        let fileName = this.files[0].name;
        // Truncate long file names to prevent layout issues
        if (fileName.length > 20) {
            fileName = fileName.substring(0, 17) + '...';
        }
        fileLabel.innerHTML = `<span class="selected-text">Selected:</span> <span class="file-name">${fileName}</span>`;
        fileLabel.style.borderColor = '#10b981';
        fileLabel.style.background = '#d1fae5';
        fileLabel.style.color = '#065f46';
    }
});

// OTP functionality
const sendOtpBtn = document.getElementById('sendOtpBtn');
const emailInput = document.getElementById('email');
const verificationCodeInput = document.getElementById('verification_code');
const otpTimer = document.getElementById('otpTimer');
const otpSuccessModal = document.getElementById('otpSuccessModal');
const closeOtpModal = document.getElementById('closeOtpModal');

sendOtpBtn.addEventListener('click', function() {
    const email = emailInput.value;
    
    if (!email) {
        alert('Please enter your email address first.');
        return;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    
    this.disabled = true;
    this.textContent = 'Sending...';
    
    fetch('/otp/send-registration', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            email: email,
            first_name: document.getElementById('first_name').value || 'User'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            otpSuccessModal.style.display = 'block';
            this.textContent = 'Resend OTP';
            startOtpTimer();
        } else {
            alert(data.message || 'Failed to send OTP. Please try again.');
            this.disabled = false;
            this.textContent = 'Send OTP';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        this.disabled = false;
        this.textContent = 'Send OTP';
    });
});

// OTP Timer
let otpTimerInterval;
function startOtpTimer() {
    let timeLeft = 300; // 5 minutes
    otpTimerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        otpTimer.textContent = `OTP expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(otpTimerInterval);
            otpTimer.textContent = 'OTP expired. Please request a new one.';
            sendOtpBtn.disabled = false;
            sendOtpBtn.textContent = 'Send OTP';
        }
        timeLeft--;
    }, 1000);
}

closeOtpModal.addEventListener('click', function() {
    otpSuccessModal.style.display = 'none';
});


function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Confirmation Modal
const form = document.getElementById('registerForm');
const confirmationModal = document.getElementById('confirmationModal');
const confirmBtn = document.getElementById('confirmSubmit');
const cancelBtn = document.getElementById('cancelSubmit');

form.addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    confirmationModal.style.display = 'block'; // Show confirmation modal
});

confirmBtn.addEventListener('click', function() {
    confirmationModal.style.display = 'none'; // Hide confirmation modal
    
    // Submit the form to the backend
    form.submit();
});

cancelBtn.addEventListener('click', function() {
    confirmationModal.style.display = 'none'; // Hide confirmation modal
});



// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target === confirmationModal) {
        confirmationModal.style.display = 'none';
    }
    if (e.target === otpSuccessModal) {
        otpSuccessModal.style.display = 'none';
    }
});
