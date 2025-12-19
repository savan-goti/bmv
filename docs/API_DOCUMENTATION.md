# Customer API Documentation - JWT Authentication

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
All protected endpoints require a JWT token in the Authorization header:
```
Authorization: Bearer {your-jwt-token}
```

---

## API Endpoints

### 1. Health Check
Check if the API is running.

**Endpoint:** `GET /api/health`

**Headers:** None required

**Response:**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2025-12-19 22:17:00"
}
```

---

### 2. Customer Registration
Register a new customer account with enhanced validation and automatic username generation.

**Endpoint:** `POST /api/v1/auth/register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
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
    "dob": "1990-01-15"
}
```

**Required Fields:**
- `name` (string, min: 2, max: 255)
- `email` (string, email, unique, max: 255)
- `password` (string, min: 8)
- `password_confirmation` (string, must match password)

**Optional Fields:**
- `phone` (string, min: 10, max: 20, unique) - Phone number
- `country_code` (string, max: 5) - Default: +91
- `username` (string, min: 3, max: 50, unique, alphanumeric with underscores) - Auto-generated if not provided
- `gender` (enum: male, female, other)
- `dob` (date, must be in the past) - Date of birth (YYYY-MM-DD)

**Features:**
- ✅ Automatic username generation from name if not provided
- ✅ Unique canonical identifier generation
- ✅ Email normalization (lowercase, trimmed)
- ✅ Phone number uniqueness validation
- ✅ Custom validation error messages
- ✅ Sensitive data hidden in response

**Success Response (201):**
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
            "canonical": "johndoe_a1b2c3d4",
            "phone": "1234567890",
            "country_code": "+1",
            "gender": "male",
            "dob": "1990-01-15",
            "status": 1,
            "phone_validate": false,
            "profile_image": null,
            "address": null,
            "city": null,
            "state": null,
            "country": null,
            "pincode": null,
            "created_at": "2025-12-19T22:17:00.000000Z",
            "updated_at": "2025-12-19T22:17:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600,
        "refresh_expires_in": 1209600
    }
}
```

**Error Response (422) - Validation Error:**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["This email is already registered"],
        "password": ["Password confirmation does not match"],
        "phone": ["This phone number is already registered"],
        "username": ["This username is already taken"],
        "dob": ["Date of birth must be in the past"]
    }
}
```

**Error Response (500) - Server Error:**
```json
{
    "success": false,
    "message": "Registration failed. Please try again.",
    "error": "Internal server error"
}
```

**Username Generation Rules:**
- Converts name to lowercase
- Replaces special characters with underscores
- Removes multiple consecutive underscores
- Ensures minimum 3 characters
- Appends counter if username exists (e.g., john_doe_1, john_doe_2)

**Example Usernames:**
- "John Doe" → `john_doe`
- "Jane Smith" → `jane_smith`
- "John Doe" (duplicate) → `john_doe_1`
- "A B" → `user_a_b`


---

### 3. Customer Login
Authenticate and receive a JWT token.

**Endpoint:** `POST /api/v1/auth/login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Required Fields:**
- `email` (string, email)
- `password` (string, min: 8)

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "1234567890",
            "country_code": "+1",
            "status": 1,
            "created_at": "2025-12-19T22:17:00.000000Z",
            "updated_at": "2025-12-19T22:17:00.000000Z"
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

**Error Response (403) - Inactive Account:**
```json
{
    "success": false,
    "message": "Your account is inactive. Please contact support."
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

### 4. Get Customer Profile
Get the authenticated customer's profile information.

**Endpoint:** `GET /api/v1/auth/profile`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {your-jwt-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "1234567890",
        "country_code": "+1",
        "gender": null,
        "dob": null,
        "address": null,
        "city": null,
        "state": null,
        "country": null,
        "pincode": null,
        "status": 1,
        "created_at": "2025-12-19T22:17:00.000000Z",
        "updated_at": "2025-12-19T22:17:00.000000Z"
    }
}
```

**Error Response (401):**
```json
{
    "message": "Unauthenticated."
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "User not found"
}
```

---

### 5. Refresh Token
Refresh the JWT token to extend the session.

**Endpoint:** `POST /api/v1/auth/refresh`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {your-jwt-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            ...
        },
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

**Error Response (500):**
```json
{
    "success": false,
    "message": "Could not refresh token"
}
```

---

### 6. Customer Logout
Invalidate the current JWT token.

**Endpoint:** `POST /api/v1/auth/logout`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {your-jwt-token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

**Error Response (500):**
```json
{
    "success": false,
    "message": "Failed to logout, please try again"
}
```

---

## Testing with cURL

### Register a new customer:
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "1234567890",
    "country_code": "+1",
    "username": "johndoe",
    "gender": "male",
    "dob": "1990-01-15"
  }'
```

### Login:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Profile (replace YOUR_TOKEN with actual token):
```bash
curl -X GET http://localhost:8000/api/v1/auth/profile \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Refresh Token:
```bash
curl -X POST http://localhost:8000/api/v1/auth/refresh \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Logout:
```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Testing with Postman

1. **Import the collection** or create requests manually
2. **Set environment variables:**
   - `base_url`: `http://localhost:8000/api/v1`
   - `token`: (will be set automatically after login)

3. **After login/register**, save the token from response:
   - Go to Tests tab in Postman
   - Add this script:
   ```javascript
   if (pm.response.code === 200 || pm.response.code === 201) {
       var jsonData = pm.response.json();
       pm.environment.set("token", jsonData.data.access_token);
   }
   ```

4. **For protected routes**, use this in Authorization tab:
   - Type: Bearer Token
   - Token: `{{token}}`

---

## JWT Configuration

The JWT tokens are configured with the following defaults:

- **Token Lifetime (TTL):** 60 minutes (1 hour)
- **Refresh Token Lifetime:** 20160 minutes (2 weeks)
- **Algorithm:** HS256
- **Blacklist:** Enabled (tokens are invalidated on logout)

You can modify these settings in `config/jwt.php` or via environment variables:
```env
JWT_SECRET=your-secret-key
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256
JWT_BLACKLIST_ENABLED=true
```

---

## Error Codes

- **200** - Success
- **201** - Created (Registration successful)
- **401** - Unauthorized (Invalid credentials or token)
- **403** - Forbidden (Account inactive)
- **404** - Not Found
- **422** - Validation Error
- **500** - Server Error

---

## Security Notes

1. Always use HTTPS in production
2. Store JWT tokens securely (not in localStorage for web apps)
3. Implement rate limiting for login/register endpoints
4. Regularly rotate JWT secrets
5. Set appropriate CORS policies
6. Monitor for suspicious login attempts
7. Implement refresh token rotation for enhanced security
