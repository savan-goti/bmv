# BMV API Quick Reference Guide

**Base URL:** `http://localhost:8000/api/v1`

---

## Authentication APIs

```
POST   /auth/register              Register new customer
POST   /auth/login                 Login (email or phone)
GET    /auth/profile               Get authenticated profile
POST   /auth/logout                Logout
POST   /auth/refresh               Refresh token
```

### Register Example
```bash
POST /auth/register
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

### Login Example
```bash
POST /auth/login
{
  "type": "email",
  "identifier": "john@example.com",
  "password": "password123"
}
```

---

## Customer Profile APIs

```
GET    /customer/profile           Get profile
PUT    /customer/profile           Update profile
POST   /customer/profile           Update profile (with file)
PUT    /customer/password          Update password
POST   /customer/password          Update password
POST   /customer/profile-image     Update profile image
DELETE /customer/profile-image     Delete profile image
PUT    /customer/location          Update location
POST   /customer/location          Update location
PUT    /customer/social-links      Update social links
POST   /customer/social-links      Update social links
DELETE /customer/account           Delete account
POST   /customer/account/delete    Delete account
GET    /customers/{id}             Get customer by ID
GET    /customers/username/{user}  Get customer by username
```

### Update Profile Example
```bash
PUT /customer/profile
{
  "name": "John Doe Updated",
  "email": "john@example.com",
  "phone": "1234567890",
  "username": "john_doe",
  "gender": "male",
  "address": "123 Main St",
  "city": "Ahmedabad",
  "state": "Gujarat",
  "country": "India",
  "pincode": "380001"
}
```

### Update Password Example
```bash
PUT /customer/password
{
  "current_password": "oldpassword123",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

### Update Location Example
```bash
PUT /customer/location
{
  "latitude": 23.0225,
  "longitude": 72.5714,
  "address": "123 Main St, Ahmedabad",
  "city": "Ahmedabad",
  "state": "Gujarat",
  "country": "India",
  "pincode": "380001"
}
```

### Update Social Links Example
```bash
PUT /customer/social-links
{
  "whatsapp": "+911234567890",
  "website": "https://johndoe.com",
  "facebook": "https://facebook.com/johndoe",
  "instagram": "https://instagram.com/johndoe",
  "linkedin": "https://linkedin.com/in/johndoe",
  "youtube": "https://youtube.com/@johndoe",
  "telegram": "https://t.me/johndoe",
  "twitter": "https://twitter.com/johndoe"
}
```

---

## Category APIs

```
GET    /category-types             Get all category types
GET    /categories                 Get all categories
GET    /subcategories              Get all sub-categories
GET    /child-categories           Get all child categories
```

### Get Categories Example
```bash
GET /categories?category_type=product&search=electronics
```

### Get Sub-Categories Example
```bash
GET /subcategories?category_id=1&search=mobile
```

### Get Child Categories Example
```bash
GET /child-categories?category_id=1&sub_category_id=1&search=android
```

---

## General APIs

```
GET    /health                     Health check
```

---

## Authentication Header

For protected endpoints, include JWT token:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

---

## Common Request Parameters

### Authentication
- **name**: string, required (min:2, max:255)
- **email**: string, required, valid email, unique
- **password**: string, required (min:8)
- **phone**: string, required (min:10, max:20), unique
- **country_code**: string, required (max:5)

### Profile
- **username**: string, alphanumeric + underscore, unique
- **gender**: string (male/female/other)
- **dob**: date (before today)
- **profile_image**: file (jpeg/png/jpg/gif, max:2MB)

### Location
- **latitude**: numeric (-90 to 90)
- **longitude**: numeric (-180 to 180)
- **address**: string
- **city**: string (max:255)
- **state**: string (max:255)
- **country**: string (max:255)
- **pincode**: string (max:20)

### Social Links
- **whatsapp**: string (max:255)
- **website**: url (max:255)
- **facebook**: url (max:255)
- **instagram**: url (max:255)
- **linkedin**: url (max:255)
- **youtube**: url (max:255)
- **telegram**: url (max:255)
- **twitter**: url (max:255)

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": null
}
```

### Validation Error Response
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "field_name": ["Error description"]
  }
}
```

---

## HTTP Status Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **422** - Validation Error
- **500** - Internal Server Error

---

## File Upload

Use `multipart/form-data` for file uploads:

```bash
curl -X POST http://localhost:8000/api/v1/customer/profile-image \
  -H "Authorization: Bearer {token}" \
  -F "profile_image=@/path/to/image.jpg"
```

---

## Testing Tips

1. **Get Token**: First register or login to get JWT token
2. **Use Token**: Include token in Authorization header for protected endpoints
3. **Check Status**: Use `/health` endpoint to verify API is running
4. **Validate Data**: Check validation rules before sending requests
5. **Handle Errors**: Always check `success` field in response

---

**Last Updated:** 2026-01-19
