# BMV API Documentation

**Version:** 1.0  
**Base URL:** `http://localhost:8000/api/v1`  
**Last Updated:** 2026-01-19

---

## Table of Contents

1. [Authentication APIs](#authentication-apis)
2. [Customer Profile APIs](#customer-profile-apis)
3. [Category APIs](#category-apis)
4. [General Information](#general-information)

---

## Authentication APIs

### 1. Register Customer

**Endpoint:** `POST /auth/register`  
**Authentication:** Not Required  
**Description:** Register a new customer account

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| name | string | Yes | min:2, max:255 | Customer's full name |
| email | string | Yes | valid email, unique | Customer's email address |
| password | string | Yes | min:8, confirmed | Customer's password |
| password_confirmation | string | Yes | must match password | Password confirmation |
| phone | string | Yes | min:10, max:20, unique | Customer's phone number |
| country_code | string | Yes | max:5 | Country code (e.g., +91) |
| gender | string | No | in:male,female,other | Customer's gender |
| dob | date | No | before:today | Date of birth |

#### Success Response (201)

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
      "canonical": "john_a1b2c3d4",
      "phone": "1234567890",
      "country_code": "+91",
      "gender": "male",
      "dob": "1990-01-01",
      "status": "active",
      "phone_validate": false,
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "refresh_expires_in": 1209600
  }
}
```

#### Error Response (422)

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

**Endpoint:** `POST /auth/login`  
**Authentication:** Not Required  
**Description:** Login with email or phone number

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| type | string | Yes | in:email,phone | Login type (email or phone) |
| identifier | string | Yes | - | Email address or phone number |
| password | string | Yes | min:8 | Customer's password |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "username": "john_doe",
      "phone": "1234567890",
      "country_code": "+91",
      "status": "active"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

#### Error Response (401)

```json
{
  "success": false,
  "message": "Invalid credentials. Please check your email and password.",
  "data": null
}
```

---

### 3. Get Profile

**Endpoint:** `GET /auth/profile`  
**Authentication:** Required (Bearer Token)  
**Description:** Get authenticated customer's profile

#### Request Parameters

No parameters required

#### Success Response (200)

```json
{
  "success": true,
  "message": "User profile",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "john_doe",
    "canonical": "john_a1b2c3d4",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01",
    "profile_image": "uploads/customers/image.jpg",
    "address": "123 Main St",
    "latitude": 23.0225,
    "longitude": 72.5714,
    "city": "Ahmedabad",
    "state": "Gujarat",
    "country": "India",
    "pincode": "380001",
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    },
    "status": "active",
    "created_at": "2026-01-19T14:30:00.000000Z"
  }
}
```

---

### 4. Logout

**Endpoint:** `POST /auth/logout`  
**Authentication:** Required (Bearer Token)  
**Description:** Logout and invalidate the current token

#### Request Parameters

No parameters required

#### Success Response (200)

```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

### 5. Refresh Token

**Endpoint:** `POST /auth/refresh`  
**Authentication:** Required (Bearer Token)  
**Description:** Refresh the authentication token

#### Request Parameters

No parameters required

#### Success Response (200)

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

## Customer Profile APIs

### 6. Get Customer Profile

**Endpoint:** `GET /customer/profile`  
**Authentication:** Required (Bearer Token)  
**Description:** Get authenticated customer's complete profile

#### Request Parameters

No parameters required

#### Success Response (200)

```json
{
  "success": true,
  "message": "Customer profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "john_doe",
    "canonical": "john_a1b2c3d4",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01",
    "profile_image": "uploads/customers/image.jpg",
    "address": "123 Main St",
    "latitude": 23.0225,
    "longitude": 72.5714,
    "city": "Ahmedabad",
    "state": "Gujarat",
    "country": "India",
    "pincode": "380001",
    "social_links": {
      "whatsapp": "+911234567890",
      "website": "https://johndoe.com",
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe",
      "linkedin": "https://linkedin.com/in/johndoe",
      "youtube": "https://youtube.com/@johndoe",
      "telegram": "https://t.me/johndoe",
      "twitter": "https://twitter.com/johndoe"
    },
    "status": "active",
    "created_at": "2026-01-19T14:30:00.000000Z"
  }
}
```

