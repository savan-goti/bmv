# BMV API - Postman Guide

## Base URL
```
http://localhost:8000/api/v1
```

---

## 🔐 Authentication APIs

### 1. Register Customer
**Method:** `POST`  
**URL:** `{{base_url}}/auth/register`  
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
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

**Response (Success - 201):**
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

---

### 2. Login Customer
**Method:** `POST`  
**URL:** `{{base_url}}/auth/login`  
**Body Type:** `raw (JSON)`

**Postman Body (Email Login):**
```json
{
    "type": "email",
    "identifier": "john@example.com",
    "password": "password123"
}
```

**Postman Body (Phone Login):**
```json
{
    "type": "phone",
    "identifier": "1234567890",
    "password": "password123"
}
```

**Response (Success - 200):**
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

---

### 3. Send OTP
**Method:** `POST`  
**URL:** `{{base_url}}/auth/send-otp`  
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```

**Response (Success - 200):**
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

---

### 4. Verify OTP
**Method:** `POST`  
**URL:** `{{base_url}}/auth/verify-otp`  
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "phone": "1234567890",
    "otp": "123456"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Phone verified successfully",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "phone_validate": true
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

### 5. Resend OTP
**Method:** `POST`  
**URL:** `{{base_url}}/auth/resend-otp`  
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "phone": "1234567890",
    "country_code": "+91"
}
```

**Response (Success - 200):**
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

---

### 6. Get Profile (Protected)
**Method:** `GET`  
**URL:** `{{base_url}}/auth/profile`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None (GET request)

**Response (Success - 200):**
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
        "status": "active"
    }
}
```

---

### 7. Logout (Protected)
**Method:** `POST`  
**URL:** `{{base_url}}/auth/logout`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

---

### 8. Refresh Token (Protected)
**Method:** `POST`  
**URL:** `{{base_url}}/auth/refresh`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None

**Response (Success - 200):**
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

## 👤 Customer Profile APIs (Protected)

**All endpoints require:**
```
Headers:
    Authorization: Bearer {{access_token}}
```

---

### 9. Get Customer Profile
**Method:** `GET`  
**URL:** `{{base_url}}/customer/profile`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None (GET request)

**Response (Success - 200):**
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
        "profile_image": "https://example.com/images/profile.jpg",
        "address": "123 Main St",
        "city": "Mumbai",
        "state": "Maharashtra",
        "country": "India",
        "pincode": "400001",
        "social_media": {
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe"
        }
    }
}
```

---

### 10. Update Customer Profile
**Method:** `PUT` or `POST`  
**URL:** `{{base_url}}/customer/profile`  
**Headers:**
```
Authorization: Bearer {{access_token}}
Content-Type: application/json
```
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
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
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Doe Updated",
        "username": "johndoe_new",
        "address": "456 New Street",
        "city": "Mumbai"
    }
}
```

---

### 11. Update Password
**Method:** `PUT` or `POST`  
**URL:** `{{base_url}}/customer/password`  
**Headers:**
```
Authorization: Bearer {{access_token}}
Content-Type: application/json
```
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Password updated successfully"
}
```

---

### 12. Update Profile Image
**Method:** `POST`  
**URL:** `{{base_url}}/customer/profile-image`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```
**Body Type:** `form-data`

**Postman Body (form-data):**
```
Key: profile_image
Type: File
Value: [Select image file from your computer]
```

**Response (Success - 200):**
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
**Method:** `DELETE`  
**URL:** `{{base_url}}/customer/profile-image`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Profile image deleted successfully"
}
```

---

### 14. Update Location
**Method:** `PUT` or `POST`  
**URL:** `{{base_url}}/customer/location`  
**Headers:**
```
Authorization: Bearer {{access_token}}
Content-Type: application/json
```
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "latitude": 19.0760,
    "longitude": 72.8777,
    "address": "123 Main St, Andheri",
    "city": "Mumbai",
    "state": "Maharashtra",
    "country": "India",
    "pincode": "400001"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Location updated successfully",
    "data": {
        "latitude": "19.0760",
        "longitude": "72.8777",
        "address": "123 Main St, Andheri",
        "city": "Mumbai",
        "state": "Maharashtra",
        "country": "India",
        "pincode": "400001"
    }
}
```

---

### 15. Update Social Links
**Method:** `PUT` or `POST`  
**URL:** `{{base_url}}/customer/social-links`  
**Headers:**
```
Authorization: Bearer {{access_token}}
Content-Type: application/json
```
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "facebook": "https://facebook.com/johndoe",
    "instagram": "https://instagram.com/johndoe",
    "twitter": "https://twitter.com/johndoe",
    "linkedin": "https://linkedin.com/in/johndoe",
    "youtube": "https://youtube.com/johndoe",
    "website": "https://johndoe.com"
}
```

**Response (Success - 200):**
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
**Method:** `DELETE` or `POST`  
**URL:** `{{base_url}}/customer/account` (DELETE) or `{{base_url}}/customer/account/delete` (POST)  
**Headers:**
```
Authorization: Bearer {{access_token}}
Content-Type: application/json
```
**Body Type:** `raw (JSON)`

**Postman Body:**
```json
{
    "password": "currentpassword123"
}
```

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Account deleted successfully"
}
```

---

### 17. Get Customer by ID
**Method:** `GET`  
**URL:** `{{base_url}}/customers/1`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None (GET request)

**Response (Success - 200):**
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
        "state": "Maharashtra"
    }
}
```

---

### 18. Get Customer by Username
**Method:** `GET`  
**URL:** `{{base_url}}/customers/username/johndoe123`  
**Headers:**
```
Authorization: Bearer {{access_token}}
```

