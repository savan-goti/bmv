# Google OAuth Documentation

This directory contains comprehensive documentation for the Google OAuth authentication system.

## Documentation Files

### 📘 [GOOGLE_AUTH_IMPLEMENTATION.md](./GOOGLE_AUTH_IMPLEMENTATION.md)
**Complete Implementation Guide**
- Detailed architecture overview
- Security features and best practices
- Database requirements
- Error handling strategies
- Testing procedures
- Configuration instructions

**Read this if you need**: Deep understanding of how the system works

---

### 📝 [GOOGLE_AUTH_SUMMARY.md](./GOOGLE_AUTH_SUMMARY.md)
**Implementation Summary**
- Overview of changes made
- List of modified files
- Benefits of the new architecture
- Migration notes
- Testing checklist

**Read this if you need**: Quick overview of what was changed and why

---

### ⚡ [GOOGLE_AUTH_QUICK_REFERENCE.md](./GOOGLE_AUTH_QUICK_REFERENCE.md)
**Developer Quick Reference**
- How to use Google login in routes and views
- Adding new guards
- Common issues and solutions
- Debugging tips
- Configuration checklist

**Read this if you need**: Quick answers while developing

---

## Quick Start

### For Users
1. Navigate to your login page (e.g., `/bmv/owner/login`)
2. Click "Login with Google"
3. Authenticate with your Google account
4. You'll be redirected to your dashboard

### For Developers

#### Basic Usage
```php
// In routes file
use App\Http\Controllers\Auth\GoogleAuthController;

Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('owner');
})->name('auth.google');
```

#### In Blade
```html
<a href="{{ route('owner.auth.google') }}">
    Login with Google
</a>
```

## System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    GoogleAuthController                      │
│                  (Centralized OAuth Handler)                 │
└─────────────────────────────────────────────────────────────┘
                              │
                              │ Manages OAuth for
                              ▼
        ┌──────────┬──────────┬──────────┬──────────┐
        │          │          │          │          │
        ▼          ▼          ▼          ▼          ▼
     Owner      Admin      Staff     Seller    (Future)
     Guard      Guard      Guard     Guard      Guards
```

## Key Features

✅ **Multi-Guard Support** - Handles Owner, Admin, Staff, and Seller authentication  
✅ **Security First** - No auto-account creation, status validation  
✅ **Centralized Logic** - Single source of truth for OAuth  
✅ **Comprehensive Logging** - All attempts logged for audit  
✅ **Easy to Extend** - Simple to add new guards  
✅ **Well Documented** - Complete documentation suite  

## Supported Guards

| Guard | Route Prefix | Dashboard Route |
|-------|-------------|-----------------|
| Owner | `/bmv/owner` | `owner.dashboard` |
| Admin | `/bmv/admin` | `admin.dashboard` |
| Staff | `/bmv/staff` | `staff.dashboard` |
| Seller | `/bmv/seller` | `seller.dashboard` |

## Security Notes

🔒 **No Auto-Registration**: Users cannot create accounts via Google login. Accounts must be pre-created by administrators.

🔒 **Status Validation**: Inactive accounts are rejected during login.

🔒 **Comprehensive Logging**: All authentication attempts are logged for security auditing.

## Configuration Required

### Environment Variables
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
```

### Google Cloud Console
Add these redirect URIs:
- `https://your-domain.com/bmv/owner/auth/google/callback`
- `https://your-domain.com/bmv/admin/auth/google/callback`
- `https://your-domain.com/bmv/staff/auth/google/callback`
- `https://your-domain.com/bmv/seller/auth/google/callback`

## Database Requirements

Each user table needs:
```sql
google_id VARCHAR(255) NULLABLE UNIQUE
email VARCHAR(255) UNIQUE
status VARCHAR(255) -- Optional, checked if present
remember_token VARCHAR(100)
```

✅ All migrations are already in place.

## Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| "No account found" | Create user account first |
| "Account not active" | Activate account in admin panel |
| "Invalid state" | Clear browser cache |
| OAuth error | Check logs in `storage/logs/laravel.log` |

### Getting Help

1. Check the relevant documentation file above
2. Review `storage/logs/laravel.log` for errors
3. Verify Google Cloud Console configuration
4. Contact the development team

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       │   └── GoogleAuthController.php  ← Main controller
│       ├── Owner/
│       │   └── AuthController.php        ← Google methods removed
│       ├── Admin/
│       │   └── AuthController.php        ← Google methods removed
│       ├── Staff/
│       │   └── AuthController.php        ← Google methods removed
│       └── Seller/
│           └── AuthController.php        ← Google methods removed
│
routes/
├── owner.php   ← Updated to use GoogleAuthController
├── admin.php   ← Updated to use GoogleAuthController
├── staff.php   ← Updated to use GoogleAuthController
└── seller.php  ← Updated to use GoogleAuthController

docs/
├── README.md                           ← This file
├── GOOGLE_AUTH_IMPLEMENTATION.md       ← Detailed guide
├── GOOGLE_AUTH_SUMMARY.md              ← Change summary
└── GOOGLE_AUTH_QUICK_REFERENCE.md      ← Quick reference
```

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 2.0 | 2025-12-25 | Centralized OAuth implementation |
| 1.0 | 2024-12-24 | Initial Google OAuth implementation |

## Contributing

When modifying the Google OAuth system:

1. Update `GoogleAuthController.php` for logic changes
2. Update documentation if behavior changes
3. Test all guards (owner, admin, staff, seller)
4. Update version history in this README
5. Run tests before committing

## License

This is proprietary software. All rights reserved.

---

**Last Updated**: December 25, 2025  
**Maintained By**: Development Team  
**Status**: ✅ Production Ready
