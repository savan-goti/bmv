# BMV API Index - All Endpoints Summary

**Total APIs:** 20  
**Base URL:** `http://localhost:8000/api/v1`  
**Last Updated:** 2026-01-19

---

## Complete API List

| # | Category | Endpoint | Method | Auth | Description |
|---|----------|----------|--------|------|-------------|
| 1 | Auth | `/auth/register` | POST | ❌ | Register new customer account |
| 2 | Auth | `/auth/login` | POST | ❌ | Login with email or phone |
| 3 | Auth | `/auth/profile` | GET | ✅ | Get authenticated customer profile |
| 4 | Auth | `/auth/logout` | POST | ✅ | Logout and invalidate token |
| 5 | Auth | `/auth/refresh` | POST | ✅ | Refresh authentication token |
| 6 | Customer | `/customer/profile` | GET | ✅ | Get customer profile details |
| 7 | Customer | `/customer/profile` | PUT/POST | ✅ | Update customer profile |
| 8 | Customer | `/customer/password` | PUT/POST | ✅ | Update customer password |
| 9 | Customer | `/customer/profile-image` | POST | ✅ | Upload/update profile image |
| 10 | Customer | `/customer/profile-image` | DELETE | ✅ | Delete profile image |
| 11 | Customer | `/customer/location` | PUT/POST | ✅ | Update location information |
| 12 | Customer | `/customer/social-links` | PUT/POST | ✅ | Update social media links |
| 13 | Customer | `/customer/account` | DELETE | ✅ | Delete customer account |
| 14 | Customer | `/customer/account/delete` | POST | ✅ | Delete customer account (alt) |
| 15 | Customer | `/customers/{id}` | GET | ✅ | Get public profile by ID |
| 16 | Customer | `/customers/username/{username}` | GET | ✅ | Get public profile by username |
| 17 | Category | `/category-types` | GET | ❌ | Get all category types |
| 18 | Category | `/categories` | GET | ❌ | Get all active categories |
| 19 | Category | `/subcategories` | GET | ❌ | Get all active sub-categories |
| 20 | Category | `/child-categories` | GET | ❌ | Get all active child categories |

---

## API Parameters Summary

### 1. Register Customer
**Endpoint:** `POST /auth/register`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| name | string | ✅ | min:2, max:255 |
| email | string | ✅ | valid email, unique |
| password | string | ✅ | min:8, confirmed |
| password_confirmation | string | ✅ | must match password |
| phone | string | ✅ | min:10, max:20, unique |
| country_code | string | ✅ | max:5 |
| gender | string | ❌ | in:male,female,other |
| dob | date | ❌ | before:today |

**Output:** Customer object + JWT token

---

### 2. Login Customer
**Endpoint:** `POST /auth/login`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| type | string | ✅ | in:email,phone |
| identifier | string | ✅ | email or phone |
| password | string | ✅ | min:8 |

**Output:** Customer object + JWT token

---

### 3. Get Profile
**Endpoint:** `GET /auth/profile`

**Parameters:** None

**Output:** Complete customer profile

---

### 4. Logout
**Endpoint:** `POST /auth/logout`

**Parameters:** None

**Output:** Success message

---

### 5. Refresh Token
**Endpoint:** `POST /auth/refresh`

**Parameters:** None

**Output:** New JWT token

---

### 6. Get Customer Profile
**Endpoint:** `GET /customer/profile`

**Parameters:** None

**Output:** Complete customer profile with all fields

---

### 7. Update Customer Profile
**Endpoint:** `PUT /customer/profile` or `POST /customer/profile`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| profile_image | file | ❌ | image, max:2MB |
| username | string | ❌ | alphanumeric+underscore, unique |
| name | string | ✅ | max:255 |
| email | string | ✅ | valid email, unique |
| phone | string | ❌ | unique |
| country_code | string | ❌ | max:10 |
| gender | string | ❌ | in:male,female,other |
| dob | date | ❌ | before:today |
| address | string | ❌ | - |
| latitude | numeric | ❌ | -90 to 90 |
| longitude | numeric | ❌ | -180 to 180 |
| city | string | ❌ | max:255 |
| state | string | ❌ | max:255 |
| country | string | ❌ | max:255 |
| pincode | string | ❌ | max:20 |
| whatsapp | string | ❌ | max:255 |
| website | string | ❌ | valid url, max:255 |
| facebook | string | ❌ | valid url, max:255 |
| instagram | string | ❌ | valid url, max:255 |
| linkedin | string | ❌ | valid url, max:255 |
| youtube | string | ❌ | valid url, max:255 |
| telegram | string | ❌ | valid url, max:255 |
| twitter | string | ❌ | valid url, max:255 |

