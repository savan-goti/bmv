# BMV API Documentation

## Base URL
```
/api/v1
```

---

## Authentication APIs

### 1. Register Customer
**Endpoint:** `POST /api/v1/auth/register`

**Parameters:**
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

**Output (Success - 201):**
```json
{
  "success": true,
  "message": "Registration successful! Welcome to BMV.",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "username": "johndoe123",
      "canonical": "unique_canonical_id",
      "phone": "1234567890",
      "country_code": "+91",
      "gender": "male",
      "dob": "1990-01-01",
      "status": "active",
      "phone_validate": false,
      "created_at": "2026-01-26T15:04:12.000000Z",
      "updated_at": "2026-01-26T15:04:12.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "refresh_expires_in": 1209600
  }
}
```

**Output (Error - 422):**
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "email": ["This email is already registered"],
    "password": ["Password confirmation does not match"]
  }
}
```

---

### 2. Login Customer
**Endpoint:** `POST /api/v1/auth/login`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| type | string | Yes | in:email,phone | Login type (email or phone) |
| identifier | string | Yes | - | Email address or phone number |
| password | string | Yes | min:8 | Customer's password |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Output (Error - 401):**
```json
{
  "success": false,
  "message": "Invalid credentials. Please check your email and password."
}
```

---

### 3. Send OTP
**Endpoint:** `POST /api/v1/auth/send-otp`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| phone | string | Yes | exists:customers,phone | Registered phone number |
| country_code | string | Yes | max:5 | Country code (e.g., +91) |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "OTP sent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 5
  }
}
```

**Output (Error - 400):**
```json
{
  "success": false,
  "message": "Phone number is already verified"
}
```

---

### 4. Verify OTP
**Endpoint:** `POST /api/v1/auth/verify-otp`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| phone | string | Yes | exists:customers,phone | Registered phone number |
| otp | string | Yes | size:6 | 6-digit OTP code |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Phone verified successfully",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "username": "johndoe123",
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

**Output (Error - 400):**
```json
{
  "success": false,
  "message": "Invalid OTP. Please try again."
}
```

---

### 5. Resend OTP
**Endpoint:** `POST /api/v1/auth/resend-otp`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| phone | string | Yes | exists:customers,phone | Registered phone number |
| country_code | string | Yes | max:5 | Country code (e.g., +91) |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "OTP resent successfully to your phone",
  "data": {
    "phone": "1234567890",
    "expires_in_minutes": 5
  }
}
```

**Output (Error - 429):**
```json
{
  "success": false,
  "message": "Please wait 60 seconds before requesting a new OTP"
}
```

---

### 6. Get Profile (Protected)
**Endpoint:** `GET /api/v1/auth/profile`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Parameters:** None

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe123",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01",
    "status": "active",
    "phone_validate": true
  }
}
```

---

### 7. Logout (Protected)
**Endpoint:** `POST /api/v1/auth/logout`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Parameters:** None

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

### 8. Refresh Token (Protected)
**Endpoint:** `POST /api/v1/auth/refresh`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Parameters:** None

**Output (Success - 200):**
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

## Customer Profile APIs (Protected)

All customer profile APIs require authentication via Bearer token.

**Headers:**
```
Authorization: Bearer {access_token}
```

---

### 9. Get Customer Profile
**Endpoint:** `GET /api/v1/customer/profile`

**Parameters:** None

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "username": "johndoe123",
    "phone": "1234567890",
    "country_code": "+91",
    "gender": "male",
    "dob": "1990-01-01",
    "profile_image": "https://example.com/images/profile.jpg",
    "address": "123 Main St",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India",
    "pincode": "400001",
    "latitude": "19.0760",
    "longitude": "72.8777",
    "social_media": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe",
      "twitter": "https://twitter.com/johndoe"
    },
    "status": "active",
    "phone_validate": true
  }
}
```

---

### 10. Update Customer Profile
**Endpoint:** `PUT /api/v1/customer/profile` or `POST /api/v1/customer/profile`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| name | string | No | min:2, max:255 | Customer's full name |
| username | string | No | min:3, max:50, unique | Username |
| gender | string | No | in:male,female,other | Gender |
| dob | date | No | before:today | Date of birth |
| address | string | No | max:500 | Street address |
| city | string | No | max:100 | City name |
| state | string | No | max:100 | State name |
| country | string | No | max:100 | Country name |
| pincode | string | No | max:20 | Postal code |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "name": "John Doe Updated",
    "username": "johndoe_new",
    "gender": "male",
    "dob": "1990-01-01",
    "address": "456 New Street",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India",
    "pincode": "400002"
  }
}
```

