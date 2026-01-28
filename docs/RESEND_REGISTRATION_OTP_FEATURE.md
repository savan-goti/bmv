# Resend Registration OTP Feature

## Overview
Added a new endpoint to allow users to resend the OTP during the registration process if they didn't receive it or if it expired.

---

## New Endpoint

### **Resend Registration OTP**
**Endpoint:** `POST /api/v1/auth/resend-registration-otp`

**Purpose:** Resends OTP to customer's phone during registration process

---

## Request

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| phone | string | Yes | Mobile number (same as used in registration) |
| country_code | string | Yes | Country code with + (e.g., +91) |

### Example Request
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

### cURL Example
```bash
curl -X POST http://localhost:8000/api/v1/auth/resend-registration-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9876543210",
    "country_code": "+91"
  }'
```

---

## Responses

### Success Response (200)
```json
{
    "success": true,
    "message": "OTP resent successfully to your phone",
    "data": {
        "phone": "9876543210",
        "expires_in_minutes": 10,
        "message": "Please verify OTP to complete registration"
    }
}
```

### Error Responses

#### Phone Not Found (404)
```json
{
    "success": false,
    "message": "Phone number not found. Please register first."
}
```

#### Already Registered (422)
```json
{
    "success": false,
    "message": "This phone number is already registered. Please login instead."
}
```

#### Rate Limited (429)
```json
{
    "success": false,
    "message": "Please wait 45 seconds before requesting a new OTP"
}
```

#### SMS Failed (500)
```json
{
    "success": false,
    "message": "Failed to send OTP. Please try again."
}
```

#### Debug Mode Response (200)
When `APP_DEBUG=true`, includes OTP in response:
```json
{
    "success": true,
    "message": "OTP resent (SMS failed, check logs)",
    "data": {
        "phone": "9876543210",
        "expires_in_minutes": 10,
        "otp_for_testing": "123456",
        "message": "Please verify OTP to complete registration"
    }
}
```

---

## Features

### 1. **Rate Limiting**
- **Cooldown Period:** 1 minute between resend requests
- **Purpose:** Prevents spam and abuse
- **Implementation:** Checks if last OTP was sent less than 1 minute ago
- **Error Code:** 429 (Too Many Requests)

### 2. **Validation Checks**
- ✅ Phone number must exist in database
- ✅ Phone number must not be already verified
- ✅ Phone number must not be already active
- ✅ Rate limiting must not be exceeded

### 3. **OTP Generation**
- **Length:** 6 digits
- **Expiration:** 10 minutes
- **Delivery:** SMS via Twilio
- **Storage:** Saved to `customers.phone_otp` and `customers.otp_expired_at`

---

## Use Cases

### Use Case 1: OTP Not Received
**Scenario:** User didn't receive the initial OTP via SMS

**Flow:**
1. User registers with phone number
2. OTP not received (network issue, SMS delay, etc.)
3. User clicks "Resend OTP" button
4. New OTP is generated and sent
5. User receives OTP and completes registration

### Use Case 2: OTP Expired
**Scenario:** User received OTP but didn't enter it within 10 minutes

**Flow:**
1. User registers with phone number
2. User receives OTP but delays entering it
3. OTP expires after 10 minutes
4. User tries to verify but gets "OTP expired" error
5. User clicks "Resend OTP" button
6. New OTP is generated and sent
7. User enters new OTP and completes registration

### Use Case 3: Rate Limiting
**Scenario:** User tries to spam the resend button

**Flow:**
1. User registers with phone number
2. User immediately clicks "Resend OTP"
3. System checks: last OTP was sent < 1 minute ago
4. System returns 429 error with wait time
5. User waits for cooldown period
6. User can then request new OTP

---

## Implementation Details

### Controller Method
**Location:** `app/Http/Controllers/Api/AuthController.php`

**Method:** `resendRegistrationOTP(Request $request, TwilioService $twilio)`

**Key Logic:**
1. Validate phone and country_code
2. Find customer by phone
3. Check if already registered
4. Check rate limiting
5. Generate new OTP
6. Update database
7. Send OTP via SMS
8. Return success response

### Route
**Location:** `routes/api.php`

**Route Definition:**
```php
Route::post('resend-registration-otp', [AuthController::class, 'resendRegistrationOTP']);
```

---

## Complete Registration Flow

```
┌─────────────────────────────────────────┐
│  Step 1: POST /api/v1/auth/register     │
│  Input: { phone, country_code }         │
│  Output: OTP sent via SMS               │
└─────────────────────────────────────────┘
                    │
                    ▼
         ┌──────────────────────┐
         │   OTP Not Received?  │
         │   OTP Expired?       │
         └──────────────────────┘
                    │
                    ▼ YES
┌─────────────────────────────────────────┐
│  POST /api/v1/auth/resend-registration  │
│  -otp                                   │
│  Input: { phone, country_code }         │
│  Output: New OTP sent via SMS           │
└─────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────┐
│  Step 2: POST /verify-registration-otp  │
│  Input: { phone, otp }                  │
│  Output: Account created + JWT token    │
└─────────────────────────────────────────┘
```

