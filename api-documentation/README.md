# BMV API Documentation

Welcome to the BMV API documentation folder. This folder contains comprehensive documentation for all API endpoints available in the BMV application.

## 📁 Files in this Folder

### 1. API_DOCUMENTATION.md
**Complete API Documentation**
- Detailed information about all 20 API endpoints
- Request parameters with validation rules
- Success and error response examples
- Authentication requirements
- HTTP status codes
- General API information

### 2. API_QUICK_REFERENCE.md
**Quick Reference Guide**
- Concise endpoint listings
- Quick examples for common operations
- Common request parameters
- Response format overview
- Testing tips

## 📚 API Categories

The BMV API is organized into the following categories:

### 🔐 Authentication APIs (5 endpoints)
- Register Customer
- Login Customer
- Get Profile
- Logout
- Refresh Token

### 👤 Customer Profile APIs (10 endpoints)
- Get Customer Profile
- Update Customer Profile
- Update Password
- Update Profile Image
- Delete Profile Image
- Update Location
- Update Social Links
- Delete Account
- Get Customer by ID
- Get Customer by Username

### 📦 Category APIs (4 endpoints)
- Get Category Types
- Get Categories
- Get Sub-Categories
- Get Child Categories

### ⚡ General APIs (1 endpoint)
- Health Check

## 🚀 Getting Started

### Base URL
```
http://localhost:8000/api/v1
```

### Quick Start

1. **Register a new customer**
   ```bash
   POST /auth/register
   ```

2. **Login to get JWT token**
   ```bash
   POST /auth/login
   ```

3. **Use the token for authenticated requests**
   ```bash
   Authorization: Bearer {your_token}
   ```

## 📖 How to Use This Documentation

1. **For Complete Details**: Open `API_DOCUMENTATION.md`
   - Full parameter descriptions
   - Validation rules
   - Complete request/response examples
   - Error handling

2. **For Quick Reference**: Open `API_QUICK_REFERENCE.md`
   - Quick endpoint lookup
   - Simple examples
   - Common patterns

## 🔑 Authentication

Most endpoints require JWT authentication. Include the token in the Authorization header:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

## 📝 Response Format

All API responses follow this standard format:

```json
{
  "success": true/false,
  "message": "Response message",
  "data": {} or [] or null
}
```

## 🛠️ Testing the API

### Using cURL
```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "1234567890",
    "country_code": "+91"
  }'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
  }'

# Get Profile (with token)
curl -X GET http://localhost:8000/api/v1/customer/profile \
  -H "Authorization: Bearer {your_token}"
```

### Using Postman
1. Import the endpoints from the documentation
2. Set up environment variables for base URL and token
3. Use the examples provided in the documentation

## 📊 API Summary

| Category | Endpoints | Auth Required |
|----------|-----------|---------------|
| Authentication | 5 | Partial (2/5) |
| Customer Profile | 10 | Yes (all) |
| Category | 4 | No |
| General | 1 | No |
| **Total** | **20** | **12/20** |

## 🔄 Version History

- **v1.0** (2026-01-19) - Initial documentation
  - 20 API endpoints documented
  - Complete request/response examples
  - Quick reference guide

## 📞 Support

For API support or questions:
- Email: support@bmv.com
- Documentation Issues: Create an issue in the project repository

## 🔐 Security Notes

1. **Never share your JWT tokens**
2. **Use HTTPS in production**
3. **Tokens expire after 60 minutes** (use refresh endpoint)
4. **Validate all input data**
5. **Handle errors gracefully**

## 📋 Changelog

### 2026-01-19
- ✅ Created comprehensive API documentation
- ✅ Added quick reference guide
- ✅ Documented all 20 endpoints
- ✅ Added request/response examples
- ✅ Included validation rules

---

**Last Updated:** 2026-01-19  
**API Version:** 1.0  
**Documentation Version:** 1.0
