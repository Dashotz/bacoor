<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Verification - City Government of Bacoor</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0a3b7a;
        }
        .header h1 {
            color: #0a3b7a;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 14px;
        }
        .otp-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #0a3b7a;
            letter-spacing: 8px;
            margin: 20px 0;
            padding: 15px;
            background-color: #e8f1ff;
            border-radius: 8px;
            border: 2px dashed #1353ad;
        }
        .instructions {
            margin: 20px 0;
            padding: 15px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
        .instructions h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        .instructions ul {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>City Government of Bacoor</h1>
            <p>Citizen Portal Verification</p>
        </div>

        <p>Hello <strong>{{ $user->full_name ?? $user->first_name ?? 'User' }}</strong>,</p>

        <p>You have requested to access the City Government of Bacoor Citizen Portal. To complete your login, please use the verification code below:</p>

        <div class="otp-section">
            <h2>Your Verification Code</h2>
            <div class="otp-code">{{ $otpCode }}</div>
            <p><strong>This code will expire in 10 minutes.</strong></p>
        </div>

        <div class="instructions">
            <h3>How to use this code:</h3>
            <ul>
                <li>Go to the OTP verification page</li>
                <li>Enter the 6-digit code above</li>
                <li>Click "Verify & Continue"</li>
            </ul>
        </div>

        <div class="warning">
            <strong>Security Notice:</strong> Never share this code with anyone. City Government of Bacoor staff will never ask for your verification code.
        </div>

        <p>If you didn't request this verification code, please ignore this email or contact our support team immediately.</p>

        <p>Thank you for using the City Government of Bacoor Citizen Portal.</p>

        <div class="footer">
            <p><strong>Serbisyong Tapat, Serbisyong Maaasahan</strong></p>
            <p>City Government of Bacoor<br>
            Citizen Portal Support</p>
        </div>
    </div>
</body>
</html>
