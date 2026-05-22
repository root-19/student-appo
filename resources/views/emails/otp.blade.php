<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1a1a1a;
            padding: 40px 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .college-name {
            color: #10b981;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        .welcome-text {
            color: #ffffff;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .enrollment-text {
            color: #10b981;
            font-size: 20px;
            margin-bottom: 30px;
        }
        .message {
            color: #d1d5db;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-container {
            border: 2px dashed #10b981;
            background-color: rgba(16, 185, 129, 0.1);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .otp-code {
            color: #10b981;
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 8px;
            margin: 0;
        }
        .expiry {
            color: #9ca3af;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #374151;
        }
        .address {
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.8;
        }
        .disclaimer {
            color: #6b7280;
            font-size: 11px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="25" r="23" stroke="#10b981" stroke-width="2"/>
                    <path d="M25 10V40M10 25H40" stroke="#10b981" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="college-name">PATEROS TECHNOLOGICAL COLLEGE</div>
        </div>

        <h1 class="welcome-text">Hello, {{ $name ?? 'Student' }}!</h1>
        <p class="enrollment-text">Welcome to Online Enrollment</p>

        <p class="message">
            Please use the one-time code below for email verification. This code will help us verify your identity and secure your account.
        </p>

        <div class="otp-container">
            <p class="otp-code">{{ $otp }}</p>
            <p class="expiry">Expires in 10 minutes</p>
        </div>

        <p class="message">
            If you didn't request this code, please ignore this email. Your account remains secure.
        </p>

        <div class="footer">
            <p class="address">
                Pateros Technological College<br>
                Main Street, Pateros, Metro Manila<br>
                Philippines
            </p>
            <p class="disclaimer">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
