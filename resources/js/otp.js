// OTP verification page functionality

document.addEventListener('DOMContentLoaded', function() {
    const otpForm = document.getElementById('otp-form');
    const resendLink = document.getElementById('resend-otp');

    // Generate OTP when page loads
    generateOtp();

    // Handle OTP form submission
    otpForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const otp = document.getElementById('otp-input').value;
        
        try {
            const response = await fetch('/otp/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
                },
                body: JSON.stringify({ otp: otp })
            });

            if (response.ok) {
                // OTP verified successfully, redirect to dashboard with JWT token
                const token = localStorage.getItem('jwt_token');
                window.location.href = `/dashboard?token=${encodeURIComponent(token)}`;
            } else {
                const data = await response.json();
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = data.message || 'Invalid OTP code';
                otpForm.insertBefore(errorDiv, otpForm.firstChild);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Handle resend OTP
    resendLink.addEventListener('click', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('/otp/resend', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
                }
            });

            if (response.ok) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'success-message';
                successDiv.textContent = 'New OTP code has been sent to your email.';
                otpForm.insertBefore(successDiv, otpForm.firstChild);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Function to generate OTP
    async function generateOtp() {
        try {
            const response = await fetch('/otp/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
                }
            });

            if (response.ok) {
                // OTP generated successfully
            } else {
                // Failed to generate OTP
            }
        } catch (error) {
            console.error('Error generating OTP:', error);
        }
    }
});