---

### 7. Update Customer Profile

**Endpoint:** `PUT /customer/profile` or `POST /customer/profile`  
**Authentication:** Required (Bearer Token)  
**Description:** Update authenticated customer's profile (use POST for multipart/form-data)

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| profile_image | file | No | image, mimes:jpeg,png,jpg,gif, max:2MB | Profile image file |
| username | string | No | max:255, regex:/^[a-zA-Z0-9_]+$/, unique | Username (alphanumeric and underscore only) |
| name | string | Yes | max:255 | Customer's full name |
| email | string | Yes | valid email, unique | Customer's email address |
| phone | string | No | unique | Customer's phone number |
| country_code | string | No | max:10 | Country code |
| gender | string | No | in:male,female,other | Customer's gender |
| dob | date | No | before:today | Date of birth |
| address | string | No | - | Full address |
| latitude | numeric | No | between:-90,90 | Latitude coordinate |
| longitude | numeric | No | between:-180,180 | Longitude coordinate |
| city | string | No | max:255 | City name |
| state | string | No | max:255 | State name |
| country | string | No | max:255 | Country name |
| pincode | string | No | max:20 | Postal/ZIP code |
| whatsapp | string | No | max:255 | WhatsApp number/link |
| website | string | No | valid url, max:255 | Website URL |
| facebook | string | No | valid url, max:255 | Facebook profile URL |
| instagram | string | No | valid url, max:255 | Instagram profile URL |
| linkedin | string | No | valid url, max:255 | LinkedIn profile URL |
| youtube | string | No | valid url, max:255 | YouTube channel URL |
| telegram | string | No | valid url, max:255 | Telegram profile URL |
| twitter | string | No | valid url, max:255 | Twitter profile URL |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "name": "John Doe Updated",
    "email": "john@example.com",
    "username": "john_doe_updated",
    "canonical": "http://localhost:8000/customer/john_doe_updated",
    "profile_image": "uploads/customers/1234567890_abc123.jpg",
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    }
  }
}
```

---

### 8. Update Password

**Endpoint:** `PUT /customer/password` or `POST /customer/password`  
**Authentication:** Required (Bearer Token)  
**Description:** Update customer's password

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| current_password | string | Yes | - | Current password |
| new_password | string | Yes | min:8, confirmed | New password |
| new_password_confirmation | string | Yes | must match new_password | New password confirmation |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Password updated successfully",
  "data": null
}
```

#### Error Response (400)

```json
{
  "success": false,
  "message": "Current password is incorrect",
  "data": null
}
```

---

### 9. Update Profile Image

**Endpoint:** `POST /customer/profile-image`  
**Authentication:** Required (Bearer Token)  
**Description:** Update customer's profile image

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| profile_image | file | Yes | image, mimes:jpeg,png,jpg,gif, max:2MB | Profile image file |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Profile image updated successfully",
  "data": {
    "profile_image": "uploads/customers/1234567890_abc123.jpg",
    "profile_image_url": "http://localhost:8000/uploads/customers/1234567890_abc123.jpg"
  }
}
```

---

### 10. Delete Profile Image

**Endpoint:** `DELETE /customer/profile-image`  
**Authentication:** Required (Bearer Token)  
**Description:** Delete customer's profile image

#### Request Parameters

No parameters required

#### Success Response (200)

```json
{
  "success": true,
  "message": "Profile image deleted successfully",
  "data": null
}
```

---

### 11. Update Location

**Endpoint:** `PUT /customer/location` or `POST /customer/location`  
**Authentication:** Required (Bearer Token)  
**Description:** Update customer's location information

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| latitude | numeric | Yes | between:-90,90 | Latitude coordinate |
| longitude | numeric | Yes | between:-180,180 | Longitude coordinate |
| address | string | No | - | Full address |
| city | string | No | max:255 | City name |
| state | string | No | max:255 | State name |
| country | string | No | max:255 | Country name |
| pincode | string | No | max:20 | Postal/ZIP code |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Location updated successfully",
  "data": {
    "latitude": 23.0225,
    "longitude": 72.5714,
    "address": "123 Main St, Ahmedabad",
    "city": "Ahmedabad",
    "state": "Gujarat",
    "country": "India",
    "pincode": "380001"
  }
}
```

