# Customer Info API - Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                     BMV Customer Info API                            │
│                         /api/v1                                      │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                        Authentication Layer                          │
│                                                                      │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐         │
│  │   Register   │    │    Login     │    │   Refresh    │         │
│  │ POST /auth/  │    │ POST /auth/  │    │ POST /auth/  │         │
│  │  register    │    │    login     │    │   refresh    │         │
│  └──────────────┘    └──────────────┘    └──────────────┘         │
│                                                                      │
│  Returns: JWT Token (Bearer Token)                                  │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      JWT Authentication                              │
│              Authorization: Bearer {token}                           │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    ▼                               ▼
┌──────────────────────────────────┐  ┌──────────────────────────────┐
│   Profile Management Endpoints   │  │  Account Management          │
│                                   │  │                              │
│  ┌────────────────────────────┐  │  │  ┌────────────────────────┐ │
│  │ Get Profile                │  │  │  │ Update Password        │ │
│  │ GET /customer/profile      │  │  │  │ PUT /customer/password │ │
│  └────────────────────────────┘  │  │  └────────────────────────┘ │
│                                   │  │                              │
│  ┌────────────────────────────┐  │  │  ┌────────────────────────┐ │
│  │ Update Profile             │  │  │  │ Delete Account         │ │
│  │ PUT /customer/profile      │  │  │  │ DELETE /customer/      │ │
│  │ POST /customer/profile     │  │  │  │        account         │ │
│  └────────────────────────────┘  │  │  └────────────────────────┘ │
│                                   │  │                              │
└──────────────────────────────────┘  └──────────────────────────────┘

┌──────────────────────────────────┐  ┌──────────────────────────────┐
│   Image Management Endpoints     │  │  Location Management         │
│                                   │  │                              │
│  ┌────────────────────────────┐  │  │  ┌────────────────────────┐ │
│  │ Upload Profile Image       │  │  │  │ Update Location        │ │
│  │ POST /customer/            │  │  │  │ PUT /customer/location │ │
│  │      profile-image         │  │  │  │ POST /customer/        │ │
│  └────────────────────────────┘  │  │  │      location          │ │
│                                   │  │  └────────────────────────┘ │
│  ┌────────────────────────────┐  │  │                              │
│  │ Delete Profile Image       │  │  │  Fields:                     │
│  │ DELETE /customer/          │  │  │  • Latitude                  │
│  │        profile-image       │  │  │  • Longitude                 │
│  └────────────────────────────┘  │  │  • Address                   │
│                                   │  │  • City, State, Country      │
│  Max Size: 2MB                    │  │  • Pincode                   │
│  Types: JPEG, PNG, JPG, GIF       │  │                              │
└──────────────────────────────────┘  └──────────────────────────────┘