---

### 11. Update Password
**Endpoint:** `PUT /api/v1/customer/password` or `POST /api/v1/customer/password`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| current_password | string | Yes | - | Current password |
| password | string | Yes | min:8, confirmed | New password |
| password_confirmation | string | Yes | must match password | Password confirmation |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Password updated successfully"
}
```

**Output (Error - 400):**
```json
{
  "success": false,
  "message": "Current password is incorrect"
}
```

---

### 12. Update Profile Image
**Endpoint:** `POST /api/v1/customer/profile-image`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| profile_image | file | Yes | image, max:2048 (2MB) | Profile image file |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Profile image updated successfully",
  "data": {
    "profile_image": "https://example.com/storage/profiles/image.jpg"
  }
}
```

---

### 13. Delete Profile Image
**Endpoint:** `DELETE /api/v1/customer/profile-image`

**Parameters:** None

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Profile image deleted successfully"
}
```

---

### 14. Update Location
**Endpoint:** `PUT /api/v1/customer/location` or `POST /api/v1/customer/location`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| latitude | numeric | Yes | between:-90,90 | Latitude coordinate |
| longitude | numeric | Yes | between:-180,180 | Longitude coordinate |
| address | string | No | max:500 | Full address |
| city | string | No | max:100 | City name |
| state | string | No | max:100 | State name |
| country | string | No | max:100 | Country name |
| pincode | string | No | max:20 | Postal code |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Location updated successfully",
  "data": {
    "latitude": "19.0760",
    "longitude": "72.8777",
    "address": "123 Main St",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India",
    "pincode": "400001"
  }
}
```

---

### 15. Update Social Links
**Endpoint:** `PUT /api/v1/customer/social-links` or `POST /api/v1/customer/social-links`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| facebook | string | No | url, max:255 | Facebook profile URL |
| instagram | string | No | url, max:255 | Instagram profile URL |
| twitter | string | No | url, max:255 | Twitter profile URL |
| linkedin | string | No | url, max:255 | LinkedIn profile URL |
| youtube | string | No | url, max:255 | YouTube channel URL |
| website | string | No | url, max:255 | Personal website URL |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Social links updated successfully",
  "data": {
    "social_media": {
      "facebook": "https://facebook.com/johndoe",
      "instagram": "https://instagram.com/johndoe",
      "twitter": "https://twitter.com/johndoe",
      "linkedin": "https://linkedin.com/in/johndoe",
      "youtube": "https://youtube.com/johndoe",
      "website": "https://johndoe.com"
    }
  }
}
```

---

### 16. Delete Account
**Endpoint:** `DELETE /api/v1/customer/account` or `POST /api/v1/customer/account/delete`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| password | string | Yes | - | Current password for confirmation |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Account deleted successfully"
}
```

**Output (Error - 400):**
```json
{
  "success": false,
  "message": "Incorrect password"
}
```

---

### 17. Get Customer by ID
**Endpoint:** `GET /api/v1/customers/{id}`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes (URL) | Customer ID |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Customer profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe123",
    "profile_image": "https://example.com/images/profile.jpg",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India"
  }
}
```

---

### 18. Get Customer by Username
**Endpoint:** `GET /api/v1/customers/username/{username}`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes (URL) | Customer username |

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "Customer profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe123",
    "profile_image": "https://example.com/images/profile.jpg",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India"
  }
}
```

---

## Category APIs

### 19. Get Category Types
**Endpoint:** `GET /api/v1/category-types`

**Parameters:** None