**Postman Body:** None (GET request)

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "username": "johndoe123",
        "profile_image": "https://example.com/images/profile.jpg",
        "city": "Mumbai"
    }
}
```

---

## 📂 Category APIs

### 19. Get Category Types
**Method:** `GET`  
**URL:** `{{base_url}}/category-types`

**Postman Body:** None (GET request)

**Response (Success - 200):**
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
**Method:** `GET`  
**URL:** `{{base_url}}/categories`  
**Query Parameters:**

**Postman Params Tab:**
```
category_type: product (optional)
search: electronics (optional)
```

**Example URLs:**
- Get all categories: `{{base_url}}/categories`
- Filter by type: `{{base_url}}/categories?category_type=product`
- Search: `{{base_url}}/categories?search=electronics`
- Combined: `{{base_url}}/categories?category_type=product&search=phone`

**Response (Success - 200):**
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
            "status": "active"
        },
        {
            "id": 2,
            "name": "Clothing",
            "slug": "clothing",
            "category_type": "product",
            "image": "https://example.com/images/clothing.jpg",
            "status": "active"
        }
    ]
}
```

---

### 21. Get Sub-Categories
**Method:** `GET`  
**URL:** `{{base_url}}/subcategories`  
**Query Parameters:** (Required)

**Postman Params Tab:**
```
category_id: 1 (required)
search: mobile (optional)
```

**Example URLs:**
- Get subcategories: `{{base_url}}/subcategories?category_id=1`
- With search: `{{base_url}}/subcategories?category_id=1&search=mobile`

**Response (Success - 200):**
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
            "status": "active"
        },
        {
            "id": 2,
            "category_id": 1,
            "name": "Laptops",
            "slug": "laptops",
            "image": "https://example.com/images/laptops.jpg",
            "status": "active"
        }
    ]
}
```

---

### 22. Get Child Categories
**Method:** `GET`  
**URL:** `{{base_url}}/child-categories`  
**Query Parameters:** (Required)

**Postman Params Tab:**
```
category_id: 1 (required)
sub_category_id: 1 (required)
search: android (optional)
```

**Example URLs:**
- Get child categories: `{{base_url}}/child-categories?category_id=1&sub_category_id=1`
- With search: `{{base_url}}/child-categories?category_id=1&sub_category_id=1&search=android`

**Response (Success - 200):**
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
            "status": "active"
        },
        {
            "id": 2,
            "category_id": 1,
            "sub_category_id": 1,
            "name": "iOS Phones",
            "slug": "ios-phones",
            "image": "https://example.com/images/ios.jpg",
            "status": "active"
        }
    ]
}
```

---

## 🏥 Health Check API

### 23. Health Check
**Method:** `GET`  
**URL:** `http://localhost:8000/api/health`

**Postman Body:** None (GET request)

**Response (Success - 200):**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2026-01-26 20:58:46"
}
```

---

## 📝 Postman Environment Setup

Create a Postman Environment with these variables:

```
base_url: http://localhost:8000/api/v1
access_token: (will be set after login)
```

### How to Set Up:

1. **Create Environment:**
   - Click on "Environments" in Postman
   - Click "+" to create new environment
   - Name it "BMV Local"
   - Add variables:
     - `base_url` = `http://localhost:8000/api/v1`
     - `access_token` = (leave empty initially)

2. **Auto-Set Token After Login:**
   - In the Login request, go to "Tests" tab
   - Add this script:
   ```javascript
   var jsonData = pm.response.json();
   if (jsonData.success && jsonData.data.access_token) {
       pm.environment.set("access_token", jsonData.data.access_token);
   }
   ```

3. **Use Variables in Requests:**
   - URL: `{{base_url}}/auth/login`
   - Headers: `Authorization: Bearer {{access_token}}`

---

## 🔧 Common Postman Settings

### For JSON Requests:
```
Headers:
    Content-Type: application/json
    
Body:
    raw (JSON)
```

### For File Uploads:
```
Headers:
    Authorization: Bearer {{access_token}}
    (Don't set Content-Type, Postman will auto-set it)
    
Body:
    form-data
```

### For Protected Routes:
```
Headers:
    Authorization: Bearer {{access_token}}
```

---

## ❌ Common Error Responses

### Validation Error (422):
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

### Unauthorized (401):
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### Not Found (404):
```json
{
    "success": false,
    "message": "Customer not found"
}
```

### Server Error (500):
```json
{
    "success": false,
    "message": "Registration failed. Please try again."
}
```

---

## 📋 Quick Testing Checklist

### 1. Test Registration:
```
POST {{base_url}}/auth/register
Body: {name, email, password, password_confirmation, phone, country_code}
```

### 2. Test Login:
```
POST {{base_url}}/auth/login
Body: {type: "email", identifier, password}
Save token from response to environment
```

### 3. Test Protected Route:
```
GET {{base_url}}/auth/profile
Headers: Authorization: Bearer {{access_token}}
```

### 4. Test Categories:
```
GET {{base_url}}/categories
GET {{base_url}}/subcategories?category_id=1
```

---

## 💡 Tips for Postman

1. **Use Collections:** Organize all endpoints in a collection
2. **Use Environments:** Switch between local/staging/production
3. **Use Pre-request Scripts:** Auto-generate timestamps, tokens
4. **Use Tests:** Auto-save tokens, validate responses
5. **Use Variables:** Reuse common values like base_url, tokens
6. **Save Examples:** Save successful responses as examples

---

## 🔗 Import to Postman

You can create a Postman Collection JSON file with all these endpoints. Would you like me to generate a complete Postman Collection JSON file that you can directly import?
