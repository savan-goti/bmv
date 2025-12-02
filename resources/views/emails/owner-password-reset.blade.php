<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-body p {
            margin: 15px 0;
            color: #555;
        }
        .reset-button {
            display: inline-block;
            padding: 14px 32px;
            margin: 25px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-2px);
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #856404;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="email-body">
            <h2>Hello {{ $owner->full_name }},</h2>
            
            <p>We received a request to reset your owner account password. If you made this request, click the button below to reset your password:</p>
            
            <div style="text-align: center;">
                <a href="{{ route('owner.reset-password.form', $token) }}" class="reset-button">
                    Reset Password
                </a>
            </div>
            
            <div class="info-box">
                <p><strong>⏰ This link will expire in 60 minutes</strong></p>
                <p>For security reasons, this password reset link is only valid for one hour from the time it was requested.</p>
            </div>
            
            <p>If the button above doesn't work, copy and paste the following URL into your web browser:</p>
            <p style="word-break: break-all; color: #667eea; font-size: 13px;">
                {{ route('owner.reset-password.form', $token) }}
            </p>
            
            <div class="warning-box">
                <p><strong>⚠️ Security Notice</strong></p>
                <p>If you did not request a password reset, please ignore this email or contact your system administrator if you have concerns about your account security.</p>
            </div>
            
            <p style="margin-top: 30px;">Best regards,<br>{{ config('app.name') }} Team</p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
