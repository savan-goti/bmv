# Login API Enhancement - Type Field (Email/Phone)

## Summary
Added support for login type field to allow customers to login using either email or phone number.

## Changes Made

### 1. AuthController.php
**File:** `app/Http/Controllers/Api/AuthController.php`

**Modified Method:** `login()`

**Key Changes:**
- Added `type` field validation (required, must be 'email' or 'phone')
- Added `identifier` field to accept either email or phone number
- Dynamic credential building based on login type
- Enhanced error messages to specify the type used
- Email format validation when type is 'email'
- Phone number sanitization when type is 'phone' (removes spaces and special characters except +)

**New Request Format:**
```json
{
    "type": "email",           // or "phone"
    "identifier": "john@example.com",  // or "1234567890"
    "password": "password123"
}
```

**Validation Rules:**
- `type`: required, must be either 'email' or 'phone'
- `identifier`: required, string
- `password`: required, string, minimum 8 characters

**Additional Validations:**
- If type is 'email': validates email format using PHP's FILTER_VALIDATE_EMAIL
- If type is 'phone': sanitizes phone number by removing spaces and special characters (keeps only digits and +)

### 2. API Documentation
**File:** `docs/api/CUSTOMER-API-MAPPING.md`

**Updated Section:** Login Customer (Section 2)

**Changes:**
- Added examples for both email and phone login
- Documented the new `type` and `identifier` parameters
- Added comprehensive error response examples
- Included validation error scenarios
- Added inactive account error response

## Usage Examples

### Email Login
```bash
curl -X POST http://localhost/bmv/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
  }'
```

### Phone Login
```bash
curl -X POST http://localhost/bmv/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "type": "phone",
    "identifier": "1234567890",
    "password": "password123"
  }'
```

## Error Responses

### Validation Error (Missing Type)
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "type": ["Login type is required"]
    }
}
```

### Invalid Email Format
```json
{
    "success": false,
    "message": "Please provide a valid email address"
}
```

### Invalid Credentials
```json
{
    "success": false,
    "message": "Invalid credentials. Please check your email and password."
}
```

### Inactive Account
```json
{
    "success": false,
    "message": "Your account is inactive. Please contact support."
}
```

## Backward Compatibility Note

⚠️ **Breaking Change**: This update changes the login API request format. The old format using just `email` and `password` will no longer work. 

**Old Format (No longer supported):**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**New Format (Required):**
```json
{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
}
```

## Testing Checklist

- [ ] Test email login with valid credentials
- [ ] Test phone login with valid credentials
- [ ] Test with invalid email format
- [ ] Test with invalid phone format
- [ ] Test with missing type field
- [ ] Test with invalid type value (not 'email' or 'phone')
- [ ] Test with missing identifier
- [ ] Test with missing password
- [ ] Test with incorrect credentials
- [ ] Test with inactive account
- [ ] Test phone number with spaces and special characters
- [ ] Test phone number with country code (+91, +1, etc.)

## Database Requirements

Ensure the `customers` table has both `email` and `phone` columns indexed for optimal login performance:

```sql
-- Check if indexes exist
SHOW INDEX FROM customers WHERE Column_name IN ('email', 'phone');

-- Add indexes if needed (usually already present)
ALTER TABLE customers ADD INDEX idx_email (email);
ALTER TABLE customers ADD INDEX idx_phone (phone);
```

## Next Steps

1. Update any mobile/web applications to use the new login format
2. Update API testing tools (Postman, etc.) with new request format
3. Notify frontend developers about the breaking change
4. Consider implementing a transition period with support for both old and new formats if needed