---

## Frontend Integration

### UI/UX Recommendations

1. **Resend Button:**
   - Show "Resend OTP" button below OTP input field
   - Disable button for 60 seconds after sending OTP
   - Show countdown timer: "Resend OTP in 45s"
   - Enable button after cooldown period

2. **User Feedback:**
   - Show success message: "OTP sent successfully"
   - Show error message if rate limited: "Please wait 45 seconds"
   - Show loading state while sending OTP

3. **Example UI Flow:**
```
┌─────────────────────────────────────┐
│  Enter OTP                          │
│  ┌─────┬─────┬─────┬─────┬─────┬─────┐
│  │  1  │  2  │  3  │  4  │  5  │  6  │
│  └─────┴─────┴─────┴─────┴─────┴─────┘
│                                     │
│  Didn't receive OTP?                │
│  [Resend OTP in 45s] (disabled)     │
│                                     │
│  After 60 seconds:                  │
│  [Resend OTP] (enabled)             │
└─────────────────────────────────────┘
```

### JavaScript Example
```javascript
let resendCooldown = 60;
let resendTimer;

function startResendTimer() {
    const resendBtn = document.getElementById('resendBtn');
    resendBtn.disabled = true;
    
    resendTimer = setInterval(() => {
        resendCooldown--;
        resendBtn.textContent = `Resend OTP in ${resendCooldown}s`;
        
        if (resendCooldown <= 0) {
            clearInterval(resendTimer);
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend OTP';
            resendCooldown = 60;
        }
    }, 1000);
}

async function resendOTP() {
    const phone = document.getElementById('phone').value;
    const countryCode = '+91';
    
    try {
        const response = await fetch('/api/v1/auth/resend-registration-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ phone, country_code: countryCode })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('OTP sent successfully!');
            startResendTimer();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('Failed to send OTP. Please try again.');
    }
}
```

---

## Testing

### Manual Testing Steps

1. **Test Successful Resend:**
   ```bash
   # Step 1: Register
   POST /api/v1/auth/register
   { "phone": "9876543210", "country_code": "+91" }
   
   # Step 2: Wait 60 seconds
   
   # Step 3: Resend OTP
   POST /api/v1/auth/resend-registration-otp
   { "phone": "9876543210", "country_code": "+91" }
   
   # Expected: Success response with new OTP
   ```

2. **Test Rate Limiting:**
   ```bash
   # Step 1: Register
   POST /api/v1/auth/register
   { "phone": "9876543210", "country_code": "+91" }
   
   # Step 2: Immediately resend (within 60 seconds)
   POST /api/v1/auth/resend-registration-otp
   { "phone": "9876543210", "country_code": "+91" }
   
   # Expected: 429 error with wait time
   ```

3. **Test Already Registered:**
   ```bash
   # Step 1: Complete registration
   POST /api/v1/auth/register
   POST /api/v1/auth/verify-registration-otp
   
   # Step 2: Try to resend OTP
   POST /api/v1/auth/resend-registration-otp
   { "phone": "9876543210", "country_code": "+91" }
   
   # Expected: 422 error - already registered
   ```

---

## Security Considerations

1. **Rate Limiting:** Prevents brute force attacks and spam
2. **OTP Expiration:** 10-minute window limits exposure
3. **One-time Use:** OTP is cleared after successful verification
4. **Phone Validation:** Only registered phones can request resend
5. **Status Check:** Prevents resend for already active accounts

---

## Database Impact

### Updated Fields
When resend OTP is called, the following fields are updated:

```sql
UPDATE customers SET
    phone_otp = '123456',              -- New OTP
    otp_expired_at = '2026-01-28 15:00:00',  -- New expiration
    updated_at = NOW()
WHERE phone = '9876543210';
```

---

## Monitoring & Logs

### Log Messages

**Success:**
```
OTP resent successfully to phone: +919876543210
```

**SMS Failed (Debug Mode):**
```
OTP not sent via SMS, but saved to database
Phone: +919876543210
OTP: 123456
```

**Rate Limited:**
```
Rate limit exceeded for phone: +919876543210
Wait time: 45 seconds
```

---

## Summary

✅ **Added:** New `resend-registration-otp` endpoint  
✅ **Features:** Rate limiting, validation, OTP regeneration  
✅ **Security:** 1-minute cooldown, status checks  
✅ **Documentation:** Updated all docs and test files  
✅ **Testing:** Manual test cases provided  

---

**Date:** January 28, 2026  
**Version:** 1.0  
**Status:** ✅ Implemented and Ready for Testing
