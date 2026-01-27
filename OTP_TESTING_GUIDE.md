# OTP API Testing Guide

## Prerequisites
- Ensure your Laravel server is running (`php artisan serve`)
- Have a registered customer account with email and phone
- Configure email settings in `.env` (or use debug mode)
- Have Postman, Insomnia, or curl installed

---

## Test Scenario 1: Email OTP Login

### Step 1: Send Email OTP
```bash
curl -X POST http://localhost:8000/api/customer/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "your-email@example.com"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "OTP sent successfully to your email",
  "data": {
    "email": "your-email@example.com",
    "expires_in_minutes": 10
  }
}
```

**If in debug mode and email fails:**
```json
{
  "success": true,
  "message": "OTP generated (Email failed, check logs)",
  "data": {
    "email": "your-email@example.com",
    "expires_in_minutes": 10,
    "otp_for_testing": "123456"
  }
}
```

### Step 2: Check Email
- Open your email inbox
- Look for email from BMV with subject "Your BMV Login OTP Code"
- Copy the 6-digit OTP code

### Step 3: Verify Email OTP
```bash
curl -X POST http://localhost:8000/api/customer/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "your-email@example.com",
    "otp": "123456"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "OTP verified successfully. You are now logged in.",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "your-email@example.com",
      ...
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

### Step 4: Use Access Token
```bash
curl -X GET http://localhost:8000/api/customer/profile \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

---

## Test Scenario 2: Phone OTP Login

### Step 1: Send Phone OTP
```bash
curl -X POST http://localhost:8000/api/customer/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "9876543210",
    "country_code": "+91"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "OTP sent successfully to your phone",
  "data": {
    "phone": "9876543210",
    "expires_in_minutes": 10
  }
}
```

### Step 2: Check SMS
- Check your phone for SMS from Twilio
- Copy the 6-digit OTP code

### Step 3: Verify Phone OTP
```bash
curl -X POST http://localhost:8000/api/customer/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "9876543210",
    "otp": "123456"
  }'
```

**Expected Response:** Same as email verification (includes JWT token)

---

## Test Scenario 3: Resend OTP

### Email Resend
```bash
curl -X POST http://localhost:8000/api/customer/resend-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "your-email@example.com"
  }'
```

### Phone Resend
```bash
curl -X POST http://localhost:8000/api/customer/resend-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "9876543210",
    "country_code": "+91"
  }'
```

---

## Test Scenario 4: Error Cases

### Invalid Email Format
```bash
curl -X POST http://localhost:8000/api/customer/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "invalid-email"
  }'
```

**Expected:** 422 Validation Error

### Unregistered Email
```bash
curl -X POST http://localhost:8000/api/customer/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "notregistered@example.com"
  }'
```

**Expected:** 404 Email not registered

### Invalid OTP
```bash
curl -X POST http://localhost:8000/api/customer/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "your-email@example.com",
    "otp": "000000"
  }'
```

**Expected:** 400 Invalid OTP

### Expired OTP
Wait 11 minutes after sending OTP, then try to verify.

**Expected:** 400 OTP has expired

### Rate Limiting
Send OTP, then immediately try to resend.

**Expected:** 429 Please wait X seconds

### Missing Country Code for Phone
```bash
curl -X POST http://localhost:8000/api/customer/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "9876543210"
  }'
```

**Expected:** 422 Country code is required

---

## Postman Collection

### Import these requests into Postman:

**1. Send Email OTP**
- Method: POST
- URL: `{{base_url}}/api/customer/send-otp`
- Body (JSON):
```json
{
  "type": "email",
  "identifier": "{{customer_email}}"
}
```

**2. Send Phone OTP**
- Method: POST
- URL: `{{base_url}}/api/customer/send-otp`
- Body (JSON):
```json
{
  "type": "phone",
  "identifier": "{{customer_phone}}",
  "country_code": "{{country_code}}"
}
```

**3. Verify OTP**
- Method: POST
- URL: `{{base_url}}/api/customer/verify-otp`
- Body (JSON):
```json
{
  "type": "{{otp_type}}",
  "identifier": "{{identifier}}",
  "otp": "{{otp_code}}"
}
```

**4. Resend OTP**
- Method: POST
- URL: `{{base_url}}/api/customer/resend-otp`
- Body (JSON):
```json
{
  "type": "{{otp_type}}",
  "identifier": "{{identifier}}",
  "country_code": "{{country_code}}"
}
```

### Environment Variables:
```
base_url = http://localhost:8000
customer_email = test@example.com
customer_phone = 9876543210
country_code = +91
otp_type = email
identifier = test@example.com
otp_code = 123456
```

---

## Debugging Tips

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Database
```sql
SELECT email, phone, email_otp, phone_otp, email_otp_expired_at, otp_expired_at 
FROM customers 
WHERE email = 'your-email@example.com';
```

### Enable Debug Mode
In `.env`:
```
APP_DEBUG=true
```

This will show OTP in response if email/SMS delivery fails.

### Test Email Configuration
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')->subject('Test');
});
```

---

## Expected Behavior Summary

| Scenario | Expected Result |
|----------|----------------|
| Send OTP to registered email | ✅ OTP sent, email received |
| Send OTP to registered phone | ✅ OTP sent, SMS received |
| Send OTP to unregistered email/phone | ❌ 404 Not registered |
| Verify with correct OTP | ✅ Login successful, JWT returned |
| Verify with incorrect OTP | ❌ 400 Invalid OTP |
| Verify expired OTP | ❌ 400 OTP expired |
| Resend OTP immediately | ❌ 429 Rate limited |
| Resend OTP after 1 minute | ✅ New OTP sent |
| Missing country code for phone | ❌ 422 Validation error |
| Invalid email format | ❌ 422 Validation error |

---

## Success Criteria

✅ All test scenarios pass
✅ Email OTP received in inbox
✅ Phone OTP received via SMS
✅ JWT token returned after verification
✅ Rate limiting works
✅ Error messages are clear
✅ Logs show no errors
✅ Database fields updated correctly

---

**Happy Testing! 🚀**
