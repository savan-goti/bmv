# API Registration Flow - Mobile Number Only

## Overview
The new registration system uses **mobile number and OTP verification only**. No email or password is required during registration. The system automatically generates a unique username for each customer.

---

## Registration Flow

### Flow Diagram
```
┌─────────────────────────────────────────────────────────────┐
│                  NEW REGISTRATION FLOW                       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    1. Enter Mobile Number
                              │
                              ▼
                POST /api/v1/auth/register
                { phone, country_code }
                              │
                              ▼
                Check if phone already registered
                              │
                              ▼
                  Create temporary customer record
                  (status = 'inactive')
                              │
                              ▼
                    Generate 6-digit OTP
                              │
                              ▼
                  Send OTP via SMS (Twilio)
                              │
                              ▼
                    2. Enter OTP Code
                              │
                              ▼
          POST /api/v1/auth/verify-registration-otp
                { phone, otp }
                              │
                              ▼
                    Verify OTP & Expiration
                              │
                              ▼
              Auto-generate unique username
              (format: user_XXXXXXXX)
                              │
                              ▼
              Update customer record:
              - username = auto-generated
              - name = username
              - canonical = ph_XXXXXXXXXX_XXXXXX
              - phone_validate = true
              - status = 'active'
              - Clear OTP fields
                              │
                              ▼
                  Generate JWT Token
                              │
                              ▼
            3. Registration Complete + Auto Login
```

---

## API Endpoints

### Base URL: `/api/v1/auth`

---

### 1. **Register (Step 1)** - `POST /api/v1/auth/register`

Initiates registration by sending OTP to the provided mobile number.

#### Request:
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```

#### Request Parameters:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| phone | string | Yes | Mobile number (10-20 digits) |
| country_code | string | Yes | Country code with + (e.g., +91) |

#### Success Response (200):
```json
{
    "success": true,
    "message": "OTP sent successfully to your phone",
    "data": {
        "phone": "1234567890",
        "expires_in_minutes": 10,
        "message": "Please verify OTP to complete registration"
    }
}
```

#### Error Responses:

**Phone Already Registered (422):**
```json
{
    "success": false,
    "message": "This phone number is already registered. Please login instead."
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "phone": ["Phone number is required"],
        "country_code": ["Country code is required"]
    }
}
```

**SMS Failed (500):**
```json
{
    "success": false,
    "message": "Failed to send OTP. Please try again."
}
```

#### Debug Mode Response:
When `APP_DEBUG=true`, the OTP is included in the response for testing:
```json
{
    "success": true,
    "message": "Registration initiated (SMS failed, check logs)",
    "data": {
        "phone": "1234567890",
        "expires_in_minutes": 10,
        "otp_for_testing": "123456",
        "message": "Please verify OTP to complete registration"
    }
}
```

---

### 2. **Verify Registration OTP (Step 2)** - `POST /api/v1/auth/verify-registration-otp`

Verifies the OTP and completes customer registration with auto-generated username.

#### Request:
```json
{
    "phone": "1234567890",
    "otp": "123456"
}
```

#### Request Parameters:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| phone | string | Yes | Mobile number used in registration |
| otp | string | Yes | 6-digit OTP received via SMS |

#### Success Response (201):
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": {
            "id": 1,
            "username": "user_34567890",
            "name": "user_34567890",
            "canonical": "ph_1234567890_a1b2c3",
            "phone": "1234567890",
            "country_code": "+91",
            "phone_validate": true,
            "status": "active",
            "profile_image": null,
            "email": null,
            "gender": null,
            "dob": null,
            "created_at": "2026-01-28T14:30:00.000000Z",
            "updated_at": "2026-01-28T14:30:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

#### Error Responses:

**Phone Not Found (404):**
```json
{
    "success": false,
    "message": "Phone number not found. Please register first."
}
```

**Already Registered (422):**
```json
{
    "success": false,
    "message": "This phone number is already registered. Please login instead."
}
```

**No OTP Found (400):**
```json
{
    "success": false,
    "message": "No OTP found. Please request a new OTP."
}
```

**OTP Expired (400):**
```json
{
    "success": false,
    "message": "OTP has expired. Please request a new OTP."
}
```

**Invalid OTP (400):**
```json
{
    "success": false,
    "message": "Invalid OTP. Please try again."
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "phone": ["Phone number is required"],
        "otp": ["OTP must be 6 digits"]
    }
}
```

---

## Database Changes

### Customer Record Creation

#### Step 1 (After Register API):
Creates a temporary customer record:
```sql
INSERT INTO customers (
    phone,
    country_code,
    phone_otp,
    otp_expired_at,
    phone_validate,
    status,
    password,
    created_at,
    updated_at
) VALUES (
    '1234567890',
    '+91',
    '123456',
    '2026-01-28 14:40:00',
    false,
    'inactive',
    '$2y$10$...',  -- Temporary hashed password
    NOW(),
    NOW()
);
```

#### Step 2 (After OTP Verification):
Updates the customer record:
```sql
UPDATE customers SET
    username = 'user_34567890',
    name = 'user_34567890',
    canonical = 'ph_1234567890_a1b2c3',
    phone_validate = true,
    status = 'active',
    phone_otp = NULL,
    otp_expired_at = NULL,
    updated_at = NOW()
