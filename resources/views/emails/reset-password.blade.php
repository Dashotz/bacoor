<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Your Password - City Government of Bacoor</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
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
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            color: #64748b;
            margin: 0;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .description {
            font-size: 16px;
            margin-bottom: 25px;
            color: #475569;
        }
        .reset-button {
            display: inline-block;
            background: #0a3b7a;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .reset-button:hover {
            background: #0c4a8f;
        }
        .manual-link {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 14px;
            color: #475569;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 16px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        .security-notice {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>City Government of Bacoor</h1>
            <p>Password Reset Request</p>
        </div>

        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $user->first_name ?? 'User' }}</strong>,
            </div>

            <div class="description">
                You have requested to reset your password for your City Government of Bacoor Citizen Portal account. 
                To proceed with the password reset, please click the button below:
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">Reset My Password</a>
            </div>

            <div class="manual-link">
                <strong>Or copy and paste this link into your browser:</strong><br>
                {{ $resetUrl }}
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> This password reset link will expire in 24 hours for security reasons.
            </div>

            <div class="security-notice">
                <strong>🔒 Security Notice:</strong> If you didn't request this password reset, please ignore this email. 
                Your account security is important to us.
            </div>

            <div class="description">
                After clicking the reset link, you'll be able to create a new password for your account. 
                Make sure to choose a strong password that you haven't used before.
            </div>
        </div>

        <div class="footer">
            <p><strong>City Government of Bacoor</strong></p>
            <p>Citizen Portal Support</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
