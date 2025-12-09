# Admin Login Email Verification Implementation

## Overview
Implemented a login email verification feature for admin users. When an admin's email is verified (via the "Email Verification" section in settings), they will automatically receive a 6-digit verification code via email during login, adding an extra layer of security.

## How It Works

### Automatic Activation
- **No separate toggle needed** - The feature is automatically enabled when the admin's email is verified
- When `email_verified_at` is set (not null), login verification codes are required
- When `email_verified_at` is null, normal login flow applies

### User Flow

**When Email is Verified:**
1. Admin enters email and password
2. System validates credentials
3. System generates 6-digit code and sends to email
4. Login page shows verification code input field
5. Admin checks email and enters code
6. System verifies code and expiration (10 minutes)
7. If 2FA is also enabled, admin enters 2FA code next
8. Login successful

**When Email is Not Verified:**
- Normal login flow (with optional 2FA if enabled)

## Features Implemented

### 1. Database Changes
- **Migration**: `2025_12_08_141752_add_login_email_verification_to_admins_table.php`
  - Added `login_verification_code` (string) - Stores the verification code
  - Added `login_verification_code_expires_at` (timestamp) - Code expiration time (10 minutes)
  - Note: `login_email_verification_enabled` field exists but is not used (we use `email_verified_at` instead)

### 2. Model Updates
- **Admin Model** (`app/Models/Admin.php`)
  - Added new fields to `$fillable` array
  - Added proper casts for datetime fields

### 3. Email Functionality
- **Mail Class**: `app/Mail/AdminLoginVerificationMail.php`
  - Sends verification code to admin's email
  - Professional email template with clear instructions
  
- **Email Template**: `resources/views/emails/admin-login-verification.blade.php`
  - Clean, responsive design
  - Displays 6-digit code prominently
  - Includes expiration notice (10 minutes)
  - Security warning if login wasn't initiated by user

### 4. Authentication Flow
- **AuthController** (`app/Http/Controllers/Admin/AuthController.php`)
  - Enhanced `authenticate()` method to:
    1. Check if email is verified (`email_verified_at` is not null)
    2. Generate and send 6-digit code if no code provided
    3. Verify the code if provided
    4. Check code expiration
    5. Clear code after successful verification
  - Works seamlessly with existing 2FA system

### 5. Settings Management
- **No UI changes needed** - Uses existing "Email Verification" section
- When admin clicks "Send Verification Email" and verifies their email, login verification is automatically enabled
- When admin clicks "Mark as Unverified", login verification is automatically disabled

### 6. Login Page Updates
- **Login View** (`resources/views/admin/auth/login.blade.php`)
  - Added login verification code input field (hidden by default)
  - Shows when verification code is sent
  - Auto-scrolls to verification field
  - Displays helpful alert message
  - Integrated with existing 2FA flow

## Security Features

1. **Code Expiration**: Verification codes expire after 10 minutes
2. **One-Time Use**: Codes are cleared after successful verification
3. **Secure Generation**: Uses cryptographically secure random number generation
4. **Email Delivery**: Codes sent only to verified email addresses
5. **Works with 2FA**: Compatible with existing two-factor authentication
6. **Automatic Activation**: Tied to email verification status for better security

## How to Enable/Disable

### Enable Login Verification:
1. Go to Admin Settings page
2. Find "Email Verification" section
3. If email is not verified, click "Send Verification Email"
4. Check your email and click the verification link
5. Login verification is now automatically enabled

### Disable Login Verification:
1. Go to Admin Settings page
2. Find "Email Verification" section
3. Click "Mark as Unverified"
4. Login verification is now automatically disabled

## Testing

### Test with Email Verification Enabled:
1. Verify your email in settings
2. Logout
3. Login with email and password
4. Check email for 6-digit verification code
5. Enter code on login page
6. If 2FA is enabled, enter 2FA code
7. Complete login

### Test with Email Verification Disabled:
1. Mark email as unverified in settings
2. Logout
3. Login with email and password
4. No verification code required
5. If 2FA is enabled, enter 2FA code
6. Complete login

## Files Modified/Created

### Created:
- `database/migrations/2025_12_08_141752_add_login_email_verification_to_admins_table.php`
- `app/Mail/AdminLoginVerificationMail.php`
- `resources/views/emails/admin-login-verification.blade.php`

### Modified:
- `app/Models/Admin.php`
- `app/Http/Controllers/Admin/AuthController.php`
- `resources/views/admin/auth/login.blade.php`

## Advantages of This Approach

1. **Simpler UX**: No need for separate toggle - uses existing email verification status
2. **Better Security**: Email verification is a prerequisite for login codes
3. **Less Confusion**: One setting controls both email verification and login codes
4. **Cleaner Code**: No duplicate settings or toggles to maintain
5. **Logical Flow**: Verified email → Secure login makes sense to users

## Notes

- The feature is automatically disabled for admins with unverified emails
- Each admin can enable/disable it by verifying/unverifying their email
- Verification codes are valid for 10 minutes
- The system handles both login email verification and 2FA seamlessly
- Email verification happens before 2FA (if both are enabled)
- The `login_email_verification_enabled` database field exists but is not actively used
