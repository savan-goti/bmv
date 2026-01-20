# OTP Verification API Documentation

**Version:** 1.0  
**Base URL:** `http://localhost:8000/api/v1`  
**Last Updated:** 2026-01-19

---

## Overview

The OTP (One-Time Password) Verification API allows you to verify customer phone numbers using SMS-based OTP sent via Twilio. This is essential for account security and phone number validation.

---

## Table of Contents

1. [Setup Instructions](#setup-instructions)
2. [API Endpoints](#api-endpoints)
3. [Usage Flow](#usage-flow)
4. [Error Handling](#error-handling)
5. [Testing](#testing)

---

## Setup Instructions

### 1. Install Twilio SDK

The Twilio SDK has already been installed via Composer:

```bash
composer require twilio/sdk
```

### 2. Configure Twilio Credentials

Add the following environment variables to your `.env` file:

```env
# Twilio Configuration
TWILIO_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_PHONE_NUMBER=your_twilio_phone_number
TWILIO_OTP_EXPIRATION=10
```

**How to get Twilio credentials:**

1. Sign up at [https://www.twilio.com/](https://www.twilio.com/)
2. Get a free trial account (includes $15 credit)
3. Go to Console Dashboard
4. Copy your **Account SID** and **Auth Token**
5. Get a Twilio phone number from the console
6. Add these to your `.env` file

### 3. Database Setup

The `customers` table already has the required OTP fields:
- `phone_otp` - Stores the OTP code
- `otp_expired_at` - Stores OTP expiration time
- `phone_validate` - Boolean flag for phone verification status

---

## API Endpoints

### 1. Send OTP

**Endpoint:** `POST /api/v1/auth/send-otp`  
**Authentication:** Not Required  
**Description:** Send OTP to customer's registered phone number

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| phone | string | Yes | Customer's phone number (must be registered) |
| country_code | string | Yes | Country code (e.g., +91, +1) |

#### Request Example

```json
{
  "phone": "1234567890",
  "country_code": "+91"
}
```

#### Success Response (200)

```json
{
  "success": true,
  "message": "OTP sent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 10
  }
}
```

#### Error Responses

**Phone not registered (422):**
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "phone": ["Phone number not registered"]
  }
}
```

**Phone already verified (400):**
```json
{
  "success": false,
  "message": "Phone number is already verified",
  "data": null
}
```

**Failed to send OTP (500):**
```json
{
  "success": false,
  "message": "Failed to send OTP. Please try again.",
  "data": null
}
```

---

### 2. Verify OTP

**Endpoint:** `POST /api/v1/auth/verify-otp`  
**Authentication:** Not Required  
**Description:** Verify the OTP sent to customer's phone

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| phone | string | Yes | exists:customers,phone | Customer's phone number |
| otp | string | Yes | size:6 | 6-digit OTP code |

#### Request Example

```json
{
  "phone": "1234567890",
  "otp": "123456"
}
```

#### Success Response (200)

```json
{
  "success": true,
  "message": "Phone verified successfully",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "country_code": "+91",
      "phone_validate": true,
      "status": "active"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

#### Error Responses

**Invalid OTP (400):**
```json
{
  "success": false,
  "message": "Invalid OTP. Please try again.",
  "data": null
}
```

**OTP Expired (400):**
```json
{
  "success": false,
  "message": "OTP has expired. Please request a new OTP.",
  "data": null
}
```

**No OTP Found (400):**
```json
{
  "success": false,
  "message": "No OTP found. Please request a new OTP.",
  "data": null
}
```

**Phone Already Verified (400):**
```json
{
  "success": false,
  "message": "Phone number is already verified",
  "data": null
}
```

---

### 3. Resend OTP

**Endpoint:** `POST /api/v1/auth/resend-otp`  
**Authentication:** Not Required  
**Description:** Resend OTP to customer's phone (with rate limiting)

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| phone | string | Yes | Customer's phone number |
| country_code | string | Yes | Country code (e.g., +91, +1) |

#### Request Example

```json
{
  "phone": "1234567890",
  "country_code": "+91"
}
```

#### Success Response (200)

```json
{
  "success": true,
  "message": "OTP resent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 10
  }
}
```

#### Error Responses

**Rate Limited (429):**
```json
{
  "success": false,
  "message": "Please wait 45 seconds before requesting a new OTP",
  "data": null
}
```

**Phone Already Verified (400):**
```json
{
  "success": false,
  "message": "Phone number is already verified",
  "data": null
}
```

---

## Usage Flow

### Complete OTP Verification Flow

```
1. Customer Registers
   ↓
2. Send OTP (POST /auth/send-otp)
   ↓
3. Customer receives SMS with OTP
   ↓
4. Verify OTP (POST /auth/verify-otp)
   ↓
5. Phone verified + JWT token returned
   ↓
6. Customer can access protected routes
```

### Alternative Flow: Resend OTP

```
1. Send OTP (POST /auth/send-otp)
   ↓
2. Customer doesn't receive OTP
   ↓
3. Resend OTP (POST /auth/resend-otp)
   ↓
4. Customer receives new OTP
   ↓
5. Verify OTP (POST /auth/verify-otp)
```

---

## Error Handling

### Common Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 400 | Bad Request (Invalid OTP, Already verified, etc.) |
| 404 | Customer not found |
| 422 | Validation Error |
| 429 | Too Many Requests (Rate limited) |
| 500 | Server Error (Failed to send OTP) |

### Error Response Format

All errors follow this format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

---

## Testing

### Test with cURL

#### 1. Send OTP

```bash
curl -X POST http://localhost:8000/api/v1/auth/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "1234567890",
    "country_code": "+91"
  }'
```

#### 2. Verify OTP

```bash
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "1234567890",
    "otp": "123456"
  }'
```

#### 3. Resend OTP

```bash
curl -X POST http://localhost:8000/api/v1/auth/resend-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "1234567890",
    "country_code": "+91"
  }'
```

### Test with Postman

1. Import the collection (create one from the cURL examples)
2. Set environment variable `BASE_URL` to `http://localhost:8000/api/v1`
3. Test each endpoint in sequence

---

## Security Features

### 1. OTP Expiration
- OTP expires after 10 minutes (configurable)
- Expired OTPs cannot be used

### 2. Rate Limiting
- Prevents spam by limiting resend requests
- Minimum 1 minute wait between resend requests

### 3. One-Time Use
- OTP is deleted after successful verification
- Cannot reuse the same OTP

### 4. Phone Validation
- Only registered phone numbers can receive OTP
- Prevents unauthorized OTP requests

### 5. Automatic Cleanup
- OTP is cleared after verification
- Expired OTPs are marked as invalid

---

## Configuration

### OTP Settings

You can configure OTP behavior in `config/services.php`:

```php
'twilio' => [
    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_AUTH_TOKEN'),
    'from' => env('TWILIO_PHONE_NUMBER'),
    'otp_expiration' => env('TWILIO_OTP_EXPIRATION', 10), // minutes
],
```

### Environment Variables

```env
# OTP Expiration (in minutes)
TWILIO_OTP_EXPIRATION=10

# Twilio Credentials
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```

---

## Best Practices

### 1. For Developers

- Always validate phone numbers before sending OTP
- Implement rate limiting to prevent abuse
- Log all OTP requests for security auditing
- Use HTTPS in production
- Never expose Twilio credentials

### 2. For Mobile Apps

- Show OTP input field after successful send
- Implement auto-read SMS feature (Android)
- Add countdown timer for OTP expiration
- Provide clear error messages
- Allow resend after 1 minute

### 3. For Testing

- Use Twilio test credentials for development
- Test with verified phone numbers in trial mode
- Implement mock OTP service for unit tests
- Test all error scenarios

---

## Troubleshooting

### OTP Not Received

**Possible Causes:**
1. Invalid Twilio credentials
2. Insufficient Twilio balance
3. Phone number not verified (trial account)
4. Network issues

**Solutions:**
1. Check `.env` file for correct credentials
2. Verify Twilio account balance
3. Add phone number to verified list in Twilio console
4. Check Twilio logs for delivery status

### OTP Verification Fails

**Possible Causes:**
1. OTP expired
2. Wrong OTP entered
3. Phone number mismatch
4. OTP already used

**Solutions:**
1. Request new OTP
2. Check OTP carefully
3. Ensure phone number matches
4. Use resend OTP endpoint

### Rate Limit Errors

**Possible Causes:**
1. Too many resend requests
2. Spam prevention triggered

**Solutions:**
1. Wait for the specified time
2. Implement proper UI feedback
3. Add countdown timer

---

## Integration Example

### React Native Example

```javascript
// Send OTP
const sendOTP = async (phone, countryCode) => {
  try {
    const response = await fetch('http://localhost:8000/api/v1/auth/send-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        phone: phone,
        country_code: countryCode
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('OTP sent successfully');
      // Navigate to OTP verification screen
    } else {
      console.error('Failed to send OTP:', data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
};

// Verify OTP
const verifyOTP = async (phone, otp) => {
  try {
    const response = await fetch('http://localhost:8000/api/v1/auth/verify-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        phone: phone,
        otp: otp
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Save JWT token
      await AsyncStorage.setItem('token', data.data.access_token);
      // Navigate to home screen
    } else {
      console.error('Invalid OTP:', data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
};
```

---

## Changelog

### Version 1.0 (2026-01-19)
- Initial release
- Send OTP endpoint
- Verify OTP endpoint
- Resend OTP endpoint
- Rate limiting implementation
- Twilio integration

---

## Support

For issues or questions:
- Check Twilio documentation: https://www.twilio.com/docs
- Review Laravel logs: `storage/logs/laravel.log`
- Contact support: support@bmv.com

---

**Documentation Version:** 1.0  
**Last Updated:** 2026-01-19  
**Maintained By:** BMV Development Team
