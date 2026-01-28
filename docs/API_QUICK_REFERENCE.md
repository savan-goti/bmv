# Quick API Reference - New Registration Flow

## Registration Endpoints

### 1. Register (Send OTP)
**Endpoint:** `POST /api/v1/auth/register`

**Request:**
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "OTP sent successfully to your phone",
    "data": {
        "phone": "9876543210",
        "expires_in_minutes": 10,
        "message": "Please verify OTP to complete registration"
    }
}
```

---

### 2. Verify Registration OTP
**Endpoint:** `POST /api/v1/auth/verify-registration-otp`

**Request:**
```json
{
    "phone": "9876543210",
    "otp": "123456"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": {
            "id": 1,
            "username": "user_76543210",
            "name": "user_76543210",
            "phone": "9876543210",
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

### 3. Resend Registration OTP
**Endpoint:** `POST /api/v1/auth/resend-registration-otp`

**Request:**
```json
{
    "phone": "9876543210",
    "country_code": "+91"
}
```

**Success Response (200):**
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

**Error Response - Rate Limited (429):**
```json
{
    "success": false,
    "message": "Please wait 45 seconds before requesting a new OTP"
}
```

---

## Login Endpoints (Existing - No Changes)

### 1. Login with Password
**Endpoint:** `POST /api/v1/auth/login`

**Request:**
```json
{
    "type": "phone",
    "identifier": "9876543210",
    "password": "password123"
}
```

---

### 2. Send OTP for Login
**Endpoint:** `POST /api/v1/auth/send-otp`

**Request:**
```json
{
    "type": "phone",
    "identifier": "9876543210",
    "country_code": "+91"
}
```

---

### 3. Verify OTP for Login
**Endpoint:** `POST /api/v1/auth/verify-otp`

**Request:**
```json
{
    "type": "phone",
    "identifier": "9876543210",
    "otp": "123456"
}
```

---

## Complete User Journey

```
NEW USER REGISTRATION:
1. POST /api/v1/auth/register → Send OTP
2. POST /api/v1/auth/verify-registration-otp → Complete registration + Auto login
   (Optional: POST /api/v1/auth/resend-registration-otp → Resend OTP if not received)

EXISTING USER LOGIN (Option 1 - OTP):
1. POST /api/v1/auth/send-otp → Send OTP
2. POST /api/v1/auth/verify-otp → Login

EXISTING USER LOGIN (Option 2 - Password):
1. POST /api/v1/auth/login → Login with password
```

---

## Error Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created (Registration successful) |
| 400 | Bad Request (Invalid OTP, Expired OTP) |
| 404 | Not Found (Phone not registered) |
| 422 | Validation Error / Already Registered |
| 429 | Too Many Requests (Rate Limited) |
| 500 | Server Error |

---

## Postman Collection

Import this JSON into Postman:

```json
{
    "info": {
        "name": "BMV Registration API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "1. Register (Send OTP)",
            "request": {
                "method": "POST",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"phone\": \"9876543210\",\n    \"country_code\": \"+91\"\n}"
                },
                "url": {
                    "raw": "http://localhost/api/v1/auth/register",
                    "protocol": "http",
                    "host": ["localhost"],
                    "path": ["api", "v1", "auth", "register"]
                }
            }
        },
        {
            "name": "2. Verify Registration OTP",
            "request": {
                "method": "POST",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"phone\": \"9876543210\",\n    \"otp\": \"123456\"\n}"
                },
                "url": {
                    "raw": "http://localhost/api/v1/auth/verify-registration-otp",
                    "protocol": "http",
                    "host": ["localhost"],
                    "path": ["api", "v1", "auth", "verify-registration-otp"]
                }
            }
        },
        {
            "name": "3. Resend Registration OTP",
            "request": {
                "method": "POST",
                "header": [
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"phone\": \"9876543210\",\n    \"country_code\": \"+91\"\n}"
                },
                "url": {
                    "raw": "http://localhost/api/v1/auth/resend-registration-otp",
                    "protocol": "http",
                    "host": ["localhost"],
                    "path": ["api", "v1", "auth", "resend-registration-otp"]
                }
            }
        }
    ]
}
```

---

**Last Updated:** January 28, 2026
