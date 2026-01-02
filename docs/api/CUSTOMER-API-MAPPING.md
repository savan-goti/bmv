# Customer Info API - Complete Mapping

## Base URL
```
http://localhost/bmv/api/v1
```

---

## 🔐 Authentication Endpoints

### 1. Register Customer

**URL:** `POST /auth/register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "1234567890",
    "country_code": "+1",
    "username": "johndoe",
    "gender": "male",
    "dob": "1990-01-01"
}
```

**Success Output (201):**
```json
{
    "success": true,
    "message": "Registration successful! Welcome to BMV.",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "username": "johndoe",
            "phone": "1234567890",
            "country_code": "+1",
            "gender": "male",
            "dob": "1990-01-01",
            "canonical": "john_a1b2c3d4",
            "status": "active",
            "phone_validate": false,
            "created_at": "2026-01-02T20:00:00.000000Z",
            "updated_at": "2026-01-02T20:00:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600,
        "refresh_expires_in": 1209600
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["This email is already registered"],
        "password": ["Password must be at least 8 characters"]
    }
}
```

---

### 2. Login Customer

**URL:** `POST /auth/login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON (Email Login):**
```json
{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
}
```

**Form Data / JSON (Phone Login):**
```json
{
    "type": "phone",
    "identifier": "1234567890",
    "password": "password123"
}
```

**Request Parameters:**
- `type` (required, string) - Login type: `email` or `phone`
- `identifier` (required, string) - Email address or phone number based on type
- `password` (required, string, min:8) - User password

**Success Output (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "username": "johndoe",
            "phone": "1234567890",
            "country_code": "+1",
            "profile_image": "uploads/customers/1234567890_abc.jpg",
            "canonical": "john_a1b2c3d4",
            "status": "active",
            "created_at": "2026-01-02T20:00:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Error Output (401):**
```json
{
    "success": false,
    "message": "Invalid credentials. Please check your email and password."
}
```

**Error Output (422 - Validation Error):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "type": ["Login type is required"],
        "identifier": ["Email or phone number is required"]
    }
}
```

**Error Output (422 - Invalid Email Format):**
```json
{
    "success": false,
    "message": "Please provide a valid email address"
}
```

**Error Output (403 - Inactive Account):**
```json
{
    "success": false,
    "message": "Your account is inactive. Please contact support."
}
```

---

### 3. Logout Customer

