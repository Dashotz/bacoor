<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - City Government of Bacoor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #0a3b7a;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background: #f4b400;
            color: #1a365d;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background: #e6a200;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>City Government of Bacoor</h1>
        <h2>Password Reset Request</h2>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->first_name }},</p>
        
        <p>We received a request to reset your password for your City Government of Bacoor account.</p>
        
        <p>Click the button below to reset your password:</p>
        
        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        </div>
        
        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
        <p style="word-break: break-all; background: #e9ecef; padding: 10px; border-radius: 4px; font-family: monospace;">
            {{ $resetUrl }}
        </p>
        
        <p><strong>Important:</strong></p>
        <ul>
            <li>This link will expire in 24 hours</li>
            <li>If you didn't request this password reset, please ignore this email</li>
            <li>For security reasons, this link can only be used once</li>
        </ul>
        
        <p>If you have any questions, please contact our support team.</p>
        
        <p>Best regards,<br>
        City Government of Bacoor</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} City Government of Bacoor. All rights reserved.</p>
    </div>
</body>
</html>