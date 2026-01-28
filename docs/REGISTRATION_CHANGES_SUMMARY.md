# Registration API Changes Summary

## Overview
The registration API has been completely refactored to use a **mobile-only registration flow** with OTP verification. The old email/password registration has been removed.

---

## What Changed

### 1. **Registration Endpoint** - `POST /api/v1/auth/register`

#### Before (Old Flow):
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
- Required: name, email, password, password_confirmation, phone
- Created account immediately
- Returned JWT token

#### After (New Flow):
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```
- Required: Only phone and country_code
- Sends OTP via SMS
- Does NOT create account yet
- Returns OTP expiration info

---

### 2. **New Endpoint Added** - `POST /api/v1/auth/verify-registration-otp`

This is a completely new endpoint that completes the registration:

```json
{
    "phone": "1234567890",
    "otp": "123456"
}
```

**What it does:**
1. Verifies the OTP
2. Auto-generates unique username (e.g., `user_34567890`)
3. Sets name = username
4. Activates the account
5. Returns JWT token for auto-login

---

## Files Modified

### 1. **AuthController.php**
- **Location:** `app/Http/Controllers/Api/AuthController.php`
- **Changes:**
  - ✅ Replaced `register()` method - now sends OTP instead of creating account
  - ✅ Added `verifyRegistrationOTP()` method - completes registration after OTP verification
  - ✅ Added `generateUniqueUsernameFromPhone()` helper method
  - ✅ Added `generateCanonicalFromPhone()` helper method

### 2. **api.php (Routes)**
- **Location:** `routes/api.php`
- **Changes:**
  - ✅ Added new route: `POST /api/v1/auth/verify-registration-otp`

### 3. **Database Migration**
- **Location:** `database/migrations/2026_01_28_142547_make_name_email_nullable_in_customers_table.php`
- **Changes:**
  - ✅ Made `name` field nullable
  - ✅ Made `email` field nullable
  - ⚠️ **Note:** Migration needs to be run: `php artisan migrate`

### 4. **Documentation**
- **Location:** `docs/API_REGISTRATION_FLOW_NEW.md`
- **Changes:**
  - ✅ Created comprehensive documentation for new registration flow

---

## Database Schema Changes

### Customers Table
- `name` - Now **nullable** (auto-generated from username)
- `email` - Now **nullable** (can be added later)
- `username` - Auto-generated from phone number
- `canonical` - Auto-generated with phone prefix

---

## New Registration Flow

```
┌─────────────────────────────────────────┐
│  Step 1: POST /api/v1/auth/register     │
│  Input: { phone, country_code }         │
│  Output: OTP sent via SMS               │
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

## Auto-Generated Fields

When OTP is verified, the system automatically generates:

1. **Username:** `user_XXXXXXXX` (last 8 digits of phone)
   - Example: Phone `9876543210` → `user_76543210`
   - If exists, adds counter: `user_76543210_1`

2. **Canonical:** `ph_XXXXXXXXXX_XXXXXX`
   - Example: `ph_9876543210_a1b2c3`

3. **Name:** Same as username
   - Example: `user_76543210`
   - Customer can update later

---

## Breaking Changes

### ⚠️ IMPORTANT: Old Registration API No Longer Works

The old registration endpoint still exists at the same URL but now has **completely different behavior**:

**Old Behavior:**
- Accepted: name, email, password, etc.
- Created account immediately

**New Behavior:**
- Accepts: Only phone and country_code
- Sends OTP (does not create account)

### Migration Path for Existing Integrations

If you have mobile apps or frontend code using the old registration API:

1. **Update the registration form** to only collect:
   - Phone number
   - Country code

2. **Add OTP verification screen** to:
   - Display OTP input field
   - Call `/verify-registration-otp` endpoint

3. **Remove from registration form:**
   - Name field
   - Email field
   - Password field
   - Password confirmation field
   - Gender field
   - Date of birth field

4. **Move optional fields to profile update:**
   - After registration, allow users to update their profile
   - Use existing profile update APIs

---

## API Response Changes

### Register Endpoint Response

**Before:**
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": { ... },
        "access_token": "...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**After:**
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

---

## Testing the New Flow

### Test Scenario 1: Successful Registration

```bash
# Step 1: Send OTP
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"phone": "9876543210", "country_code": "+91"}'

# Response: OTP sent (check SMS or logs in debug mode)

# Step 2: Verify OTP
curl -X POST http://localhost/api/v1/auth/verify-registration-otp \
  -H "Content-Type: application/json" \
  -d '{"phone": "9876543210", "otp": "123456"}'

# Response: Account created + JWT token
```

### Test Scenario 2: Phone Already Registered

```bash
# Attempt to register with existing phone
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"phone": "9876543210", "country_code": "+91"}'

# Response: Error - "This phone number is already registered. Please login instead."
```

---

## Rollback Instructions

If you need to rollback to the old registration flow:

1. **Revert AuthController.php:**
   ```bash
   git checkout HEAD~1 app/Http/Controllers/Api/AuthController.php
   ```

2. **Revert routes/api.php:**
   ```bash
   git checkout HEAD~1 routes/api.php
   ```

3. **Rollback migration:**
   ```bash
   php artisan migrate:rollback --step=1
   ```

---

## Next Steps

1. ✅ **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. ✅ **Test the API:**
   - Use Postman or cURL to test both endpoints
   - Verify OTP is sent via SMS
   - Confirm account is created after OTP verification

3. ✅ **Update Frontend/Mobile App:**
   - Modify registration screens
   - Add OTP verification screen
   - Update API calls

4. ✅ **Update Documentation:**
   - Inform team about changes
   - Update API documentation
   - Update user guides

---

## Support & Troubleshooting

### Common Issues

**1. OTP not received:**
- Check Twilio configuration in `.env`
- Verify phone number format
- Check logs: `storage/logs/laravel.log`
- Enable debug mode to see OTP in response

**2. Migration fails:**
- Check database connection
- Ensure no active connections to customers table
- Run: `php artisan migrate:status`

**3. Username conflicts:**
- System automatically handles conflicts by adding counter
- Example: `user_12345678_1`, `user_12345678_2`

---

## Questions?

Contact the development team or check:
- Full documentation: `docs/API_REGISTRATION_FLOW_NEW.md`
- Old flow reference: `docs/API_AUTH_FLOW_CUSTOMERS.md`

---

**Date:** January 28, 2026  
**Author:** Development Team  
**Version:** 2.0
