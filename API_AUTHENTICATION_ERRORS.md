# API Authentication Error Handling

## Overview
This document explains how authentication errors are handled in the BMV API when access tokens are missing, invalid, or expired.

## Authentication Error Types

### 1. **Token Not Found** (401 Unauthorized)
**When it occurs:** When no access token is provided in the request header.

**Response:**
```json
{
    "success": false,
    "message": "Access token not found. Please provide a valid token.",
    "error": "token_not_found"
}
```

**How to fix:** Include the access token in the Authorization header:
```
Authorization: Bearer YOUR_ACCESS_TOKEN_HERE
```

---

### 2. **Token Invalid** (401 Unauthorized)
**When it occurs:** When the provided access token is malformed or invalid.

**Response:**
```json
{
    "success": false,
    "message": "Access token is invalid. Please login again.",
    "error": "token_invalid"
}
```

**How to fix:** Login again to get a new valid access token.

---

### 3. **Token Expired** (401 Unauthorized)
**When it occurs:** When the access token has passed its expiration time.

**Response:**
```json
{
    "success": false,
    "message": "Access token has expired. Please refresh your token or login again.",
    "error": "token_expired"
}
```

**How to fix:** 
- Use the `/api/v1/auth/refresh` endpoint to get a new token
- Or login again to get a new access token

---

### 4. **Unauthorized** (401 Unauthorized)
**When it occurs:** When trying to access a protected endpoint without proper authentication.

**Response:**
```json
{
    "success": false,
    "message": "Unauthorized. Access token is required for this endpoint.",
    "error": "unauthorized"
}
```

**How to fix:** Ensure you're sending a valid access token in the Authorization header.

---

### 5. **Unauthenticated** (401 Unauthorized)
**When it occurs:** General authentication failure.

**Response:**
```json
{
    "success": false,
    "message": "Authentication required. Please login to access this resource.",
    "error": "unauthenticated"
}
```

**How to fix:** Login to obtain an access token before accessing protected resources.

---

## Protected Endpoints

The following endpoints require authentication (access token):

### Auth Endpoints
- `POST /api/v1/auth/logout`
- `POST /api/v1/auth/refresh`
- `GET /api/v1/auth/profile`

### Customer Endpoints
- `GET /api/v1/customer/profile`
- `PUT /api/v1/customer/profile`
- `POST /api/v1/customer/profile`
- `PUT /api/v1/customer/password`
- `POST /api/v1/customer/password`
- `POST /api/v1/customer/profile-image`
- `DELETE /api/v1/customer/profile-image`
- `PUT /api/v1/customer/location`
- `POST /api/v1/customer/location`
- `PUT /api/v1/customer/social-links`
- `POST /api/v1/customer/social-links`
- `DELETE /api/v1/customer/account`
- `POST /api/v1/customer/account/delete`
- `GET /api/v1/customers/{id}`
- `GET /api/v1/customers/username/{username}`

---

## How to Include Access Token

### Using cURL
```bash
curl -X GET "http://your-domain.com/api/v1/auth/profile" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN_HERE" \
  -H "Accept: application/json"
```

### Using Postman
1. Go to the **Authorization** tab
2. Select **Type**: Bearer Token
3. Paste your access token in the **Token** field

### Using JavaScript (Fetch API)
```javascript
fetch('http://your-domain.com/api/v1/auth/profile', {
    method: 'GET',
    headers: {
        'Authorization': 'Bearer YOUR_ACCESS_TOKEN_HERE',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => {
    if (error.error === 'token_expired') {
        // Refresh token or redirect to login
    }
});
```

### Using Axios
```javascript
axios.get('http://your-domain.com/api/v1/auth/profile', {
    headers: {
        'Authorization': 'Bearer YOUR_ACCESS_TOKEN_HERE',
        'Accept': 'application/json'
    }
})
.then(response => console.log(response.data))
.catch(error => {
    if (error.response.status === 401) {
        // Handle authentication error
        console.log(error.response.data.message);
    }
});
```

---

## Token Refresh Flow

When you receive a `token_expired` error, you can refresh your token:

**Request:**
```http
POST /api/v1/auth/refresh
Authorization: Bearer YOUR_EXPIRED_TOKEN
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": { ... },
        "access_token": "NEW_ACCESS_TOKEN",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

## Best Practices

1. **Store tokens securely**: Never store tokens in localStorage if dealing with sensitive data. Use httpOnly cookies or secure storage.

2. **Handle token expiration gracefully**: Implement automatic token refresh before expiration.

3. **Clear tokens on logout**: Always clear stored tokens when the user logs out.

4. **Implement retry logic**: When receiving a `token_expired` error, automatically refresh the token and retry the request.

5. **Use HTTPS**: Always use HTTPS in production to prevent token interception.

---

## Example Error Handling Implementation

```javascript
// Axios interceptor for handling authentication errors
axios.interceptors.response.use(
    response => response,
    async error => {
        const originalRequest = error.config;
        
        if (error.response.status === 401) {
            const errorType = error.response.data.error;
            
            if (errorType === 'token_expired' && !originalRequest._retry) {
                originalRequest._retry = true;
                
                try {
                    // Refresh the token
                    const response = await axios.post('/api/v1/auth/refresh');
                    const newToken = response.data.data.access_token;
                    
                    // Update token in storage
                    localStorage.setItem('access_token', newToken);
                    
                    // Retry original request with new token
                    originalRequest.headers['Authorization'] = 'Bearer ' + newToken;
                    return axios(originalRequest);
                } catch (refreshError) {
                    // Refresh failed, redirect to login
                    window.location.href = '/login';
                    return Promise.reject(refreshError);
                }
            } else {
                // Token invalid or other auth error, redirect to login
                window.location.href = '/login';
            }
        }
        
        return Promise.reject(error);
    }
);
```

---

## Testing Authentication Errors

### Test 1: Missing Token
```bash
curl -X GET "http://localhost:8000/api/v1/auth/profile" \
  -H "Accept: application/json"
```

Expected: `token_not_found` error

### Test 2: Invalid Token
```bash
curl -X GET "http://localhost:8000/api/v1/auth/profile" \
  -H "Authorization: Bearer invalid_token_here" \
  -H "Accept: application/json"
```

Expected: `token_invalid` error

### Test 3: Expired Token
Wait for token to expire (default: 60 minutes) or manually set a short TTL in config, then:
```bash
curl -X GET "http://localhost:8000/api/v1/auth/profile" \
  -H "Authorization: Bearer EXPIRED_TOKEN" \
  -H "Accept: application/json"
```

Expected: `token_expired` error

---

## Configuration

Token expiration time can be configured in `config/jwt.php`:

```php
'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token lifetime in minutes
```

---

## Support

For any issues related to authentication, please check:
1. Token is being sent in the correct format
2. Token hasn't expired
3. User account is active
4. API endpoint URL is correct

If issues persist, contact the development team.