**Output (Success - 200):**
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
      "value": "both",
      "label": "Both"
    }
  ]
}
```

---

### 20. Get Categories
**Endpoint:** `GET /api/v1/categories`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_type | string | No | Filter by type (product, service, both) |
| search | string | No | Search by category name |

**Output (Success - 200):**
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
      "image": "https://example.com/images/electronics.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    },
    {
      "id": 2,
      "name": "Clothing",
      "slug": "clothing",
      "category_type": "product",
      "image": "https://example.com/images/clothing.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    }
  ]
}
```

---

### 21. Get Sub-Categories
**Endpoint:** `GET /api/v1/subcategories`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| category_id | integer | Yes | exists:categories,id | Parent category ID |
| search | string | No | - | Search by sub-category name |

**Output (Success - 200):**
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
      "image": "https://example.com/images/mobile.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "name": "Laptops",
      "slug": "laptops",
      "image": "https://example.com/images/laptops.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    }
  ]
}
```

**Output (Error - 422):**
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "category_id": ["Category ID is required"]
  }
}
```

---

### 22. Get Child Categories
**Endpoint:** `GET /api/v1/child-categories`

**Parameters:**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| category_id | integer | Yes | exists:categories,id | Parent category ID |
| sub_category_id | integer | Yes | exists:sub_categories,id | Parent sub-category ID |
| search | string | No | - | Search by child category name |

**Output (Success - 200):**
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
      "slug": "android-phones",
      "image": "https://example.com/images/android.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    },
    {
      "id": 2,
      "category_id": 1,
      "sub_category_id": 1,
      "name": "iOS Phones",
      "slug": "ios-phones",
      "image": "https://example.com/images/ios.jpg",
      "status": "active",
      "created_at": "2026-01-26T15:04:12.000000Z"
    }
  ]
}
```

**Output (Error - 422):**
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "category_id": ["Category ID is required"],
    "sub_category_id": ["Sub-category ID is required"]
  }
}
```

---

## Health Check API

### 23. Health Check
**Endpoint:** `GET /api/health`

**Parameters:** None

**Output (Success - 200):**
```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2026-01-26 20:34:12"
}
```

---

## Seller Web Routes (Non-API)

### Authentication Routes (Guest Only)

#### 24. Seller Login Page
**Endpoint:** `GET /seller/login`

**Output:** Login page view

---

#### 25. Seller Login Submit
**Endpoint:** `POST /seller/login`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| email | string | Yes | Seller email |
| password | string | Yes | Seller password |

**Output:** Redirect to dashboard or back with errors

---

#### 26. Seller Registration Page
**Endpoint:** `GET /seller/register`

**Output:** Registration page view

---

#### 27. Seller Registration Submit
**Endpoint:** `POST /seller/register`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| business_name | string | Yes | Business name |
| owner_name | string | Yes | Owner's full name |
| email | string | Yes | Email address |
| password | string | Yes | Password |
| password_confirmation | string | Yes | Password confirmation |
| phone | string | Yes | Phone number |
| business_type | string | Yes | Type of business |
| address | string | Yes | Business address |

**Output:** Redirect to login or back with errors

---

#### 28. Google OAuth Login
**Endpoint:** `GET /seller/auth/google`

**Output:** Redirect to Google OAuth

---

#### 29. Google OAuth Register
**Endpoint:** `GET /seller/auth/google/register`

**Output:** Redirect to Google OAuth

---

#### 30. Google OAuth Callback
**Endpoint:** `GET /seller/auth/google/callback`

**Output:** Process OAuth and redirect to dashboard or login

---

#### 31. Forgot Password Page
**Endpoint:** `GET /seller/forgot-password`

**Output:** Forgot password form view

---

#### 32. Send Reset Link
**Endpoint:** `POST /seller/forgot-password`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| email | string | Yes | Registered email |

**Output:** Redirect with success/error message

---

#### 33. Reset Password Form
**Endpoint:** `GET /seller/reset-password/{token}`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| token | string | Yes (URL) | Reset token |

**Output:** Reset password form view

---

#### 34. Reset Password Submit
**Endpoint:** `POST /seller/reset-password`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| token | string | Yes | Reset token |
| email | string | Yes | Email address |
| password | string | Yes | New password |
| password_confirmation | string | Yes | Password confirmation |

