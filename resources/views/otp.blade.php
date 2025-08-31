<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify OTP - City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/otp.css', 'resources/js/app.js', 'resources/js/jwt-auth.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="theme-color" content="#0a3b7a" />
</head>
<body>
    <div class="otp-container">
        <div class="otp-card">
            <div class="otp-header">
                <h1 class="otp-title">Verify Your Email</h1>
                <p class="otp-subtitle">We've sent a 6-digit code to your email</p>
            </div>

            @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
            @endif

            @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
            @endif

            <form id="otp-form" class="otp-form">
                <input type="text" name="otp" id="otp-input" class="otp-input" placeholder="000000" maxlength="6" required autocomplete="off" autofocus>
                <button type="submit" class="otp-button">Verify & Continue</button>
            </form>

            <div class="otp-resend">
                <a href="#" id="resend-otp">Resend Code</a>
            </div>
        </div>
    </div>

    <script>
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
                        // OTP verified successfully, redirect to dashboard
                        window.location.href = '/dashboard';
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
                        console.log('OTP generated successfully');
                    } else {
                        console.error('Failed to generate OTP');
                    }
                } catch (error) {
                    console.error('Error generating OTP:', error);
                }
            }
        });
    </script>
</body>
</html>