**URL:** `POST /auth/logout`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**Success Output (200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

---

### 4. Refresh Token

**URL:** `POST /auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**Success Output (200):**
```json
{
    "success": true,
    "message": "Login successful",
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

---

## 👤 Profile Management Endpoints

### 5. Get Customer Profile

**URL:** `GET /customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**Success Output (200):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 1,
        "profile_image": "uploads/customers/1234567890_abc.jpg",
        "canonical": "john_a1b2c3d4",
        "username": "johndoe",
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "1234567890",
        "country_code": "+1",
        "phone_validate": true,
        "gender": "male",
        "dob": "1990-01-01",
        "address": "123 Main St, Apt 4B",
        "latitude": "40.71280000",
        "longitude": "-74.00600000",
        "city": "New York",
        "state": "NY",
        "country": "USA",
        "pincode": "10001",
        "social_links": {
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe",
            "twitter": "https://twitter.com/johndoe",
            "linkedin": "https://linkedin.com/in/johndoe"
        },
        "status": "active",
        "created_at": "2026-01-02T20:00:00.000000Z",
        "updated_at": "2026-01-02T20:15:00.000000Z"
    }
}
```

**Error Output (401):**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

---

### 6. Update Customer Profile (JSON)

**URL:** `PUT /customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "username": "johndoe_updated",
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "9876543210",
    "country_code": "+1",
    "gender": "male",
    "dob": "1990-01-15",
    "address": "456 Oak Avenue, Suite 100",
    "latitude": 40.7580,
    "longitude": -73.9855,
    "city": "New York",
    "state": "NY",
    "country": "USA",
    "pincode": "10019",
    "whatsapp": "+19876543210",
    "website": "https://johndoe.com",
    "facebook": "https://facebook.com/johndoe",
    "instagram": "https://instagram.com/johndoe",
    "linkedin": "https://linkedin.com/in/johndoe",
    "youtube": "https://youtube.com/@johndoe",
    "telegram": "https://t.me/johndoe",
    "twitter": "https://twitter.com/johndoe"
}
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "profile_image": "uploads/customers/1234567890_abc.jpg",
        "canonical": "johndoe_updated",
        "username": "johndoe_updated",
        "name": "John Doe Updated",
        "email": "john.updated@example.com",
        "phone": "9876543210",
        "country_code": "+1",
        "gender": "male",
        "dob": "1990-01-15",
        "address": "456 Oak Avenue, Suite 100",
        "latitude": "40.75800000",
        "longitude": "-73.98550000",
        "city": "New York",
        "state": "NY",
        "country": "USA",
        "pincode": "10019",
        "social_links": {
            "whatsapp": "+19876543210",
            "website": "https://johndoe.com",
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe",
            "linkedin": "https://linkedin.com/in/johndoe",
            "youtube": "https://youtube.com/@johndoe",
            "telegram": "https://t.me/johndoe",
            "twitter": "https://twitter.com/johndoe"
        },
        "status": "active",
        "updated_at": "2026-01-02T20:20:00.000000Z"
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["This email is already registered"],
        "username": ["This username is already taken"],
        "dob": ["Date of birth must be in the past"]
    }
}
```

---

### 7. Update Customer Profile (with Image)

**URL:** `POST /customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Form Data (multipart/form-data):**
```
profile_image: [FILE] (image.jpg)
name: John Doe Updated
email: john.updated@example.com
phone: 9876543210
country_code: +1
gender: male
dob: 1990-01-15
address: 456 Oak Avenue
city: New York
state: NY
country: USA
pincode: 10019
latitude: 40.7580
longitude: -73.9855
whatsapp: +19876543210
website: https://johndoe.com
facebook: https://facebook.com/johndoe
instagram: https://instagram.com/johndoe
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "profile_image": "uploads/customers/1735834200_xyz789.jpg",
        "canonical": "john_a1b2c3d4",
        "username": "johndoe",
        "name": "John Doe Updated",
        "email": "john.updated@example.com",
        "phone": "9876543210",
        "country_code": "+1",
        "gender": "male",
        "dob": "1990-01-15",
        "address": "456 Oak Avenue",
        "latitude": "40.75800000",
        "longitude": "-73.98550000",
        "city": "New York",
        "state": "NY",
        "country": "USA",
        "pincode": "10019",
        "social_links": {
            "whatsapp": "+19876543210",
            "website": "https://johndoe.com",
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe"
        },
        "status": "active",
        "updated_at": "2026-01-02T20:25:00.000000Z"
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "profile_image": [
            "The profile image must be an image",
            "The profile image must not be greater than 2048 kilobytes"
        ]
    }
}
```

---

## 🔑 Password Management Endpoints

### 8. Update Password

**URL:** `PUT /customer/password` or `POST /customer/password`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "current_password": "password123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
}
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Password updated successfully",
    "data": null
}
```

**Error Output (400):**
```json
{
    "success": false,
    "message": "Current password is incorrect"
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "new_password": [
            "New password must be at least 8 characters",
            "Password confirmation does not match"
        ]
    }
}
```

---

## 📸 Profile Image Management Endpoints

### 9. Upload/Update Profile Image

**URL:** `POST /customer/profile-image`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Form Data (multipart/form-data):**
```
profile_image: [FILE] (profile.jpg, max 2MB)
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Profile image updated successfully",
    "data": {
        "profile_image": "uploads/customers/1735834500_def456.jpg",
        "profile_image_url": "http://localhost/bmv/uploads/customers/1735834500_def456.jpg"
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "profile_image": [
            "Profile image is required",
            "The profile image must be an image",
            "Image must be jpeg, png, jpg, or gif",
            "Image size must not exceed 2MB"
        ]
    }
}
```

---

### 10. Delete Profile Image

**URL:** `DELETE /customer/profile-image`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**Success Output (200):**
```json
{
    "success": true,
    "message": "Profile image deleted successfully",
    "data": null
}
```

---

## 📍 Location Management Endpoints

### 11. Update Location

