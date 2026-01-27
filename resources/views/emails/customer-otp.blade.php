<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .code-box {
            background-color: #f0f0f0;
            border: 2px dashed #4CAF50;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            letter-spacing: 5px;
            font-family: 'Courier New', monospace;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Login OTP Verification</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $customer->name }},</p>
            
            <p>We received a login request for your BMV account. Please use the OTP code below to complete your login:</p>
            
            <div class="code-box">
                <div class="code">{{ $otp }}</div>
            </div>
            
            <p>This code will expire in <strong>{{ $expirationMinutes }} minutes</strong>.</p>
            
            <div class="warning">
                <strong>Security Notice:</strong> If you did not attempt to log in, please ignore this email and ensure your account password is secure. Do not share this OTP with anyone.
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; color: #666;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
