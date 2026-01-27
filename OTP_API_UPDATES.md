# OTP API Updates - Email & Phone Support

## Overview
The OTP APIs have been updated to support both **email** and **phone** verification for customer login. Users can now choose to receive their OTP via email or SMS.

---

## Updated APIs

### 1. Send OTP API
**Endpoint:** `POST /api/customer/send-otp`

**Description:** Sends an OTP to the customer's email or phone number for login verification.

#### Request Parameters:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `type` | string | Yes | Type of OTP delivery: `email` or `phone` |
| `identifier` | string | Yes | Email address or phone number (based on type) |
| `country_code` | string | Conditional | Required only for `phone` type (e.g., `+91`, `+1`) |

#### Request Examples:

**Email OTP:**
```json
{
  "type": "email",
  "identifier": "customer@example.com"
}
```

**Phone OTP:**
```json
{
  "type": "phone",
  "identifier": "9876543210",
  "country_code": "+91"
}
```

#### Success Response (200):
```json
{
  "success": true,
  "message": "OTP sent successfully to your email",
  "data": {
    "email": "customer@example.com",
    "expires_in_minutes": 10
  }
}
```

#### Error Responses:
- **404 Not Found:** Email/Phone not registered
- **422 Validation Error:** Invalid email format or missing country code
- **500 Server Error:** Failed to send OTP

---

### 2. Verify OTP API
**Endpoint:** `POST /api/customer/verify-otp`

**Description:** Verifies the OTP and logs the customer in by returning a JWT token.

#### Request Parameters:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `type` | string | Yes | Type of OTP: `email` or `phone` |
| `identifier` | string | Yes | Email address or phone number (based on type) |
| `otp` | string | Yes | 6-digit OTP code |

#### Request Examples:

**Email OTP Verification:**
```json
{
  "type": "email",
  "identifier": "customer@example.com",
  "otp": "123456"
}
```

**Phone OTP Verification:**
```json
{
  "type": "phone",
  "identifier": "9876543210",
  "otp": "123456"
}
```

#### Success Response (200):
```json
{
  "success": true,
  "message": "OTP verified successfully. You are now logged in.",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "customer@example.com",
      "phone": "9876543210",
      "country_code": "+91",
      "username": "john_doe",
      "canonical": "johndoe_abc12345",
      "profile_image": null,
      "gender": "male",
      "dob": "1990-01-01",
      "address": null,
      "city": null,
      "state": null,
      "country": null,
      "pincode": null,
      "status": "active",
      "phone_validate": true,
      "created_at": "2026-01-27T10:30:00.000000Z",
      "updated_at": "2026-01-27T16:30:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

#### Error Responses:
- **400 Bad Request:** Invalid OTP, expired OTP, or no OTP found
- **404 Not Found:** Email/Phone not registered
- **422 Validation Error:** Invalid email format
- **500 Server Error:** Verification failed

---

### 3. Resend OTP API
**Endpoint:** `POST /api/customer/resend-otp`

**Description:** Resends a new OTP to the customer's email or phone number with rate limiting.

#### Request Parameters:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `type` | string | Yes | Type of OTP delivery: `email` or `phone` |
| `identifier` | string | Yes | Email address or phone number (based on type) |
| `country_code` | string | Conditional | Required only for `phone` type (e.g., `+91`, `+1`) |

#### Request Examples:

**Resend Email OTP:**
```json
{
  "type": "email",
  "identifier": "customer@example.com"
}
```

**Resend Phone OTP:**
```json
{
  "type": "phone",
  "identifier": "9876543210",
  "country_code": "+91"
}
```

#### Success Response (200):
```json
{
  "success": true,
  "message": "OTP resent successfully to your email",
  "data": {
    "email": "customer@example.com",
    "expires_in_minutes": 10
  }
}
```

#### Error Responses:
- **404 Not Found:** Email/Phone not registered
- **422 Validation Error:** Invalid email format or missing country code
- **429 Too Many Requests:** Rate limit exceeded (must wait before requesting new OTP)
- **500 Server Error:** Failed to resend OTP

---

## Implementation Details

### Database Changes
Added new fields to `customers` table:
- `email_otp` (string, nullable) - Stores email OTP
- `email_otp_expired_at` (datetime, nullable) - Email OTP expiration timestamp

Existing fields for phone OTP:
- `phone_otp` (string, nullable) - Stores phone OTP
- `otp_expired_at` (datetime, nullable) - Phone OTP expiration timestamp
- `phone_validate` (boolean) - Phone verification status

### Email Template
- Created `CustomerOTPMail` mailable class
- Professional email template with OTP code display
- Includes security notice and expiration time

### Rate Limiting
- Prevents spam by enforcing a 1-minute wait time between OTP requests
- Separate rate limiting for email and phone OTP

### Security Features
1. **OTP Expiration:** OTPs expire after 10 minutes
2. **One-Time Use:** OTPs are cleared after successful verification
3. **Hidden Fields:** OTP codes are hidden in API responses (except debug mode)
4. **Rate Limiting:** Prevents brute force attacks
5. **Auto-Login:** Successful OTP verification automatically logs in the customer

### Debug Mode
When `APP_DEBUG=true`:
- Failed email/SMS sends still return success with OTP in response
- Useful for development and testing
- **Must be disabled in production**

---

## Testing Guide

### Test Email OTP Flow:
1. **Send OTP:**
   ```bash
   POST /api/customer/send-otp
   {
     "type": "email",
     "identifier": "test@example.com"
   }
   ```

2. **Check email for OTP code**

3. **Verify OTP:**
   ```bash
   POST /api/customer/verify-otp
   {
     "type": "email",
     "identifier": "test@example.com",
     "otp": "123456"
   }
   ```

4. **Receive JWT token and login**

### Test Phone OTP Flow:
1. **Send OTP:**
   ```bash
   POST /api/customer/send-otp
   {
     "type": "phone",
     "identifier": "9876543210",
     "country_code": "+91"
   }
   ```

2. **Check SMS for OTP code**

3. **Verify OTP:**
   ```bash
   POST /api/customer/verify-otp
   {
     "type": "phone",
     "identifier": "9876543210",
     "otp": "123456"
   }
   ```

4. **Receive JWT token and login**

---

## Migration Command
```bash
php artisan migrate
```

This will add the `email_otp` and `email_otp_expired_at` fields to the customers table.

---

## Notes
- Email OTP and Phone OTP are independent systems
- Customers can use either method for login
- Phone verification (`phone_validate`) is only updated when verifying phone OTP
- Email OTP does not affect phone verification status
- Both methods provide JWT token upon successful verification
- OTP length: 6 digits
- Default expiration: 10 minutes (configurable in `config/services.php`)