**URL:** `PUT /customer/location` or `POST /customer/location`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "latitude": 40.7128,
    "longitude": -74.0060,
    "address": "123 Main Street, Apartment 4B",
    "city": "New York",
    "state": "New York",
    "country": "United States",
    "pincode": "10001"
}
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Location updated successfully",
    "data": {
        "latitude": "40.71280000",
        "longitude": "-74.00600000",
        "address": "123 Main Street, Apartment 4B",
        "city": "New York",
        "state": "New York",
        "country": "United States",
        "pincode": "10001"
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "latitude": [
            "Latitude is required",
            "Latitude must be between -90 and 90"
        ],
        "longitude": [
            "Longitude is required",
            "Longitude must be between -180 and 180"
        ]
    }
}
```

---

## 🌐 Social Links Management Endpoints

### 12. Update Social Links

**URL:** `PUT /customer/social-links` or `POST /customer/social-links`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "whatsapp": "+1234567890",
    "website": "https://johndoe.com",
    "facebook": "https://facebook.com/johndoe",
    "instagram": "https://instagram.com/johndoe",
    "linkedin": "https://linkedin.com/in/johndoe",
    "youtube": "https://youtube.com/@johndoe",
    "telegram": "https://t.me/johndoe",
    "twitter": "https://twitter.com/johndoe"
}
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Social links updated successfully",
    "data": {
        "social_links": {
            "whatsapp": "+1234567890",
            "website": "https://johndoe.com",
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe",
            "linkedin": "https://linkedin.com/in/johndoe",
            "youtube": "https://youtube.com/@johndoe",
            "telegram": "https://t.me/johndoe",
            "twitter": "https://twitter.com/johndoe"
        }
    }
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "website": ["The website must be a valid URL"],
        "facebook": ["The facebook must be a valid URL"]
    }
}
```

---

## 🗑️ Account Management Endpoints

### 13. Delete Account

**URL:** `DELETE /customer/account` or `POST /customer/account/delete`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Form Data / JSON:**
```json
{
    "password": "password123"
}
```

**Success Output (200):**
```json
{
    "success": true,
    "message": "Account deleted successfully",
    "data": null
}
```

**Error Output (400):**
```json
{
    "success": false,
    "message": "Password is incorrect"
}
```

**Error Output (422):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "password": ["Password is required to delete account"]
    }
}
```

---

## 👥 Public Customer Profile Endpoints

### 14. Get Customer by ID

**URL:** `GET /customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**URL Parameters:**
- `id` (required) - Customer ID (integer)

**Example:** `GET /customers/5`

**Success Output (200):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 5,
        "username": "janedoe",
        "name": "Jane Doe",
        "profile_image": "uploads/customers/1735834800_ghi789.jpg",
        "profile_image_url": "http://localhost/bmv/uploads/customers/1735834800_ghi789.jpg",
        "canonical": "jane_x9y8z7w6",
        "social_links": {
            "facebook": "https://facebook.com/janedoe",
            "instagram": "https://instagram.com/janedoe",
            "twitter": "https://twitter.com/janedoe"
        },
        "created_at": "2026-01-01T10:00:00.000000Z"
    }
}
```

**Error Output (404):**
```json
{
    "success": false,
    "message": "Customer not found"
}
```

---

### 15. Get Customer by Username

**URL:** `GET /customers/username/{username}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Form Data:** None

**URL Parameters:**
- `username` (required) - Customer username (string)

**Example:** `GET /customers/username/janedoe`