┌──────────────────────────────────┐  ┌──────────────────────────────┐
│   Social Links Management        │  │  Public Profile Endpoints    │
│                                   │  │                              │
│  ┌────────────────────────────┐  │  │  ┌────────────────────────┐ │
│  │ Update Social Links        │  │  │  │ Get Customer by ID     │ │
│  │ PUT /customer/             │  │  │  │ GET /customers/{id}    │ │
│  │     social-links           │  │  │  └────────────────────────┘ │
│  │ POST /customer/            │  │  │                              │
│  │      social-links          │  │  │  ┌────────────────────────┐ │
│  └────────────────────────────┘  │  │  │ Get Customer by        │ │
│                                   │  │  │ Username               │ │
│  Supported Platforms:             │  │  │ GET /customers/        │ │
│  • WhatsApp                       │  │  │     username/{name}    │ │
│  • Website                        │  │  └────────────────────────┘ │
│  • Facebook                       │  │                              │
│  • Instagram                      │  │  Returns: Public info only   │
│  • LinkedIn                       │  │  • ID, Username, Name        │
│  • YouTube                        │  │  • Profile Image             │
│  • Telegram                       │  │  • Social Links              │
│  • Twitter                        │  │  • Created Date              │
└──────────────────────────────────┘  └──────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                         Data Flow                                    │
│                                                                      │
│  Client Request                                                      │
│       │                                                              │
│       ▼                                                              │
│  JWT Middleware (auth:api)                                          │
│       │                                                              │
│       ▼                                                              │
│  CustomerController                                                  │
│       │                                                              │
│       ├─► Validation (Laravel Validator)                            │
│       │                                                              │
│       ├─► Business Logic                                            │
│       │   • File Upload                                             │
│       │   • Password Hashing                                        │
│       │   • Data Processing                                         │
│       │                                                              │
│       ├─► Database (Customer Model)                                 │
│       │                                                              │
│       ▼                                                              │
│  JSON Response (ResponseTrait)                                      │
│       │                                                              │
│       ▼                                                              │
│  Client Receives Response                                           │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      Response Format                                 │
│                                                                      │
│  Success Response:                                                   │
│  {                                                                   │
│    "success": true,                                                  │
│    "message": "Operation successful",                                │
│    "data": { ... }                                                   │
│  }                                                                   │
│                                                                      │
│  Error Response:                                                     │
│  {                                                                   │
│    "success": false,                                                 │
│    "message": "Error message",                                       │
│    "errors": { ... }  // For validation errors                      │
│  }                                                                   │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      Security Features                               │
│                                                                      │
│  ✓ JWT Authentication (all endpoints)                               │
│  ✓ Password verification (for sensitive operations)                 │
│  ✓ Input validation (all requests)                                  │
│  ✓ File type & size validation (image uploads)                      │
│  ✓ Unique constraints (email, phone, username)                      │
│  ✓ Soft delete (account deletion)                                   │
│  ✓ Hidden sensitive fields (password, OTP)                          │
│  ✓ Public profile filtering (limited data)                          │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      Database Schema                                 │
│                                                                      │
│  customers table:                                                    │
│  ├─ id (primary key)                                                │
│  ├─ profile_image (string, nullable)                                │
│  ├─ canonical (string, unique)                                      │
│  ├─ username (string, unique, nullable)                             │
│  ├─ name (string)                                                   │
│  ├─ email (string, unique)                                          │
│  ├─ phone (string, unique, nullable)                                │
│  ├─ country_code (string, nullable)                                 │
│  ├─ phone_otp (string, nullable, hidden)                            │
│  ├─ otp_expired_at (datetime, nullable)                             │
│  ├─ phone_validate (boolean)                                        │
│  ├─ gender (enum: male/female/other, nullable)                      │
│  ├─ dob (date, nullable)                                            │
│  ├─ address (text, nullable)                                        │
│  ├─ latitude (decimal, nullable)                                    │
│  ├─ longitude (decimal, nullable)                                   │
│  ├─ city (string, nullable)                                         │
│  ├─ state (string, nullable)                                        │
│  ├─ country (string, nullable)                                      │
│  ├─ pincode (string, nullable)                                      │
│  ├─ social_links (json, nullable)                                   │
│  ├─ status (enum: active/blocked)                                   │
│  ├─ password (hashed, hidden)                                       │
│  ├─ remember_token (hidden)                                         │
│  ├─ created_at (timestamp)                                          │
│  ├─ updated_at (timestamp)                                          │
│  └─ deleted_at (timestamp, nullable - soft delete)                  │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      File Structure                                  │
│                                                                      │
│  app/Http/Controllers/Api/                                          │
│  └─ CustomerController.php (17.9 KB)                                │
│                                                                      │
│  routes/                                                             │
│  └─ api.php (updated)                                               │
│                                                                      │
│  docs/api/                                                           │
│  ├─ customer-info-api.md (12.6 KB)                                  │
│  ├─ customer-info-api-quick-reference.md (7.4 KB)                   │
│  ├─ CUSTOMER-INFO-API-README.md (9.0 KB)                            │
│  ├─ CUSTOMER-INFO-API-FILES.md (7.1 KB)                             │
│  ├─ BMV-Customer-Info-API.postman_collection.json (22.6 KB)         │
│  ├─ test-customer-api.sh (5.7 KB)                                   │
│  └─ test-customer-api.bat (5.5 KB)                                  │
│                                                                      │
│  public/uploads/customers/                                           │
│  └─ (profile images stored here)                                    │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      Testing Tools                                   │
│                                                                      │
│  1. Postman Collection                                               │
│     • Import JSON file                                               │
│     • Auto token management                                          │
│     • Pre-configured requests                                        │
│                                                                      │
│  2. Windows Batch Script                                             │
│     • Interactive menu                                               │
│     • Run: test-customer-api.bat                                     │
│                                                                      │
│  3. Linux/Mac Bash Script                                            │
│     • Interactive menu                                               │
│     • Run: ./test-customer-api.sh                                    │
│                                                                      │
│  4. cURL Commands                                                    │
│     • See documentation for examples                                 │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      Quick Start                                     │
│                                                                      │
│  1. Login to get JWT token                                           │
│     POST /api/v1/auth/login                                          │
│                                                                      │
│  2. Use token in Authorization header                                │
│     Authorization: Bearer {token}                                    │
│                                                                      │
│  3. Call any customer info endpoint                                  │
│     GET /api/v1/customer/profile                                     │
│                                                                      │
│  4. Handle response                                                  │
│     Check success field and process data                             │
└─────────────────────────────────────────────────────────────────────┘
```

## Endpoint Summary

| Category | Method | Endpoint | Description |
|----------|--------|----------|-------------|
| **Profile** | GET | `/customer/profile` | Get profile |
| | PUT/POST | `/customer/profile` | Update profile |
| **Password** | PUT/POST | `/customer/password` | Change password |
| **Image** | POST | `/customer/profile-image` | Upload image |
| | DELETE | `/customer/profile-image` | Delete image |
| **Location** | PUT/POST | `/customer/location` | Update location |
| **Social** | PUT/POST | `/customer/social-links` | Update social links |
| **Account** | DELETE | `/customer/account` | Delete account |
| **Public** | GET | `/customers/{id}` | Get by ID |
| | GET | `/customers/username/{username}` | Get by username |

## Status Codes

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
