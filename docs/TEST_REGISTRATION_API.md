# Testing the New Registration API

## Test Case 1: Register with Phone Number

### Request
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9081882806",
    "country_code": "+91"
  }'
```

### Expected Response (Success)
```json
{
    "success": true,
    "message": "OTP sent successfully to your phone",
    "data": {
        "phone": "9081882806",
        "expires_in_minutes": 10,
        "message": "Please verify OTP to complete registration"
    }
}
```

OR (in Debug Mode):
```json
{
    "success": true,
    "message": "Registration initiated (SMS failed, check logs)",
    "data": {
        "phone": "9081882806",
        "expires_in_minutes": 10,
        "otp_for_testing": "123456",
        "message": "Please verify OTP to complete registration"
    }
}
```

---

## Test Case 2: Resend OTP

### Request
```bash
curl -X POST http://localhost:8000/api/v1/auth/resend-registration-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9081882806",
    "country_code": "+91"
  }'
```

### Expected Response (Success)
```json
{
    "success": true,
    "message": "OTP resent successfully to your phone",
    "data": {
        "phone": "9081882806",
        "expires_in_minutes": 10,
        "message": "Please verify OTP to complete registration"
    }
}
```

### Expected Response (Rate Limited)
```json
{
    "success": false,
    "message": "Please wait 45 seconds before requesting a new OTP"
}
```

---

## Test Case 3: Verify OTP

### Request
```bash
curl -X POST http://localhost:8000/api/v1/auth/verify-registration-otp \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9081882806",
    "otp": "123456"
  }'
```

### Expected Response (Success)
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": {
            "id": 1,
            "username": "user_81882806",
            "name": "user_81882806",
            "canonical": "ph_9081882806_abc123",
            "phone": "9081882806",
            "country_code": "+91",
            "phone_validate": true,
            "status": "active",
            "created_at": "2026-01-28T14:48:44.000000Z",
            "updated_at": "2026-01-28T14:50:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

## Troubleshooting

### Issue: "Field 'name' doesn't have a default value"
**Solution:** Run the migration
```bash
php artisan migrate
```

### Issue: "This phone number is already registered"
**Solution:** The phone is already in the database. Try:
1. Use a different phone number, OR
2. Delete the existing record:
```bash
php artisan tinker --execute="Customer::where('phone', '9081882806')->delete();"
```

### Issue: "OTP not sent via SMS"
**Solution:** Check Twilio configuration in `.env` file:
```env
TWILIO_SID=your_sid
TWILIO_AUTH_TOKEN=your_token
TWILIO_PHONE_NUMBER=your_phone
```

---

## Quick Test Script

Save this as `test-registration.sh`:

```bash
#!/bin/bash

echo "Testing Registration API..."
echo ""

# Step 1: Register
echo "Step 1: Sending OTP..."
RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "9081882806",
    "country_code": "+91"
  }')

echo "$RESPONSE" | jq '.'
echo ""

# Extract OTP from response (if in debug mode)
OTP=$(echo "$RESPONSE" | jq -r '.data.otp_for_testing // "123456"')
echo "OTP: $OTP"
echo ""

# Step 2: Verify OTP
echo "Step 2: Verifying OTP..."
curl -s -X POST http://localhost:8000/api/v1/auth/verify-registration-otp \
  -H "Content-Type: application/json" \
  -d "{
    \"phone\": \"9081882806\",
    \"otp\": \"$OTP\"
  }" | jq '.'
```

Run with:
```bash
chmod +x test-registration.sh
./test-registration.sh
```

---

**Status:** ✅ Migration completed successfully  
**Database:** `name` and `email` fields are now nullable  
**Ready to test:** Yes