**Success Output (200):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 5,
        "username": "janedoe",
        "name": "Jane Doe",
        "profile_image": "uploads/customers/1735834800_ghi789.jpg",
        "profile_image_url": "http://localhost/bmv/uploads/customers/1735834800_ghi789.jpg",
        "canonical": "jane_x9y8z7w6",
        "social_links": {
            "facebook": "https://facebook.com/janedoe",
            "instagram": "https://instagram.com/janedoe",
            "twitter": "https://twitter.com/janedoe"
        },
        "created_at": "2026-01-01T10:00:00.000000Z"
    }
}
```

**Error Output (404):**
```json
{
    "success": false,
    "message": "Customer not found"
}
```

---

## 📊 Quick Reference Table

| # | Endpoint | Method | Auth Required | Form Data Type | Response Type |
|---|----------|--------|---------------|----------------|---------------|
| 1 | `/auth/register` | POST | ❌ | JSON | Customer + Token |
| 2 | `/auth/login` | POST | ❌ | JSON | Customer + Token |
| 3 | `/auth/logout` | POST | ✅ | None | Success Message |
| 4 | `/auth/refresh` | POST | ✅ | None | New Token |
| 5 | `/customer/profile` | GET | ✅ | None | Customer Object |
| 6 | `/customer/profile` | PUT | ✅ | JSON | Customer Object |
| 7 | `/customer/profile` | POST | ✅ | Multipart | Customer Object |
| 8 | `/customer/password` | PUT/POST | ✅ | JSON | Success Message |
| 9 | `/customer/profile-image` | POST | ✅ | Multipart | Image URLs |
| 10 | `/customer/profile-image` | DELETE | ✅ | None | Success Message |
| 11 | `/customer/location` | PUT/POST | ✅ | JSON | Location Data |
| 12 | `/customer/social-links` | PUT/POST | ✅ | JSON | Social Links |
| 13 | `/customer/account` | DELETE | ✅ | JSON | Success Message |
| 14 | `/customers/{id}` | GET | ✅ | None | Public Profile |
| 15 | `/customers/username/{username}` | GET | ✅ | None | Public Profile |

---

## 🔧 Common HTTP Status Codes

| Code | Meaning | When Used |
|------|---------|-----------|
| 200 | OK | Successful request |
| 201 | Created | Registration successful |
| 400 | Bad Request | Invalid password, etc. |
| 401 | Unauthorized | Missing/invalid token |
| 403 | Forbidden | Account blocked |
| 404 | Not Found | Customer not found |
| 422 | Unprocessable Entity | Validation errors |
| 500 | Internal Server Error | Server error |

---

## 📝 Field Validation Rules

### Profile Fields
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `name` | string | ✅ | max:255 |
| `email` | string | ✅ | valid email, unique |
| `username` | string | ❌ | alphanumeric + underscore, unique |
| `phone` | string | ❌ | unique |
| `country_code` | string | ❌ | max:10 |
| `gender` | string | ❌ | male/female/other |
| `dob` | date | ❌ | YYYY-MM-DD, past date |
| `address` | string | ❌ | - |
| `latitude` | decimal | ❌ | -90 to 90 |
| `longitude` | decimal | ❌ | -180 to 180 |
| `city` | string | ❌ | max:255 |
| `state` | string | ❌ | max:255 |
| `country` | string | ❌ | max:255 |
| `pincode` | string | ❌ | max:20 |

### Social Links
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `whatsapp` | string | ❌ | max:255 |
| `website` | string | ❌ | valid URL, max:255 |
| `facebook` | string | ❌ | valid URL, max:255 |
| `instagram` | string | ❌ | valid URL, max:255 |
| `linkedin` | string | ❌ | valid URL, max:255 |
| `youtube` | string | ❌ | valid URL, max:255 |
| `telegram` | string | ❌ | valid URL, max:255 |
| `twitter` | string | ❌ | valid URL, max:255 |

### Image Upload
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `profile_image` | file | ✅ | image, max:2MB, jpeg/png/jpg/gif |

### Password
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `password` | string | ✅ | min:8, confirmed |
| `current_password` | string | ✅ | - |
| `new_password` | string | ✅ | min:8, confirmed |

---

## 💡 Usage Examples

### Example 1: Complete Registration Flow
```bash
# 1. Register
curl -X POST http://localhost/bmv/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'

# Response: Save the access_token

# 2. Get Profile
curl -X GET http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Example 2: Update Profile with Image
```bash
curl -X POST http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=John Doe Updated" \
  -F "email=john.updated@example.com" \
  -F "profile_image=@/path/to/image.jpg"
```

### Example 3: Update Location
```bash
curl -X PUT http://localhost/bmv/api/v1/customer/location \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"latitude":40.7128,"longitude":-74.0060,"address":"123 Main St","city":"New York"}'
```

---

## 🎯 Testing Checklist

- [ ] Register new customer
- [ ] Login with credentials
- [ ] Get customer profile
- [ ] Update profile (JSON)
- [ ] Update profile with image
- [ ] Change password
- [ ] Upload profile image
- [ ] Delete profile image
- [ ] Update location
- [ ] Update social links
- [ ] Get customer by ID
- [ ] Get customer by username
- [ ] Delete account
- [ ] Verify logout

---

**Document Version:** 1.0  
**Last Updated:** 2026-01-02  
**Base URL:** `http://localhost/bmv/api/v1`
