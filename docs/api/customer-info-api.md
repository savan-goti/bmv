# Customer Info API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
All customer info endpoints require JWT authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your-jwt-token}
```

---

## Endpoints

### 1. Get Customer Profile
Get the authenticated customer's profile information.

**Endpoint:** `GET /customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 1,
        "profile_image": "uploads/customers/1234567890_abc123.jpg",
        "canonical": "johndoe_a1b2c3d4",
        "username": "johndoe",
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "1234567890",
        "country_code": "+1",
        "phone_validate": true,
        "gender": "male",
        "dob": "1990-01-01",
        "address": "123 Main St",
        "latitude": "40.7128",
        "longitude": "-74.0060",
        "city": "New York",
        "state": "NY",
        "country": "USA",
        "pincode": "10001",
        "social_links": {
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe",
            "twitter": "https://twitter.com/johndoe"
        },
        "status": "active",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 2. Update Customer Profile
Update the authenticated customer's profile information.

**Endpoint:** `PUT /customer/profile` or `POST /customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data (if uploading image)
Accept: application/json
```

**Request Body (Form Data):**
```
profile_image: (file) - Optional, max 2MB, jpeg/png/jpg/gif
username: (string) - Optional, alphanumeric and underscores only
name: (string) - Required
email: (string) - Required, must be valid email
phone: (string) - Optional
country_code: (string) - Optional, max 10 chars
gender: (string) - Optional, male/female/other
dob: (date) - Optional, format: YYYY-MM-DD, must be in the past
address: (string) - Optional
latitude: (decimal) - Optional, between -90 and 90
longitude: (decimal) - Optional, between -180 and 180
city: (string) - Optional
state: (string) - Optional
country: (string) - Optional
pincode: (string) - Optional
whatsapp: (string) - Optional
website: (url) - Optional
facebook: (url) - Optional
instagram: (url) - Optional
linkedin: (url) - Optional
youtube: (url) - Optional
telegram: (url) - Optional
twitter: (url) - Optional
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Doe Updated",
        "email": "john.updated@example.com",
        ...
    }
}
```

**Error Response (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "email": ["This email is already registered"],
        "username": ["This username is already taken"]
    }
}
```

---

### 3. Update Password
Change the authenticated customer's password.

**Endpoint:** `PUT /customer/password` or `POST /customer/password`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body (JSON):**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Password updated successfully",
    "data": null
}
```

**Error Response (400 Bad Request):**
```json
{
    "success": false,
    "message": "Current password is incorrect"
}
```

---

### 4. Update Profile Image
Upload or update the customer's profile image.

**Endpoint:** `POST /customer/profile-image`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (Form Data):**
```
profile_image: (file) - Required, max 2MB, jpeg/png/jpg/gif
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Profile image updated successfully",
    "data": {
        "profile_image": "uploads/customers/1234567890_abc123.jpg",
        "profile_image_url": "http://your-domain.com/uploads/customers/1234567890_abc123.jpg"
    }
}
```

---

### 5. Delete Profile Image
Remove the customer's profile image.

**Endpoint:** `DELETE /customer/profile-image`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Profile image deleted successfully",
    "data": null
}
```

---

### 6. Update Location
Update the customer's location information.

**Endpoint:** `PUT /customer/location` or `POST /customer/location`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body (JSON):**
```json
{
    "latitude": 40.7128,
    "longitude": -74.0060,
    "address": "123 Main St, Apt 4B",
    "city": "New York",
    "state": "NY",
    "country": "USA",
    "pincode": "10001"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Location updated successfully",
    "data": {
        "latitude": "40.7128",
        "longitude": "-74.0060",
        "address": "123 Main St, Apt 4B",
        "city": "New York",
        "state": "NY",
        "country": "USA",
        "pincode": "10001"
    }
}
```

---

### 7. Update Social Links
Update the customer's social media links.

**Endpoint:** `PUT /customer/social-links` or `POST /customer/social-links`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body (JSON):**
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

**Response (200 OK):**
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

---

### 8. Delete Account
Permanently delete the customer's account (soft delete).

**Endpoint:** `DELETE /customer/account` or `POST /customer/account/delete`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body (JSON):**
```json
{
    "password": "yourpassword123"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Account deleted successfully",
    "data": null
}
```

**Error Response (400 Bad Request):**
```json
{
    "success": false,
    "message": "Password is incorrect"
}
```

---

### 9. Get Customer by ID
Get public profile information of a customer by their ID.

**Endpoint:** `GET /customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 1,
        "username": "johndoe",
        "name": "John Doe",
        "profile_image": "uploads/customers/1234567890_abc123.jpg",
        "profile_image_url": "http://your-domain.com/uploads/customers/1234567890_abc123.jpg",
        "canonical": "johndoe_a1b2c3d4",
        "social_links": {
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe"
        },
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Error Response (404 Not Found):**
```json
{
    "success": false,
    "message": "Customer not found"
}
```

---

### 10. Get Customer by Username
Get public profile information of a customer by their username.

**Endpoint:** `GET /customers/username/{username}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Customer profile retrieved successfully",
    "data": {
        "id": 1,
        "username": "johndoe",
        "name": "John Doe",
        "profile_image": "uploads/customers/1234567890_abc123.jpg",
        "profile_image_url": "http://your-domain.com/uploads/customers/1234567890_abc123.jpg",
        "canonical": "johndoe_a1b2c3d4",
        "social_links": {
            "facebook": "https://facebook.com/johndoe",
            "instagram": "https://instagram.com/johndoe"
        },
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {
        "field_name": ["Error message 1", "Error message 2"]
    }
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Failed to update profile: Error details here"
}
```

---

## Testing with cURL

### Get Profile
```bash
curl -X GET http://your-domain.com/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Accept: application/json"
```

### Update Profile
```bash
curl -X POST http://your-domain.com/api/v1/customer/profile \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Accept: application/json" \
  -F "name=John Doe Updated" \
  -F "email=john.updated@example.com" \
  -F "phone=9876543210" \
  -F "profile_image=@/path/to/image.jpg"
```

### Update Password
```bash
curl -X POST http://your-domain.com/api/v1/customer/password \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
  }'
```

### Update Location
```bash
curl -X POST http://your-domain.com/api/v1/customer/location \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
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

### Delete Account
```bash
curl -X DELETE http://your-domain.com/api/v1/customer/account \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "password": "yourpassword123"
  }'
```

---

## Testing with Postman

1. **Set Base URL:** `http://your-domain.com/api/v1`
2. **Add Authorization Header:**
   - Type: Bearer Token
   - Token: Your JWT token from login/register
3. **Test Each Endpoint:** Follow the request body formats above

---

## Notes

- All endpoints return JSON responses
- File uploads use `multipart/form-data` content type
- Other requests use `application/json` content type
- JWT tokens expire based on your configuration (default: 60 minutes)
- Use the refresh token endpoint to get a new token
- Profile images are stored in `public/uploads/customers/`
- Maximum image size: 2MB
- Supported image formats: JPEG, PNG, JPG, GIF
- Account deletion is soft delete (can be restored from database)
- Social links are stored as JSON in the database
- Location coordinates use decimal format with 8 decimal places