---

### 12. Update Social Links

**Endpoint:** `PUT /customer/social-links` or `POST /customer/social-links`  
**Authentication:** Required (Bearer Token)  
**Description:** Update customer's social media links

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| whatsapp | string | No | max:255 | WhatsApp number/link |
| website | string | No | valid url, max:255 | Website URL |
| facebook | string | No | valid url, max:255 | Facebook profile URL |
| instagram | string | No | valid url, max:255 | Instagram profile URL |
| linkedin | string | No | valid url, max:255 | LinkedIn profile URL |
| youtube | string | No | valid url, max:255 | YouTube channel URL |
| telegram | string | No | valid url, max:255 | Telegram profile URL |
| twitter | string | No | valid url, max:255 | Twitter profile URL |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Social links updated successfully",
  "data": {
    "social_links": {
      "whatsapp": "+911234567890",
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

---

### 13. Delete Account

**Endpoint:** `DELETE /customer/account` or `POST /customer/account/delete`  
**Authentication:** Required (Bearer Token)  
**Description:** Delete customer account (soft delete)

#### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| password | string | Yes | - | Customer's password for confirmation |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Account deleted successfully",
  "data": null
}
```

#### Error Response (400)

```json
{
  "success": false,
  "message": "Password is incorrect",
  "data": null
}
```

---

### 14. Get Customer by ID

**Endpoint:** `GET /customers/{id}`  
**Authentication:** Required (Bearer Token)  
**Description:** Get public profile of a customer by ID

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Customer ID (URL parameter) |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Customer profile retrieved successfully",
  "data": {
    "id": 1,
    "username": "john_doe",
    "name": "John Doe",
    "profile_image": "uploads/customers/image.jpg",
    "profile_image_url": "http://localhost:8000/uploads/customers/image.jpg",
    "canonical": "john_a1b2c3d4",
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    },
    "created_at": "2026-01-19T14:30:00.000000Z"
  }
}
```

#### Error Response (404)

```json
{
  "success": false,
  "message": "Customer not found",
  "data": null
}
```

---

### 15. Get Customer by Username

**Endpoint:** `GET /customers/username/{username}`  
**Authentication:** Required (Bearer Token)  
**Description:** Get public profile of a customer by username

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Customer username (URL parameter) |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Customer profile retrieved successfully",
  "data": {
    "id": 1,
    "username": "john_doe",
    "name": "John Doe",
    "profile_image": "uploads/customers/image.jpg",
    "profile_image_url": "http://localhost:8000/uploads/customers/image.jpg",
    "canonical": "john_a1b2c3d4",
    "social_links": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe"
    },
    "created_at": "2026-01-19T14:30:00.000000Z"
  }
}
```

---

## Category APIs

### 16. Get Category Types

**Endpoint:** `GET /category-types`  
**Authentication:** Not Required  
**Description:** Get all available category types

#### Request Parameters

No parameters required

#### Success Response (200)

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
    }
  ]
}
```

---

### 17. Get Categories

**Endpoint:** `GET /categories`  
**Authentication:** Not Required  
**Description:** Get all active categories with optional filters

