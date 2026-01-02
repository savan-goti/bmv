# Customer Info API - Quick Mapping Table

## 📋 All Endpoints at a Glance

### Authentication Endpoints (No Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 1 | POST | `/auth/register` | `name, email, password, password_confirmation, phone, country_code, username, gender, dob` | `{customer, access_token, token_type, expires_in}` |
| 2 | POST | `/auth/login` | `email, password` | `{customer, access_token, token_type, expires_in}` |

### Authentication Endpoints (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 3 | POST | `/auth/logout` | None | `{success: true, message}` |
| 4 | POST | `/auth/refresh` | None | `{customer, access_token, token_type, expires_in}` |
| 5 | GET | `/auth/profile` | None | `{customer}` |

### Profile Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 6 | GET | `/customer/profile` | None | `{id, profile_image, username, name, email, phone, gender, dob, address, latitude, longitude, city, state, country, pincode, social_links, status, created_at, updated_at}` |
| 7 | PUT | `/customer/profile` | `username, name, email, phone, country_code, gender, dob, address, latitude, longitude, city, state, country, pincode, whatsapp, website, facebook, instagram, linkedin, youtube, telegram, twitter` (JSON) | `{customer}` |
| 8 | POST | `/customer/profile` | Same as PUT + `profile_image` (file) (Multipart) | `{customer}` |

### Password Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 9 | PUT | `/customer/password` | `current_password, new_password, new_password_confirmation` | `{success: true, message, data: null}` |
| 10 | POST | `/customer/password` | Same as PUT | Same as PUT |

### Image Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 11 | POST | `/customer/profile-image` | `profile_image` (file, max 2MB) | `{profile_image, profile_image_url}` |
| 12 | DELETE | `/customer/profile-image` | None | `{success: true, message, data: null}` |

### Location Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 13 | PUT | `/customer/location` | `latitude, longitude, address, city, state, country, pincode` | `{latitude, longitude, address, city, state, country, pincode}` |
| 14 | POST | `/customer/location` | Same as PUT | Same as PUT |

### Social Links Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 15 | PUT | `/customer/social-links` | `whatsapp, website, facebook, instagram, linkedin, youtube, telegram, twitter` | `{social_links}` |
| 16 | POST | `/customer/social-links` | Same as PUT | Same as PUT |

### Account Management (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 17 | DELETE | `/customer/account` | `password` | `{success: true, message, data: null}` |
| 18 | POST | `/customer/account/delete` | `password` | Same as DELETE |

### Public Profiles (Auth Required)

| # | Method | URL | Form Data | Success Output |
|---|--------|-----|-----------|----------------|
| 19 | GET | `/customers/{id}` | None | `{id, username, name, profile_image, profile_image_url, canonical, social_links, created_at}` |
| 20 | GET | `/customers/username/{username}` | None | Same as above |

---

## 🔑 Field Details

### Registration/Login Fields
```
name: string (required, max:255)
email: string (required, valid email, unique)
password: string (required, min:8, confirmed)
phone: string (optional, unique)
country_code: string (optional, max:10)
username: string (optional, alphanumeric+underscore, unique)
gender: enum (optional, male/female/other)
dob: date (optional, YYYY-MM-DD, past date)
```

### Profile Update Fields
```
All registration fields +
address: string (optional)
latitude: decimal (optional, -90 to 90)
longitude: decimal (optional, -180 to 180)
city: string (optional, max:255)
state: string (optional, max:255)
country: string (optional, max:255)
pincode: string (optional, max:20)
profile_image: file (optional, max:2MB, jpeg/png/jpg/gif)
```

### Social Links Fields
```
whatsapp: string (optional, max:255)
website: url (optional, max:255)
facebook: url (optional, max:255)
instagram: url (optional, max:255)
linkedin: url (optional, max:255)
youtube: url (optional, max:255)
telegram: url (optional, max:255)
twitter: url (optional, max:255)
```

### Password Change Fields
```
current_password: string (required)
new_password: string (required, min:8, confirmed)
new_password_confirmation: string (required)
```

### Location Fields
```
latitude: decimal (required, -90 to 90)
longitude: decimal (required, -180 to 180)
address: string (optional)
city: string (optional, max:255)
state: string (optional, max:255)
country: string (optional, max:255)
pincode: string (optional, max:20)
```

