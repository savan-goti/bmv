# 🔧 OTP API Troubleshooting Guide

**Last Updated:** 2026-01-19

---

## Common Issues and Solutions

### ✅ FIXED: Error 500 with `now()->addMinutes()`

**Issue:**
```
Error 500 when calling now()->addMinutes($expirationMinutes)
```

**Cause:**
The `now()` helper function was not properly imported in the AuthController.

**Solution:**
Added `use Illuminate\Support\Carbon;` import and replaced all `now()` calls with `Carbon::now()`.

**Files Modified:**
- `app/Http/Controllers/Api/AuthController.php`

**Changes Made:**
```php
// Added import at top of file
use Illuminate\Support\Carbon;

// Changed all instances from:
now()->addMinutes($expirationMinutes)

// To:
Carbon::now()->addMinutes($expirationMinutes)
```

**Status:** ✅ Fixed

---

## Other Common Issues

### 1. Twilio Authentication Error

**Error:**
```json
{
  "success": false,
  "message": "Failed to send OTP. Please try again."
}
```

**Possible Causes:**
- Invalid Twilio credentials
- Missing environment variables
- Incorrect Twilio phone number format

**Solutions:**

1. **Check .env file:**
```bash
# Verify these exist and are correct
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```

2. **Clear config cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

3. **Verify credentials in Twilio Console:**
   - Go to https://console.twilio.com/
   - Check Account SID and Auth Token
   - Verify phone number format includes country code

---

### 2. Phone Number Not Verified (Trial Account)

**Error:**
```
Failed to send OTP
```

**Cause:**
Trial accounts can only send to verified phone numbers.

**Solution:**
1. Go to [Verified Caller IDs](https://console.twilio.com/us1/develop/phone-numbers/manage/verified)
2. Click "Add a new Caller ID"
3. Enter the phone number you want to test
4. Verify via SMS or call

---

### 3. OTP Not Received

**Possible Causes:**
- Network delay
- Invalid phone number
- Twilio service issues
- Insufficient balance

**Solutions:**

1. **Check Twilio Logs:**
   - Go to https://console.twilio.com/us1/monitor/logs/sms
   - Check delivery status
   - Look for error messages

2. **Check Laravel Logs:**
```bash
tail -f storage/logs/laravel.log
```

3. **Verify Phone Number Format:**
   - Must include country code
   - Example: `+911234567890` (not `1234567890`)

4. **Check Twilio Balance:**
   - Go to Console Dashboard
   - Verify you have credit remaining

---

### 4. Invalid OTP Error

**Error:**
```json
{
  "success": false,
  "message": "Invalid OTP. Please try again."
}
```

**Possible Causes:**
- Wrong OTP entered
- OTP expired (10 minutes)
- OTP already used

**Solutions:**
1. Request new OTP using resend endpoint
2. Check OTP carefully (6 digits)
3. Ensure OTP hasn't expired

---

### 5. Rate Limit Error

**Error:**
```json
{
  "success": false,
  "message": "Please wait X seconds before requesting a new OTP"
}
```

**Cause:**
Rate limiting prevents spam (1 minute cooldown).

**Solution:**
Wait for the specified time before requesting a new OTP.

---

### 6. Phone Already Verified

**Error:**
```json
{
  "success": false,
  "message": "Phone number is already verified"
}
```

**Cause:**
The phone number has already been verified.

**Solution:**
This is expected behavior. The customer can proceed to login.

---

### 7. Customer Not Found

**Error:**
```json
{
  "success": false,
  "message": "Customer not found"
}
```

**Cause:**
Phone number not registered in the system.

**Solution:**
1. Register the customer first using `/auth/register`
2. Then use the same phone number for OTP verification

---

## Debugging Steps

### Step 1: Check Environment Variables

```bash
# View Twilio configuration
php artisan tinker
>>> config('services.twilio')
```

Expected output:
```php
[
  "sid" => "AC...",
  "token" => "...",
  "from" => "+1234567890",
  "otp_expiration" => 10
]
```

### Step 2: Test Twilio Connection

Create a test file `test-twilio.php`:

```php
<?php
require 'vendor/autoload.php';

use Twilio\Rest\Client;

$sid = env('TWILIO_SID');
$token = env('TWILIO_AUTH_TOKEN');
$from = env('TWILIO_PHONE_NUMBER');

try {
    $client = new Client($sid, $token);
    
    $message = $client->messages->create(
        '+911234567890', // Your verified phone
        [
            'from' => $from,
            'body' => 'Test message from BMV'
        ]
    );
    
    echo "Message sent! SID: " . $message->sid;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Run:
```bash
php test-twilio.php
```

### Step 3: Check Database

```sql
-- Check if OTP is saved
SELECT id, phone, phone_otp, otp_expired_at, phone_validate 
FROM customers 
WHERE phone = '1234567890';
```

### Step 4: Enable Debug Mode

In `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Then check logs:
```bash
tail -f storage/logs/laravel.log
```

---

## Testing Checklist

- [ ] Twilio credentials added to `.env`
- [ ] Config cache cleared
- [ ] Phone number verified in Twilio console (trial)
- [ ] Customer registered in database
- [ ] Send OTP endpoint tested
- [ ] SMS received on phone
- [ ] Verify OTP endpoint tested
- [ ] Resend OTP tested
- [ ] Rate limiting tested
- [ ] OTP expiration tested

---

## Quick Fixes

### Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Restart Server
```bash
# Stop current server (Ctrl+C)
php artisan serve
```

### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Check for Twilio errors
grep -i "twilio" storage/logs/laravel.log
grep -i "otp" storage/logs/laravel.log
```

---

## Contact Support

If issues persist:

1. **Check Twilio Status:**
   - https://status.twilio.com/

2. **Twilio Support:**
   - https://support.twilio.com/

3. **Laravel Logs:**
   - `storage/logs/laravel.log`

4. **Twilio Logs:**
   - https://console.twilio.com/us1/monitor/logs/sms

---

**Document Version:** 1.1  
**Last Updated:** 2026-01-19  
**Status:** Active
