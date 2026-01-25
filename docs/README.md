# BMV API Documentation

Welcome to the BMV API documentation. This folder contains comprehensive documentation for all API endpoints.

---

## 📁 Documentation Files

### Profile Management APIs

1. **[API_IMPLEMENTATION_SUMMARY.md](API_IMPLEMENTATION_SUMMARY.md)**
   - **Purpose:** Overview of current implementation status
   - **Use When:** You want to understand what's already implemented
   - **Contains:** Features, technical details, database schema, security measures

2. **[API_PROFILE_MANAGEMENT.md](API_PROFILE_MANAGEMENT.md)**
   - **Purpose:** Complete API reference guide
   - **Use When:** You need detailed endpoint documentation
   - **Contains:** All endpoints, parameters, request/response examples, error codes, cURL examples

3. **[API_PROFILE_QUICK_REFERENCE.md](API_PROFILE_QUICK_REFERENCE.md)**
   - **Purpose:** Quick lookup guide
   - **Use When:** You need a quick reference while coding
   - **Contains:** Endpoint summaries, field validations, quick examples

4. **[BMV_Profile_Management_API.postman_collection.json](BMV_Profile_Management_API.postman_collection.json)**
   - **Purpose:** Postman collection for testing
   - **Use When:** You want to test APIs using Postman
   - **Contains:** All endpoints pre-configured with examples

---

## 🚀 Quick Start

### For Developers

1. **Understanding the APIs:**
   - Start with `API_IMPLEMENTATION_SUMMARY.md` to see what's available
   - Use `API_PROFILE_QUICK_REFERENCE.md` for quick lookups

2. **Detailed Implementation:**
   - Read `API_PROFILE_MANAGEMENT.md` for complete details
   - Check validation rules, error responses, and examples

3. **Testing:**
   - Import `BMV_Profile_Management_API.postman_collection.json` into Postman
   - Set environment variables (`base_url` and `token`)
   - Test all endpoints

### For Frontend Developers

1. **Integration Steps:**
   ```javascript
   // Example: Update Profile
   const updateProfile = async (profileData) => {
     const response = await fetch('http://localhost/api/v1/customer/profile', {
       method: 'PUT',
       headers: {
         'Authorization': `Bearer ${token}`,
         'Content-Type': 'application/json',
         'Accept': 'application/json'
       },
       body: JSON.stringify(profileData)
     });
     return await response.json();
   };
   ```

2. **With Image Upload:**
   ```javascript
   // Example: Update Profile with Image
   const updateProfileWithImage = async (formData) => {
     const response = await fetch('http://localhost/api/v1/customer/profile', {
       method: 'POST',
       headers: {
         'Authorization': `Bearer ${token}`,
         'Accept': 'application/json'
       },
       body: formData // FormData object with image
     });
     return await response.json();
   };
   ```

---

## 📋 Available API Endpoints

### Profile Management

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/customer/profile` | GET | Get profile |
| `/api/v1/customer/profile` | PUT/POST | Update profile |
| `/api/v1/customer/password` | PUT/POST | Update password |
| `/api/v1/customer/profile-image` | POST | Update profile image |
| `/api/v1/customer/profile-image` | DELETE | Delete profile image |
| `/api/v1/customer/location` | PUT/POST | Update location |
| `/api/v1/customer/social-links` | PUT/POST | Update social links |
| `/api/v1/customer/account` | DELETE | Delete account |

---

## 🔐 Authentication

All endpoints require JWT Bearer token:

```
Authorization: Bearer YOUR_JWT_TOKEN
```

Get your token from the login endpoint:
```
POST /api/v1/auth/login
```

---

## 📝 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { /* response data */ }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": { /* error details */ }
}
```

---

## 🧪 Testing with Postman

### Import Collection

1. Open Postman
2. Click **Import**
3. Select `BMV_Profile_Management_API.postman_collection.json`
4. Collection will be imported with all endpoints

### Set Environment Variables

1. Create a new environment in Postman
2. Add variables:
   - `base_url`: `http://localhost/api/v1` (or your API URL)
   - `token`: Your JWT token from login

### Test Endpoints

1. Login first to get JWT token
2. Set the token in environment variable
3. Test each endpoint
4. Check responses

---

## 🔧 Common Use Cases

### 1. Update Customer Name and City

**Request:**
```bash
curl -X PUT "http://localhost/api/v1/customer/profile" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "city": "Mumbai"
  }'
```

### 2. Upload Profile Image

**Request:**
```bash
curl -X POST "http://localhost/api/v1/customer/profile" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=John Doe" \
  -F "profile_image=@/path/to/image.jpg"
```

### 3. Update Password

**Request:**
```bash
curl -X PUT "http://localhost/api/v1/customer/password" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "current_password": "OldPass123!",
    "password": "NewPass456!",
    "password_confirmation": "NewPass456!"
  }'
```

### 4. Delete Account

**Request:**
```bash
curl -X DELETE "http://localhost/api/v1/customer/account" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "password": "MyPassword123!"
  }'
```

---

## ⚠️ Important Notes

1. **Email Field:** While required in update profile, it should be treated as readonly
2. **Profile Image:** Max size 2MB, formats: JPEG, PNG, JPG, GIF
3. **Account Deletion:** Soft delete - data is preserved but account is deactivated
4. **Password Security:** All password operations require current password verification
5. **Social Links:** Stored as JSON, empty values are automatically removed

---

## 📊 Field Validations

### Profile Fields

| Field | Type | Required | Max Length | Special Rules |
|-------|------|----------|------------|---------------|
| name | string | Yes | 255 | - |
| email | string | Yes | 255 | Valid email, unique |
| username | string | No | 255 | Alphanumeric + underscore, unique |
| phone | string | No | 20 | Unique |
| gender | string | No | - | male, female, other |
| dob | date | No | - | YYYY-MM-DD, before today |
| profile_image | file | No | 2MB | jpeg, png, jpg, gif |

### Location Fields

| Field | Type | Required | Range |
|-------|------|----------|-------|
| latitude | decimal | No | -90 to 90 |
| longitude | decimal | No | -180 to 180 |
| city | string | No | max:255 |
| state | string | No | max:255 |
| country | string | No | max:255 |
| pincode | string | No | max:20 |

### Social Links

All social links are optional and must be valid URLs (except WhatsApp).

---

## 🐛 Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Check if token is valid
   - Ensure token is in Authorization header
   - Verify token hasn't expired

2. **422 Validation Error**
   - Check request body format
   - Verify all required fields are present
   - Ensure data types are correct

3. **500 Server Error**
   - Check server logs
   - Verify database connection
   - Ensure file upload directory is writable

### Debug Tips

1. Use Postman to test endpoints
2. Check response headers for additional info
3. Review Laravel logs: `storage/logs/laravel.log`
4. Enable debug mode in `.env`: `APP_DEBUG=true`

---

## 📚 Additional Resources

- **Laravel Documentation:** https://laravel.com/docs
- **JWT Auth Documentation:** https://jwt-auth.readthedocs.io/
- **Postman Documentation:** https://learning.postman.com/

---

## 📞 Support

For questions or issues:
1. Check the detailed documentation files
2. Review the implementation summary
3. Test with Postman collection
4. Contact the development team

---

## 📅 Last Updated

January 23, 2026

---

## 📝 Version History

- **v1.0** (2026-01-23): Initial documentation for Profile Management APIs
  - Edit Profile API
  - Delete Account API
  - Password Update API
  - Profile Image Management
  - Location Management
  - Social Links Management

---

**Happy Coding! 🚀**