---

## 📤 Response Structure

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

## 🎯 HTTP Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST (registration) |
| 400 | Bad Request | Invalid password, etc. |
| 401 | Unauthorized | Missing/invalid JWT token |
| 403 | Forbidden | Account blocked |
| 404 | Not Found | Customer not found |
| 422 | Unprocessable Entity | Validation errors |
| 500 | Internal Server Error | Server error |

---

## 🔐 Authentication Header

All endpoints except `/auth/register` and `/auth/login` require:

```
Authorization: Bearer {your_jwt_token}
```

Get token from:
- POST `/auth/register` → Returns `access_token`
- POST `/auth/login` → Returns `access_token`
- POST `/auth/refresh` → Returns new `access_token`

---

## 📝 Content-Type Headers

### JSON Requests
```
Content-Type: application/json
Accept: application/json
```

### File Upload Requests (Multipart)
```
Content-Type: multipart/form-data
Accept: application/json
```

---

## 🚀 Quick Test Examples

### 1. Register & Login
```bash
# Register
curl -X POST http://localhost/bmv/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@test.com","password":"pass1234","password_confirmation":"pass1234"}'

# Login
curl -X POST http://localhost/bmv/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@test.com","password":"pass1234"}'
```

### 2. Get & Update Profile
```bash
# Get Profile
curl -X GET http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer TOKEN"

# Update Profile (JSON)
curl -X PUT http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"John Updated","phone":"1234567890"}'

# Update Profile (with Image)
curl -X POST http://localhost/bmv/api/v1/customer/profile \
  -H "Authorization: Bearer TOKEN" \
  -F "name=John Updated" \
  -F "profile_image=@image.jpg"
```

### 3. Password & Image
```bash
# Change Password
curl -X PUT http://localhost/bmv/api/v1/customer/password \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"current_password":"pass1234","new_password":"newpass1234","new_password_confirmation":"newpass1234"}'

# Upload Image
curl -X POST http://localhost/bmv/api/v1/customer/profile-image \
  -H "Authorization: Bearer TOKEN" \
  -F "profile_image=@photo.jpg"

# Delete Image
curl -X DELETE http://localhost/bmv/api/v1/customer/profile-image \
  -H "Authorization: Bearer TOKEN"
```

### 4. Location & Social Links
```bash
# Update Location
curl -X PUT http://localhost/bmv/api/v1/customer/location \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"latitude":40.7128,"longitude":-74.0060,"city":"New York"}'

# Update Social Links
curl -X PUT http://localhost/bmv/api/v1/customer/social-links \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"facebook":"https://facebook.com/john","instagram":"https://instagram.com/john"}'
```

### 5. Public Profiles & Delete
```bash
# Get Customer by ID
curl -X GET http://localhost/bmv/api/v1/customers/1 \
  -H "Authorization: Bearer TOKEN"

# Get Customer by Username
curl -X GET http://localhost/bmv/api/v1/customers/username/johndoe \
  -H "Authorization: Bearer TOKEN"

# Delete Account
curl -X DELETE http://localhost/bmv/api/v1/customer/account \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"password":"pass1234"}'
```

---

## 📊 Endpoint Count Summary

- **Total Endpoints:** 20
- **Authentication:** 5 (2 public, 3 protected)
- **Profile Management:** 3
- **Password Management:** 2
- **Image Management:** 2
- **Location Management:** 2
- **Social Links:** 2
- **Account Management:** 2
- **Public Profiles:** 2

---

## ✅ Testing Checklist

- [ ] Register new customer → Get token
- [ ] Login → Get token
- [ ] Get profile → Verify data
- [ ] Update profile (JSON) → Verify changes
- [ ] Update profile (with image) → Check image uploaded
- [ ] Change password → Login with new password
- [ ] Upload profile image → Verify URL
- [ ] Delete profile image → Verify removed
- [ ] Update location → Check coordinates
- [ ] Update social links → Verify links
- [ ] Get customer by ID → Check public data
- [ ] Get customer by username → Check public data
- [ ] Delete account → Verify soft delete
- [ ] Logout → Token invalidated
- [ ] Refresh token → Get new token

---

**Base URL:** `http://localhost/bmv/api/v1`  
**Version:** 1.0  
**Last Updated:** 2026-01-02