WHERE phone = '1234567890';
```

---

## Auto-Generated Fields

### 1. Username
- **Format:** `user_XXXXXXXX` (last 8 digits of phone)
- **Example:** Phone `9876543210` → Username `user_76543210`
- **Uniqueness:** If username exists, appends counter: `user_76543210_1`, `user_76543210_2`, etc.

### 2. Canonical
- **Format:** `ph_XXXXXXXXXX_XXXXXX` (last 10 digits + random 6 chars)
- **Example:** `ph_9876543210_a1b2c3`
- **Purpose:** Unique identifier for internal use

### 3. Name
- **Value:** Same as auto-generated username
- **Example:** `user_76543210`
- **Note:** Customer can update this later via profile update API

---

## Security Features

### 1. OTP Security
- **Length:** 6 digits
- **Expiration:** 10 minutes
- **Rate Limiting:** Prevents spam (1-minute cooldown)
- **One-time Use:** OTP is cleared after successful verification

### 2. Phone Validation
- **Uniqueness:** Phone numbers must be unique
- **Format:** Accepts 10-20 digit numbers
- **Country Code:** Required for international support

### 3. Account Status
- **Initial Status:** `inactive` (during OTP verification)
- **After Verification:** `active`
- **Login Restriction:** Only active accounts can login

### 4. JWT Token
- **Auto-Login:** Token generated immediately after registration
- **Expiration:** 60 minutes (configurable)
- **Type:** Bearer token

---

## Testing Examples

### Using cURL

#### Step 1: Register
```bash
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9876543210",
    "country_code": "+91"
  }'
```

#### Step 2: Verify OTP
```bash
curl -X POST http://localhost/api/v1/auth/verify-registration-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9876543210",
    "otp": "123456"
  }'
```

### Using Postman

#### Collection Structure:
```
BMV API
├── Auth
│   ├── Register (Step 1)
│   │   POST /api/v1/auth/register
│   │   Body: { "phone": "9876543210", "country_code": "+91" }
│   │
│   └── Verify Registration OTP (Step 2)
│       POST /api/v1/auth/verify-registration-otp
│       Body: { "phone": "9876543210", "otp": "123456" }
```

---

## Migration Required

Run the following migration to make `name` and `email` nullable:

```bash
php artisan migrate
```

This runs the migration: `2026_01_28_142547_make_name_email_nullable_in_customers_table.php`

---

## Comparison: Old vs New Flow

### Old Registration Flow (Removed)
```
1. Enter: name, email, password, password_confirmation, phone, country_code
2. Submit registration
3. Account created + logged in
```

### New Registration Flow (Current)
```
1. Enter: phone, country_code
2. Receive OTP via SMS
3. Enter OTP
4. Account created + auto-login
   - Username auto-generated
   - Name = username (can update later)
   - No password required
```

---

## Benefits of New Flow

1. **Simplified Registration:** Only phone number required
2. **Faster Onboarding:** 2-step process instead of complex form
3. **Better Security:** OTP verification ensures phone ownership
4. **No Password Hassle:** Passwordless authentication
5. **Auto-Login:** Immediate access after verification
6. **Mobile-First:** Optimized for mobile users

---

## Next Steps for Customers

After successful registration, customers can:

1. **Update Profile:** Add name, email, gender, DOB, etc.
2. **Add Profile Image:** Upload profile picture
3. **Set Location:** Add address and location details
4. **Browse Products:** Start shopping immediately

---

## Environment Variables

```env
# Twilio Configuration (for SMS OTP)
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_token
TWILIO_PHONE_NUMBER=your_twilio_phone

# JWT Configuration
JWT_SECRET=your_secret_key
JWT_TTL=60

# App Configuration
APP_DEBUG=true  # Set to false in production
```

---

## Error Handling

All API responses follow a consistent format:

### Success Response:
```json
{
    "success": true,
    "message": "Success message",
    "data": { ... }
}
```

### Error Response:
```json
{
    "success": false,
    "message": "Error message",
    "errors": { ... }  // Optional validation errors
}
```

---

## Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Enable debug mode: `APP_DEBUG=true`
- Verify Twilio configuration
- Check database connection

---

**Last Updated:** January 28, 2026  
**Version:** 2.0  
**Flow Type:** Mobile-Only Registration with OTP
