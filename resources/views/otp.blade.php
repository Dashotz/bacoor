<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify OTP - City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/otp.css', 'resources/js/app.js'])
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
                <p class="otp-subtitle">We've sent a 6-digit code to {{ session('user_email') }}</p>
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

            <form method="POST" action="{{ route('otp.verify') }}" class="otp-form">
                @csrf
                <input type="text" name="otp" class="otp-input" placeholder="000000" maxlength="6" required autocomplete="off" autofocus>
                <button type="submit" class="otp-button">Verify & Continue</button>
            </form>

            <div class="otp-resend">
                <a href="{{ route('otp.resend') }}">Resend Code</a>
            </div>
        </div>
    </div>
</body>
</html>
