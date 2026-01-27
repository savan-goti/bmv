# OTP API Quick Reference

## 📮 Send OTP

### Email OTP
```json
POST /api/customer/send-otp
{
  "type": "email",
  "identifier": "customer@example.com"
}
```

### Phone OTP
```json
POST /api/customer/send-otp
{
  "type": "phone",
  "identifier": "9876543210",
  "country_code": "+91"
}
```

---

## ✅ Verify OTP (Auto-Login)

### Email OTP
```json
POST /api/customer/verify-otp
{
  "type": "email",
  "identifier": "customer@example.com",
  "otp": "123456"
}
```

### Phone OTP
```json
POST /api/customer/verify-otp
{
  "type": "phone",
  "identifier": "9876543210",
  "otp": "123456"
}
```

**Response:** JWT token + customer data

---

## 🔄 Resend OTP

### Email OTP
```json
POST /api/customer/resend-otp
{
  "type": "email",
  "identifier": "customer@example.com"
}
```

### Phone OTP
```json
POST /api/customer/resend-otp
{
  "type": "phone",
  "identifier": "9876543210",
  "country_code": "+91"
}
```

---

## 🔑 Key Points

- **OTP Length:** 6 digits
- **Expiration:** 10 minutes
- **Rate Limit:** 1 minute between requests
- **Auto-Login:** Yes (after successful verification)
- **Channels:** Email (SMTP) or SMS (Twilio)

---

## 📝 Parameter Reference

| Parameter | Type | Required | Used In | Description |
|-----------|------|----------|---------|-------------|
| `type` | string | Yes | All APIs | `email` or `phone` |
| `identifier` | string | Yes | All APIs | Email address or phone number |
| `country_code` | string | Conditional | send-otp, resend-otp | Required for phone type (e.g., `+91`) |
| `otp` | string | Yes | verify-otp | 6-digit OTP code |

---

## 🎯 Success Response Format

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

---

## ❌ Error Response Format

```json
{
  "success": false,
  "message": "Email not registered"
}
```

---

## 🔐 After Verification

```json
{
  "success": true,
  "message": "OTP verified successfully. You are now logged in.",
  "data": {
    "customer": { ... },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

Use the `access_token` in subsequent requests:
```
Authorization: Bearer {access_token}
```
