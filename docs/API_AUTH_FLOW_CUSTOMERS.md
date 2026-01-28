# API Authentication Flow - Customers Table

## Overview
The API authentication system uses the `customers` database table for all customer-related authentication operations. This document outlines the complete authentication flow and configuration.

---

## Database Configuration

### Table: `customers`

**Migration File:** `2025_11_24_151013_create_customers_table.php`

#### Schema Structure:

```sql
CREATE TABLE customers (
    -- Primary Key
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Profile Information
    profile_image VARCHAR(255) NULL,
    canonical VARCHAR(255) NULL,
    username VARCHAR(255) UNIQUE NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(255) UNIQUE NULL,
    country_code VARCHAR(255) NULL,
    
    -- OTP Fields
    phone_otp VARCHAR(255) NULL,
    otp_expired_at DATETIME NULL,
    email_otp VARCHAR(255) NULL,
    email_otp_expired_at DATETIME NULL,
    phone_validate BOOLEAN DEFAULT FALSE,
    
    -- Personal Information
    gender ENUM('male', 'female', 'other') NULL,
    dob DATE NULL,
    
    -- Address & Location
    address TEXT NULL,
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    city VARCHAR(255) NULL,
    state VARCHAR(255) NULL,
    country VARCHAR(255) NULL,
    pincode VARCHAR(255) NULL,
    social_links JSON NULL,
    
    -- Status & Security
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'active',
    password VARCHAR(255) NOT NULL,
    last_login_at DATETIME NULL,
    last_login_ip VARCHAR(50) NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    email_verified_at DATETIME NULL,
    remember_token VARCHAR(100) NULL,
    
    -- Timestamps
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

---

## Laravel Configuration

### 1. Auth Configuration (`config/auth.php`)

#### Guards:
```php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'customers',
    ],
],
```

#### Providers:
```php
'providers' => [
    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer::class,
    ],
],
```

#### Password Reset:
```php
'passwords' => [
    'customers' => [
        'provider' => 'customers',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

### 2. Customer Model (`app/Models/Customer.php`)

```php
class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'profile_image', 'canonical', 'username', 'name', 'email',
        'phone', 'country_code', 'phone_otp', 'otp_expired_at',
        'phone_validate', 'email_otp', 'email_otp_expired_at',
        'gender', 'dob', 'address', 'latitude', 'longitude',
        'city', 'state', 'country', 'pincode', 'social_links',
        'status', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'phone_otp', 'email_otp',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'dob' => 'date',
            'otp_expired_at' => 'datetime',
            'email_otp_expired_at' => 'datetime',
            'phone_validate' => 'boolean',
            'social_links' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }
}
```

---

## API Endpoints

### Base URL: `/api/v1/auth`

### 1. **Register** - `POST /api/v1/auth/register`

Creates a new customer account in the `customers` table.

**Request:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "username": "john_doe",
            "canonical": "johndoe_a1b2c3d4",
            "phone": "1234567890",
            "country_code": "+91",
            "status": "active",
            "created_at": "2026-01-28T14:00:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600,
        "refresh_expires_in": 1209600
    }
}
```

**Database Operation:**
- Inserts new record into `customers` table
- Auto-generates `username` from name
- Auto-generates unique `canonical` identifier
- Sets `status` to 'active'
- Hashes password automatically
- Sets `phone_validate` to false

---

### 2. **Login** - `POST /api/v1/auth/login`

Authenticates customer using email or phone with password.

**Request (Email Login):**
```json
{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
}
```

**Request (Phone Login):**
```json
{
    "type": "phone",
    "identifier": "1234567890",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "status": "active"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Database Operation:**
- Queries `customers` table by email or phone
- Verifies password hash
- Checks if `status` = 'active'
- Generates JWT token

---

### 3. **Send OTP** - `POST /api/v1/auth/send-otp`

Sends OTP to customer's email or phone for passwordless login.

**Request (Email OTP):**
```json
{
    "type": "email",
    "identifier": "john@example.com"
}
```

**Request (Phone OTP):**
```json
{
    "type": "phone",
    "identifier": "1234567890",
    "country_code": "+91"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "OTP sent successfully to your email",
    "data": {
        "email": "john@example.com",
        "expires_in_minutes": 10
    }
}
```

**Database Operation:**
- Finds customer in `customers` table by email or phone
- Generates 6-digit OTP
- For email: Updates `email_otp` and `email_otp_expired_at` columns
- For phone: Updates `phone_otp` and `otp_expired_at` columns
- Sets expiration to 10 minutes from now

---

### 4. **Verify OTP** - `POST /api/v1/auth/verify-otp`

Verifies OTP and logs in the customer.

**Request:**
```json
{
    "type": "email",
    "identifier": "john@example.com",
    "otp": "123456"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "OTP verified successfully. You are now logged in.",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Database Operation:**
- Finds customer in `customers` table
- Verifies OTP matches stored value
- Checks OTP hasn't expired
- For email: Clears `email_otp` and `email_otp_expired_at`
- For phone: Clears `phone_otp` and `otp_expired_at`, sets `phone_validate` to true
- Generates JWT token

---

### 5. **Resend OTP** - `POST /api/v1/auth/resend-otp`

Resends OTP with rate limiting (1 minute cooldown).

**Request:**
```json
{
    "type": "email",
    "identifier": "john@example.com"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "OTP resent successfully to your email",
    "data": {
        "email": "john@example.com",
        "expires_in_minutes": 10
    }
}
```

**Database Operation:**
- Finds customer in `customers` table
- Checks rate limiting (prevents spam)
- Generates new 6-digit OTP
- Updates OTP and expiration fields
- Sends OTP via email or SMS

---

### 6. **Get Profile** - `GET /api/v1/auth/profile`

Retrieves authenticated customer's profile.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "User profile",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "username": "john_doe",
        "phone": "1234567890",
        "status": "active",
        "created_at": "2026-01-28T14:00:00.000000Z"
    }
}
```

**Database Operation:**
- Retrieves customer from `customers` table using JWT token
- Returns customer data (excludes password, OTP fields)

---

### 7. **Logout** - `POST /api/v1/auth/logout`

Invalidates the current JWT token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

**Database Operation:**
- Invalidates JWT token (no database changes)

---

### 8. **Refresh Token** - `POST /api/v1/auth/refresh`

Refreshes the JWT token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {...},
        "access_token": "new_token_here",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Database Operation:**
- Validates existing token
- Generates new JWT token
- Retrieves customer from `customers` table

---

## Authentication Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    CUSTOMER REGISTRATION                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    POST /api/v1/auth/register
                              │
                              ▼
                    Validate Input Data
                              │
                              ▼
                Generate username & canonical
                              │
                              ▼
            INSERT INTO customers (name, email, password,
            phone, status='active', phone_validate=false)
                              │
                              ▼
                    Generate JWT Token
                              │
                              ▼
                    Return Token + Customer Data


┌─────────────────────────────────────────────────────────────┐
│                  PASSWORD-BASED LOGIN                        │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    POST /api/v1/auth/login
                              │
                              ▼
            SELECT * FROM customers WHERE
            email = ? OR phone = ?
                              │
                              ▼
                    Verify Password Hash
                              │
                              ▼
                Check status = 'active'
                              │
                              ▼
                    Generate JWT Token
                              │
                              ▼
                    Return Token + Customer Data


┌─────────────────────────────────────────────────────────────┐
│                      OTP-BASED LOGIN                         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    POST /api/v1/auth/send-otp
                              │
                              ▼
            SELECT * FROM customers WHERE
            email = ? OR phone = ?
                              │
                              ▼
                    Generate 6-digit OTP
                              │
                              ▼
            UPDATE customers SET
            email_otp = ?, email_otp_expired_at = ?
            OR phone_otp = ?, otp_expired_at = ?
                              │
                              ▼
                Send OTP via Email/SMS
                              │
                              ▼
                    POST /api/v1/auth/verify-otp
                              │
                              ▼
            SELECT * FROM customers WHERE
            email = ? OR phone = ?
                              │
                              ▼
            Verify OTP & Check Expiration
                              │
                              ▼
            UPDATE customers SET
            email_otp = NULL, email_otp_expired_at = NULL
            OR phone_otp = NULL, otp_expired_at = NULL,
            phone_validate = true
                              │
                              ▼
                    Generate JWT Token
                              │
                              ▼
                    Return Token + Customer Data
```

---

## Security Features

### 1. **Password Hashing**
- Passwords are automatically hashed using Laravel's `hashed` cast
- Uses bcrypt algorithm
- Stored in `customers.password` column

### 2. **JWT Authentication**
- Stateless authentication using JSON Web Tokens
- Token expiration: 60 minutes (configurable)
- Refresh token expiration: 14 days (configurable)

### 3. **OTP Security**
- 6-digit random OTP
- 10-minute expiration
- Rate limiting: 1-minute cooldown between requests
- Separate OTP fields for email and phone

### 4. **Account Status**
- ENUM values: 'active', 'inactive', 'blocked'
- Login blocked for non-active accounts
- Checked on every login attempt

### 5. **Soft Deletes**
- Customers table uses soft deletes
- Deleted accounts remain in database with `deleted_at` timestamp
- Can be restored if needed

---

## Common Issues & Solutions

### Issue 1: Status Field Mismatch
**Problem:** Registration sets `status = 1` but database expects 'active'
**Solution:** ✅ Fixed - Now uses `status = 'active'`

### Issue 2: OTP Not Received
**Solution:** Check logs, verify Twilio/Email configuration, use debug mode to see OTP in response

### Issue 3: Token Expired
**Solution:** Use refresh token endpoint to get new access token

### Issue 4: Phone Already Registered
**Solution:** Use login or password reset instead of registration

---

## Environment Variables

```env
# JWT Configuration
JWT_SECRET=your_secret_key
JWT_TTL=60
JWT_REFRESH_TTL=20160

# Twilio Configuration (for Phone OTP)
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_token
TWILIO_PHONE_NUMBER=your_twilio_phone

# Mail Configuration (for Email OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## Testing

### Test Customer Registration
```bash
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "1234567890",
    "country_code": "+91"
  }'
```

### Test Login
```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "test@example.com",
    "password": "password123"
  }'
```

### Test OTP Flow
```bash
# Send OTP
curl -X POST http://localhost/api/v1/auth/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "test@example.com"
  }'

# Verify OTP
curl -X POST http://localhost/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "test@example.com",
    "otp": "123456"
  }'
```

---

## Database Queries Reference

### Find Customer by Email
```sql
SELECT * FROM customers WHERE email = 'john@example.com' AND deleted_at IS NULL;
```

### Find Customer by Phone
```sql
SELECT * FROM customers WHERE phone = '1234567890' AND deleted_at IS NULL;
```

### Check Active Customers
```sql
SELECT * FROM customers WHERE status = 'active' AND deleted_at IS NULL;
```

### Find Customers with Pending OTP
```sql
SELECT * FROM customers 
WHERE email_otp IS NOT NULL 
  AND email_otp_expired_at > NOW()
  AND deleted_at IS NULL;
```

### Update Customer Status
```sql
UPDATE customers SET status = 'inactive' WHERE id = 1;
```

---

## Conclusion

The API authentication system is fully configured to use the `customers` database table. All authentication operations (registration, login, OTP verification) interact directly with this table. The system supports:

- ✅ Email/Password authentication
- ✅ Phone/Password authentication  
- ✅ Email OTP authentication
- ✅ Phone OTP authentication
- ✅ JWT token management
- ✅ Account status management
- ✅ Soft deletes
- ✅ Rate limiting

**Last Updated:** January 28, 2026
**Version:** 1.0