**Output:** Updated customer profile

---

### 8. Update Password
**Endpoint:** `PUT /customer/password` or `POST /customer/password`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| current_password | string | ✅ | - |
| new_password | string | ✅ | min:8, confirmed |
| new_password_confirmation | string | ✅ | must match new_password |

**Output:** Success message

---

### 9. Update Profile Image
**Endpoint:** `POST /customer/profile-image`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| profile_image | file | ✅ | image, jpeg/png/jpg/gif, max:2MB |

**Output:** Image path and URL

---

### 10. Delete Profile Image
**Endpoint:** `DELETE /customer/profile-image`

**Parameters:** None

**Output:** Success message

---

### 11. Update Location
**Endpoint:** `PUT /customer/location` or `POST /customer/location`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| latitude | numeric | ✅ | -90 to 90 |
| longitude | numeric | ✅ | -180 to 180 |
| address | string | ❌ | - |
| city | string | ❌ | max:255 |
| state | string | ❌ | max:255 |
| country | string | ❌ | max:255 |
| pincode | string | ❌ | max:20 |

**Output:** Updated location data

---

### 12. Update Social Links
**Endpoint:** `PUT /customer/social-links` or `POST /customer/social-links`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| whatsapp | string | ❌ | max:255 |
| website | string | ❌ | valid url, max:255 |
| facebook | string | ❌ | valid url, max:255 |
| instagram | string | ❌ | valid url, max:255 |
| linkedin | string | ❌ | valid url, max:255 |
| youtube | string | ❌ | valid url, max:255 |
| telegram | string | ❌ | valid url, max:255 |
| twitter | string | ❌ | valid url, max:255 |

**Output:** Updated social links

---

### 13-14. Delete Account
**Endpoint:** `DELETE /customer/account` or `POST /customer/account/delete`

| Parameter | Type | Required | Validation |
|-----------|------|----------|------------|
| password | string | ✅ | - |

**Output:** Success message

---

### 15. Get Customer by ID
**Endpoint:** `GET /customers/{id}`

| Parameter | Type | Required | Location |
|-----------|------|----------|----------|
| id | integer | ✅ | URL path |

**Output:** Public customer profile

---

### 16. Get Customer by Username
**Endpoint:** `GET /customers/username/{username}`

| Parameter | Type | Required | Location |
|-----------|------|----------|----------|
| username | string | ✅ | URL path |

**Output:** Public customer profile

---

### 17. Get Category Types
**Endpoint:** `GET /category-types`

**Parameters:** None

**Output:** Array of category types (product, service)

---

### 18. Get Categories
**Endpoint:** `GET /categories`

| Parameter | Type | Required | Location |
|-----------|------|----------|----------|
| category_type | string | ❌ | Query string |
| search | string | ❌ | Query string |

**Output:** Array of active categories

---

### 19. Get Sub-Categories
**Endpoint:** `GET /subcategories`

| Parameter | Type | Required | Location |
|-----------|------|----------|----------|
| category_id | integer | ❌ | Query string |
| search | string | ❌ | Query string |

**Output:** Array of active sub-categories

---

### 20. Get Child Categories
**Endpoint:** `GET /child-categories`

| Parameter | Type | Required | Location |
|-----------|------|----------|----------|
| category_id | integer | ❌ | Query string |
| sub_category_id | integer | ❌ | Query string |
| search | string | ❌ | Query string |

**Output:** Array of active child categories

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

### Validation Error
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

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Authentication

Protected endpoints require JWT token in header:
```
Authorization: Bearer {your_token}
```

Token expires in: **60 minutes**  
Refresh token expires in: **14 days**

---

**Document Version:** 1.0  
**Generated:** 2026-01-19