**Output:** Redirect to login or back with errors

---

### Email Verification (Signed URL)

#### 35. Verify Email
**Endpoint:** `GET /seller/email/verify/{id}/{hash}`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes (URL) | Seller ID |
| hash | string | Yes (URL) | Verification hash |

**Output:** Email verification confirmation

---

### Protected Seller Routes (Authenticated)

#### 36. Seller Logout
**Endpoint:** `ANY /seller/logout`

**Output:** Redirect to login page

---

#### 37. Seller Dashboard
**Endpoint:** `GET /seller/`

**Output:** Dashboard view

---

#### 38. Seller Profile Page
**Endpoint:** `GET /seller/profile`

**Output:** Profile page view

---

#### 39. Update Seller Profile
**Endpoint:** `POST /seller/profile`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| business_name | string | No | Business name |
| owner_name | string | No | Owner name |
| phone | string | No | Phone number |
| address | string | No | Address |

**Output:** Redirect back with success/error

---

#### 40. Update Seller Password
**Endpoint:** `POST /seller/profile/password`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| current_password | string | Yes | Current password |
| password | string | Yes | New password |
| password_confirmation | string | Yes | Password confirmation |

**Output:** Redirect back with success/error

---

#### 41. Seller Login History
**Endpoint:** `GET /seller/profile/login-history`

**Output:** Login history view

---

#### 42. Seller Settings Page
**Endpoint:** `GET /seller/settings`

**Output:** Settings page view

---

#### 43. Update Seller Settings
**Endpoint:** `POST /seller/settings`

**Parameters:** Various settings parameters

**Output:** Redirect back with success/error

---

#### 44. Send Email Verification
**Endpoint:** `POST /seller/settings/email/send-verification`

**Output:** JSON response with success/error

---

#### 45. Enable Two-Factor Authentication
**Endpoint:** `POST /seller/settings/two-factor/enable`

**Output:** JSON response with QR code

---

#### 46. Confirm Two-Factor Authentication
**Endpoint:** `POST /seller/settings/two-factor/confirm`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| code | string | Yes | 2FA verification code |

**Output:** JSON response with success/error

---

#### 47. Disable Two-Factor Authentication
**Endpoint:** `POST /seller/settings/two-factor/disable`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| password | string | Yes | Current password |

**Output:** JSON response with success/error

---

#### 48. Regenerate Recovery Codes
**Endpoint:** `POST /seller/settings/two-factor/recovery-codes`

**Output:** JSON response with new recovery codes

---

#### 49. Get Active Sessions
**Endpoint:** `GET /seller/settings/sessions`

**Output:** JSON response with active sessions

---

#### 50. Logout Single Session
**Endpoint:** `POST /seller/settings/sessions/logout`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| session_id | string | Yes | Session ID to logout |

**Output:** JSON response with success/error

---

#### 51. Logout Other Sessions
**Endpoint:** `POST /seller/settings/sessions/logout-others`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| password | string | Yes | Current password |

**Output:** JSON response with success/error

---

#### 52. Delete Seller Account
**Endpoint:** `POST /seller/settings/delete-account`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| password | string | Yes | Current password for confirmation |

**Output:** Redirect to login with success message

---

## Common Response Formats

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { }
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

## Authentication

Most API endpoints require JWT authentication. Include the token in the Authorization header:

```
Authorization: Bearer {your_access_token}
```

### Token Expiration
- Access Token: 60 minutes (3600 seconds)
- Refresh Token: 14 days (1209600 seconds)

Use the `/api/v1/auth/refresh` endpoint to get a new access token before it expires.

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

---

## Notes

1. All API responses follow a consistent JSON format with `success`, `message`, and `data` fields.
2. Dates are returned in ISO 8601 format (e.g., "2026-01-26T15:04:12.000000Z").
3. File uploads should use `multipart/form-data` content type.
4. For endpoints that accept both PUT and POST methods, use POST when uploading files.
5. All endpoints are prefixed with `/api/v1` except the health check endpoint.
6. Seller routes are web-based (not API) and use session authentication.
