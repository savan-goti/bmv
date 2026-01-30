# BMV API Documentation - Quick Reference

## Base URL
```
http://localhost/api/v1
```

---

## Authentication

All API endpoints use JWT (JSON Web Token) authentication except for public endpoints.

### Headers Required
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## 1. Authentication Endpoints

### 1.1 Register Customer
**POST** `/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "country_code": "+91",
  "password": "password123",
  "password_confirmation": "password123",
  "gender": "male",
  "dob": "1990-01-01"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Customer registered successfully",
  "data": {
    "customer": {
      "id": 1,
      "username": "johndoe",
      "canonical": "john@example.com",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "country_code": "+91",
      "gender": "male",
      "dob": "1990-01-01",
      "phone_validate": false,
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### 1.2 Login - Send OTP (Step 1)
**POST** `/auth/login`

Sends OTP to customer's email or phone for login verification.

**Request Body (Email Login):**
```json
{
  "type": "email",
  "identifier": "john@example.com"
}
```

**Request Body (Phone Login):**
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
    "expires_in_minutes": 10,
    "message": "Please verify OTP to login"
  }
}
```

---

### 1.3 Verify Login OTP (Step 2)
**POST** `/auth/verify-login-otp`

Verifies OTP and logs in the customer.

**Request Body (Email):**
```json
{
  "type": "email",
  "identifier": "john@example.com",
  "otp": "123456"
}
```

**Request Body (Phone):**
```json
{
  "type": "phone",
  "identifier": "1234567890",
  "otp": "123456"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful! Welcome back.",
  "data": {
    "customer": {
      "id": 1,
      "username": "johndoe",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "phone_validate": true,
      "status": "active"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### 1.4 Resend Login OTP
**POST** `/auth/resend-login-otp`

Resends OTP to customer's email or phone for login.

**Request Body (Email):**
```json
{
  "type": "email",
  "identifier": "john@example.com"
}
```

**Request Body (Phone):**
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
  "message": "OTP resent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 10,
    "message": "Please verify OTP to login"
  }
}
```

**Error Response (429 - Rate Limited):**
```json
{
  "success": false,
  "message": "Please wait 45 seconds before requesting a new OTP"
}
```

---

### 1.5 Register Customer (Step 1)
**POST** `/auth/register`

Sends OTP to customer's phone for registration.

**Request Body:**
```json
{
  "phone": "1234567890",
  "country_code": "+91"
}
```

**Response (200):**
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

### 1.6 Verify Registration OTP (Step 2)
**POST** `/auth/verify-registration-otp`

Verifies OTP and completes customer registration.

**Request Body:**
```json
{
  "phone": "1234567890",
  "otp": "123456"
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
      "username": "user_34567890",
      "canonical": "ph_1234567890_abc123",
      "name": "user_34567890",
      "phone": "1234567890",
      "country_code": "+91",
      "phone_validate": true,
      "status": "active",
      "created_at": "2026-01-30T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### 1.7 Resend Registration OTP
**POST** `/auth/resend-registration-otp`

Resends OTP to customer's phone during registration.

**Request Body:**
```json
{
  "phone": "1234567890",
  "country_code": "+91"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "OTP resent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 10,
    "message": "Please verify OTP to complete registration"
  }
}
```

---

### 1.8 Logout
**POST** `/auth/logout`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

### 1.9 Refresh Token
**POST** `/auth/refresh`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

---

### 1.10 Get Profile
**GET** `/auth/profile`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "username": "johndoe",
    "canonical": "john@example.com",
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01",
    "profile_image": "uploads/customer_profiles/profile.jpg",
    "address": "123 Main St",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India",
    "pincode": "400001",
    "latitude": "19.0760",
    "longitude": "72.8777",
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    },
    "phone_validate": true,
    "status": "active",
    "created_at": "2026-01-20T10:00:00.000000Z"
  }
}
```


---

## 2. Customer Profile Endpoints

### 2.1 Get Customer Profile
**GET** `/customer/profile`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "username": "johndoe",
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "profile_image": "uploads/customer_profiles/profile.jpg"
  }
}
```

---

### 2.2 Update Customer Profile
**PUT** `/customer/profile`  
**POST** `/customer/profile` (for multipart/form-data)  
**Auth Required:** Yes

**Request Body:**
```json
{
  "name": "John Updated",
  "phone": "9876543210",
  "country_code": "+91",
  "gender": "male",
  "dob": "1990-01-01",
  "address": "456 New St",
  "city": "Delhi",
  "state": "Delhi",
  "country": "India",
  "pincode": "110001"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "name": "John Updated",
    "phone": "9876543210",
    "address": "456 New St"
  }
}
```

---

### 2.3 Update Password
**PUT** `/customer/password`  
**POST** `/customer/password`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "current_password": "oldpassword123",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password updated successfully"
}
```

---

### 2.4 Update Profile Image
**POST** `/customer/profile-image`  
**Auth Required:** Yes  
**Content-Type:** multipart/form-data

**Request Body:**
```
profile_image: [file]
```

**Response (200):**
```json
{
  "success": true,
  "message": "Profile image updated successfully",
  "data": {
    "profile_image": "uploads/customer_profiles/profile_123456.jpg"
  }
}
```

---

