# Customer Info API - Quick Reference

## 📋 Overview
Complete REST API for managing customer information in the BMV application.

## 🔐 Authentication
All endpoints require JWT authentication:
```
Authorization: Bearer {your-jwt-token}
```

Get token from:
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`

---

## 📍 API Endpoints Summary

### Profile Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customer/profile` | Get authenticated customer profile |
| PUT/POST | `/customer/profile` | Update customer profile |

### Password Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| PUT/POST | `/customer/password` | Change password |

### Profile Image
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/customer/profile-image` | Upload/update profile image |
| DELETE | `/customer/profile-image` | Delete profile image |

### Location
| Method | Endpoint | Description |
|--------|----------|-------------|
| PUT/POST | `/customer/location` | Update location info |

### Social Links
| Method | Endpoint | Description |
|--------|----------|-------------|
| PUT/POST | `/customer/social-links` | Update social media links |

### Account Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| DELETE | `/customer/account` | Delete account (soft delete) |

### Public Profiles
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customers/{id}` | Get customer by ID |
| GET | `/customers/username/{username}` | Get customer by username |

---

## 🚀 Quick Start Examples

### 1. Get Profile
```bash
curl -X GET http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### 2. Update Profile
```bash
curl -X POST http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "name=John Doe" \
  -F "email=john@example.com" \
  -F "phone=1234567890"
```

### 3. Upload Profile Image
```bash
curl -X POST http://localhost/bmv/api/v1/customer/profile-image \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "profile_image=@/path/to/image.jpg"
```

### 4. Update Password
```bash
curl -X PUT http://localhost/bmv/api/v1/customer/password \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "current_password": "old123",
    "new_password": "new123",
    "new_password_confirmation": "new123"
  }'
```

### 5. Update Location
```bash
curl -X PUT http://localhost/bmv/api/v1/customer/location \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 40.7128,
    "longitude": -74.0060,
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "country": "USA",
    "pincode": "10001"
  }'
```

---

## 📝 Request Body Fields

### Update Profile
```json
{
  "profile_image": "file (max 2MB)",
  "username": "string (alphanumeric + underscore)",
  "name": "string (required)",
  "email": "string (required, valid email)",
  "phone": "string",
  "country_code": "string (max 10 chars)",
  "gender": "male|female|other",
  "dob": "YYYY-MM-DD (must be past date)",
  "address": "string",
  "latitude": "decimal (-90 to 90)",
  "longitude": "decimal (-180 to 180)",
  "city": "string",
  "state": "string",
  "country": "string",
  "pincode": "string",
  "whatsapp": "string",
  "website": "url",
  "facebook": "url",
  "instagram": "url",
  "linkedin": "url",
  "youtube": "url",
  "telegram": "url",
  "twitter": "url"
}
```

### Update Password
```json
{
  "current_password": "string (required)",
  "new_password": "string (min 8 chars, required)",
  "new_password_confirmation": "string (required)"
}
```

### Update Location
```json
{
  "latitude": "decimal (required)",
  "longitude": "decimal (required)",
  "address": "string",
  "city": "string",
  "state": "string",
  "country": "string",
  "pincode": "string"
}
```

### Update Social Links
```json
{
  "whatsapp": "string",
  "website": "url",
  "facebook": "url",
  "instagram": "url",
  "linkedin": "url",
  "youtube": "url",
  "telegram": "url",
  "twitter": "url"
}
```

### Delete Account
```json
{
  "password": "string (required)"
}
```

---

## ✅ Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful message",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message"
}
```

### Validation Error Response
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

---

## 🔒 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## 📦 Postman Collection

Import the Postman collection for easy testing:
- File: `docs/api/BMV-Customer-Info-API.postman_collection.json`
- Set `base_url` variable to your API URL
- Login to automatically set JWT token

---

## 🛠️ Testing Tips

1. **Get JWT Token First**
   - Register or login to get token
   - Token auto-saves in Postman collection

2. **File Uploads**
   - Use `multipart/form-data`
   - Max file size: 2MB
   - Supported formats: JPEG, PNG, JPG, GIF

3. **JSON Requests**
   - Use `application/json` content type
   - Validate JSON format before sending

4. **Error Handling**
   - Check response status code
   - Read error messages for debugging
   - Validate input data before sending

---

## 📚 Full Documentation

For detailed documentation, see:
- `docs/api/customer-info-api.md`

---

## 🔄 Common Workflows

### Complete Profile Setup
1. Login/Register → Get JWT token
2. Update Profile → Add basic info
3. Upload Profile Image → Add photo
4. Update Location → Add address
5. Update Social Links → Add social media

### Change Password Flow
1. Get current profile (verify logged in)
2. Update password with current + new password
3. Re-login with new password

### Account Deletion Flow
1. Confirm password
2. Delete account
3. User automatically logged out

---

## ⚠️ Important Notes

- All endpoints require authentication
- JWT tokens expire (default: 60 minutes)
- Use refresh token endpoint to renew
- Profile images stored in `public/uploads/customers/`
- Account deletion is soft delete (recoverable)
- Social links stored as JSON
- Location uses decimal coordinates (8 decimal places)
- Username must be unique and alphanumeric + underscore only
- Email and phone must be unique

---

## 🆘 Support

For issues or questions:
1. Check full documentation
2. Verify JWT token is valid
3. Check request format matches examples
4. Review error messages for specific issues