#### Request Parameters (Query String)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_type | string | No | Filter by category type (product/service) |
| search | string | No | Search categories by name |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "category_type": "product",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    },
    {
      "id": 2,
      "name": "Clothing",
      "category_type": "product",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    }
  ]
}
```

---

### 18. Get Sub-Categories

**Endpoint:** `GET /subcategories`  
**Authentication:** Not Required  
**Description:** Get all active sub-categories with optional filters

#### Request Parameters (Query String)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_id | integer | No | Filter by parent category ID |
| search | string | No | Search sub-categories by name |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Sub-categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Mobile Phones",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "name": "Laptops",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    }
  ]
}
```

---

### 19. Get Child Categories

**Endpoint:** `GET /child-categories`  
**Authentication:** Not Required  
**Description:** Get all active child categories with optional filters

#### Request Parameters (Query String)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_id | integer | No | Filter by parent category ID |
| sub_category_id | integer | No | Filter by parent sub-category ID |
| search | string | No | Search child categories by name |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Child categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "sub_category_id": 1,
      "name": "Android Phones",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "sub_category_id": 1,
      "name": "iOS Phones",
      "status": "active",
      "created_at": "2026-01-19T14:30:00.000000Z",
      "updated_at": "2026-01-19T14:30:00.000000Z"
    }
  ]
}
```

---

### 20. Health Check

**Endpoint:** `GET /health`  
**Authentication:** Not Required  
**Description:** Check if API is running

#### Request Parameters

No parameters required

#### Success Response (200)

```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2026-01-19 14:30:00"
}
```

---

## General Information

### Authentication

Most endpoints require authentication using JWT (JSON Web Token). Include the token in the Authorization header:

```
Authorization: Bearer {your_access_token}
```

### Response Format

All API responses follow this standard format:

```json
{
  "success": true/false,
  "message": "Response message",
  "data": {} or [] or null
}
```

### HTTP Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

### Error Response Format

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error description"]
  }
}
```

### Validation Error Response

```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "email": ["The email field is required"],
    "password": ["The password must be at least 8 characters"]
  }
}
```

### File Upload

For file uploads (like profile images), use `multipart/form-data` content type and POST method.

**Example using cURL:**

```bash
curl -X POST http://localhost:8000/api/v1/customer/profile-image \
  -H "Authorization: Bearer {your_token}" \
  -F "profile_image=@/path/to/image.jpg"
```

### Date Format

All dates are returned in ISO 8601 format:
```
2026-01-19T14:30:00.000000Z
```

### Pagination

Currently, the API returns all results without pagination. Pagination may be added in future versions.

---

## API Summary Table

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/auth/register` | POST | No | Register new customer |
| 2 | `/auth/login` | POST | No | Login customer |
| 3 | `/auth/profile` | GET | Yes | Get authenticated profile |
| 4 | `/auth/logout` | POST | Yes | Logout customer |
| 5 | `/auth/refresh` | POST | Yes | Refresh token |
| 6 | `/customer/profile` | GET | Yes | Get customer profile |
| 7 | `/customer/profile` | PUT/POST | Yes | Update customer profile |
| 8 | `/customer/password` | PUT/POST | Yes | Update password |
| 9 | `/customer/profile-image` | POST | Yes | Update profile image |
| 10 | `/customer/profile-image` | DELETE | Yes | Delete profile image |
| 11 | `/customer/location` | PUT/POST | Yes | Update location |
| 12 | `/customer/social-links` | PUT/POST | Yes | Update social links |
| 13 | `/customer/account` | DELETE | Yes | Delete account |
| 14 | `/customers/{id}` | GET | Yes | Get customer by ID |
| 15 | `/customers/username/{username}` | GET | Yes | Get customer by username |
| 16 | `/category-types` | GET | No | Get category types |
| 17 | `/categories` | GET | No | Get categories |
| 18 | `/subcategories` | GET | No | Get sub-categories |
| 19 | `/child-categories` | GET | No | Get child categories |
| 20 | `/health` | GET | No | Health check |

---

**Document Version:** 1.0  
**Generated:** 2026-01-19  
**Contact:** support@bmv.com
