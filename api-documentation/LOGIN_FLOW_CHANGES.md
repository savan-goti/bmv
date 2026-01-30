# Login API Flow Changes - Summary

## Overview
The authentication system has been updated from a **password-based login** to an **OTP-based login** flow. This change enhances security and provides a passwordless authentication experience for customers.

---

## What Changed

### ❌ **REMOVED APIs**
The following endpoints have been **removed**:
1. `POST /auth/send-otp` - Generic OTP sending endpoint
2. `POST /auth/verify-otp` - Generic OTP verification endpoint  
3. `POST /auth/resend-otp` - Generic OTP resending endpoint

### ❌ **MODIFIED APIs**
1. `POST /auth/login` - **Changed from password-based to OTP-based**
   - **Before**: Required `type`, `identifier`, and `password`
   - **After**: Only requires `type` and `identifier` (sends OTP instead of authenticating)

---

## ✅ New Login Flow

### **Step 1: Request Login OTP**
**Endpoint**: `POST /auth/login`

**Purpose**: Send OTP to customer's email or phone

**Request (Email)**:
```json
{
  "type": "email",
  "identifier": "john@example.com"
}
```

**Request (Phone)**:
```json
{
  "type": "phone",
  "identifier": "1234567890",
  "country_code": "+91"
}
```

**Response**:
```json
{
  "success": true,
  "message": "OTP sent successfully to your email",
  "data": {
    "email": "john@example.com",
    "expires_in_minutes": 10,
    "message": "Please verify OTP to login"
  }
}
```

---

### **Step 2: Verify OTP and Login**
**Endpoint**: `POST /auth/verify-login-otp` *(NEW)*

**Purpose**: Verify OTP and complete login

**Request (Email)**:
```json
{
  "type": "email",
  "identifier": "john@example.com",
  "otp": "123456"
}
```

**Request (Phone)**:
```json
{
  "type": "phone",
  "identifier": "1234567890",
  "otp": "123456"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Login successful! Welcome back.",
  "data": {
    "customer": {
      "id": 1,
      "username": "johndoe",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "phone_validate": true,
      "status": "active"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### **Step 3: Resend OTP (if needed)**
**Endpoint**: `POST /auth/resend-login-otp` *(NEW)*

**Purpose**: Resend OTP if the customer didn't receive it or it expired

**Request (Email)**:
```json
{
  "type": "email",
  "identifier": "john@example.com"
}
```

**Request (Phone)**:
```json
{
  "type": "phone",
  "identifier": "1234567890",
  "country_code": "+91"
}
```

**Response**:
```json
{
  "success": true,
  "message": "OTP resent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 10,
    "message": "Please verify OTP to login"
  }
}
```

**Rate Limiting**: If requested too soon, returns:
```json
{
  "success": false,
  "message": "Please wait 45 seconds before requesting a new OTP"
}
```

---

## Registration Flow (Unchanged Structure)

The registration flow remains similar but uses dedicated endpoints:

1. `POST /auth/register` - Send OTP to phone
2. `POST /auth/verify-registration-otp` - Verify OTP and complete registration
3. `POST /auth/resend-registration-otp` - Resend registration OTP

---

## Key Features

### 🔒 **Security**
- No passwords transmitted during login
- OTP expires after 10 minutes
- Rate limiting prevents OTP spam (1-minute cooldown)
- Account status validation (must be active)

### 📧 **Multi-Channel Support**
- Login via **email** OR **phone**
- OTP sent via:
  - **Email**: Using Laravel Mail
  - **SMS**: Using Twilio service

### 🛡️ **Error Handling**
- Clear error messages for:
  - Invalid credentials
  - Expired OTP
  - Inactive accounts
  - Rate limiting
  - Missing OTP

### 🔄 **Development Mode**
- When `APP_DEBUG=true`:
  - OTP included in response if SMS/Email fails
  - Helpful for testing without actual SMS/Email delivery

---

## Database Fields Used

### Customer Model Fields:
- **Email OTP**: `email_otp`, `email_otp_expired_at`
- **Phone OTP**: `phone_otp`, `otp_expired_at`
- **Validation**: `phone_validate`, `status`

---

## Migration Guide

### For Frontend/Mobile Apps:

#### **Old Login Flow**:
```javascript
// Step 1: Login with password
POST /auth/login
{
  "type": "email",
  "identifier": "john@example.com",
  "password": "password123"
}
// Response: JWT token
```

#### **New Login Flow**:
```javascript
// Step 1: Request OTP
POST /auth/login
{
  "type": "email",
  "identifier": "john@example.com"
}
// Response: OTP sent confirmation

// Step 2: Verify OTP
POST /auth/verify-login-otp
{
  "type": "email",
  "identifier": "john@example.com",
  "otp": "123456"
}
// Response: JWT token
```

---

## Testing

### Email Login Test:
```bash
# Step 1: Request OTP
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "test@example.com"
  }'

# Step 2: Verify OTP
curl -X POST http://localhost/api/v1/auth/verify-login-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "test@example.com",
    "otp": "123456"
  }'
```

### Phone Login Test:
```bash
# Step 1: Request OTP
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "1234567890",
    "country_code": "+91"
  }'

# Step 2: Verify OTP
curl -X POST http://localhost/api/v1/auth/verify-login-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "phone",
    "identifier": "1234567890",
    "otp": "123456"
  }'
```

---

## Files Modified

### Routes:
- `routes/api.php` - Updated authentication routes

### Controllers:
- `app/Http/Controllers/Api/AuthController.php`
  - Replaced `login()` method (password → OTP)
  - Removed `sendOTP()`, `verifyOTP()`, `resendOTP()` methods
  - Added `verifyLoginOTP()` method
  - Added `resendLoginOTP()` method

### Documentation:
- `api-documentation/API_REFERENCE.md` - Updated with new flow

---

## Benefits of OTP-Based Login

1. **Enhanced Security**: No password storage or transmission
2. **Better UX**: Passwordless authentication
3. **Reduced Support**: No "forgot password" issues
4. **Multi-Factor**: Inherently 2FA (something you know + something you have)
5. **Flexibility**: Login with email OR phone

---

## Notes

- OTP is **6 digits** and expires in **10 minutes**
- Rate limiting: **1 minute** between OTP requests
- Both email and phone login supported
- Customer must have `status = 'active'` to login
- In debug mode, OTP is returned in response for testing

---

**Date**: January 30, 2026  
**Version**: 2.0  
**Author**: BMV Development Team