### 2.5 Delete Profile Image
**DELETE** `/customer/profile-image`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Profile image deleted successfully"
}
```

---

### 2.6 Update Location
**PUT** `/customer/location`  
**POST** `/customer/location`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "latitude": "19.0760",
  "longitude": "72.8777",
  "address": "123 Main St",
  "city": "Mumbai",
  "state": "Maharashtra",
  "country": "India",
  "pincode": "400001"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Location updated successfully",
  "data": {
    "latitude": "19.0760",
    "longitude": "72.8777",
    "address": "123 Main St"
  }
}
```

---

### 2.7 Update Social Links
**PUT** `/customer/social-links`  
**POST** `/customer/social-links`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "social_links": {
    "facebook": "https://facebook.com/johndoe",
    "instagram": "https://instagram.com/johndoe",
    "twitter": "https://twitter.com/johndoe",
    "linkedin": "https://linkedin.com/in/johndoe"
  }
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Social links updated successfully",
  "data": {
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    }
  }
}
```

---

### 2.8 Delete Account
**DELETE** `/customer/account`  
**POST** `/customer/account/delete`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Account deleted successfully"
}
```

---

### 2.9 Get Customer by ID
**GET** `/customers/{id}`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Customer retrieved successfully",
  "data": {
    "id": 1,
    "username": "johndoe",
    "name": "John Doe",
    "profile_image": "uploads/customer_profiles/profile.jpg",
    "city": "Mumbai",
    "state": "Maharashtra"
  }
}
```

---

### 2.10 Get Customer by Username
**GET** `/customers/username/{username}`  
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Customer retrieved successfully",
  "data": {
    "id": 1,
    "username": "johndoe",
    "name": "John Doe",
    "profile_image": "uploads/customer_profiles/profile.jpg"
  }
}
```

---

## 3. Category Endpoints

### 3.1 Get Category Types
**GET** `/category-types`

**Response (200):**
```json
{
  "success": true,
  "message": "Category types retrieved successfully",
  "data": [
    {
      "value": "product",
      "label": "Product"
    },
    {
      "value": "service",
      "label": "Service"
    },
    {
      "value": "digital",
      "label": "Digital"
    },
    {
      "value": "mixed",
      "label": "Mixed"
    },
    {
      "value": "business",
      "label": "Business"
    }
  ]
}
```

---

### 3.2 Get Categories
**GET** `/categories`

**Query Parameters:**
- `category_type` (optional): Filter by type (product, service, digital, mixed, business)
- `search` (optional): Search by name

**Example:**
```
GET /categories?category_type=product&search=electronics
```

**Response (200):**
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics",
      "category_type": "product",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Fashion",
      "slug": "fashion",
      "category_type": "product",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    }
  ]
}
```

---

### 3.3 Get Sub-Categories
**GET** `/subcategories`

**Query Parameters:**
- `category_id` (required): Parent category ID
- `search` (optional): Search by name

**Example:**
```
GET /subcategories?category_id=1&search=mobile
```

**Response (200):**
```json
{
  "success": true,
  "message": "Sub-categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Mobile Phones",
      "slug": "mobile-phones",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "name": "Laptops",
      "slug": "laptops",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    }
  ]
}
```

---

### 3.4 Get Child Categories
**GET** `/child-categories`

**Query Parameters:**
- `category_id` (required): Parent category ID
- `sub_category_id` (required): Parent sub-category ID
- `search` (optional): Search by name

**Example:**
```
GET /child-categories?category_id=1&sub_category_id=1&search=samsung
```

**Response (200):**
```json
{
  "success": true,
  "message": "Child categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "sub_category_id": 1,
      "name": "Samsung Phones",
      "slug": "samsung-phones",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "sub_category_id": 1,
      "name": "iPhone",
      "slug": "iphone",
      "status": "active",
      "created_at": "2026-01-20T10:00:00.000000Z"
    }
  ]
}
```

---

## 4. Health Check

### 4.1 API Health Check
**GET** `/health`

**Response (200):**
```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2026-01-20 10:00:00"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## Response Format

All API responses follow this standard format:

**Success Response:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

---

## Rate Limiting

⚠️ **Not yet implemented**

Recommended limits:
- Authentication endpoints: 5 requests per minute
- General endpoints: 60 requests per minute
- OTP endpoints: 3 requests per minute

---

## Testing

### Using cURL

**Register:**
```bash
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "country_code": "+91",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Login:**
```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "type": "email",
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Get Profile (with token):**
```bash
curl -X GET http://localhost/api/v1/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## Postman Collection

A Postman collection should be created with all endpoints for easy testing.

**Collection Structure:**
```
BMV API
├── Authentication
│   ├── Register
│   ├── Login (Email)
│   ├── Login (Phone)
│   ├── Send OTP
│   ├── Verify OTP
│   ├── Resend OTP
│   ├── Logout
│   ├── Refresh Token
│   └── Get Profile
├── Customer Profile
│   ├── Get Profile
│   ├── Update Profile
│   ├── Update Password
│   ├── Upload Profile Image
│   ├── Delete Profile Image
│   ├── Update Location
│   ├── Update Social Links
│   ├── Delete Account
│   ├── Get Customer by ID
│   └── Get Customer by Username
├── Categories
│   ├── Get Category Types
│   ├── Get Categories
│   ├── Get Sub-Categories
│   └── Get Child Categories
└── Health Check
    └── API Health
```

---

**Last Updated:** January 20, 2026  
**API Version:** v1  
**Base URL:** http://localhost/api/v1
