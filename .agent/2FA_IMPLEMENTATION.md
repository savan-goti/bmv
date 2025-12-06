# Two-Factor Authentication (2FA) Implementation for Owner Panel

## Overview
A complete two-factor authentication system has been implemented for the owner panel, providing an extra layer of security using Time-based One-Time Passwords (TOTP).

## Features Implemented

### 1. **2FA Setup & Management**
- ✅ QR code generation for easy setup with authenticator apps
- ✅ Manual secret key entry option
- ✅ OTP verification before enabling 2FA
- ✅ Recovery codes generation (8 codes)
- ✅ Recovery codes regeneration
- ✅ Secure 2FA disable with password confirmation

### 2. **Login Flow**
- ✅ Automatic 2FA detection during login
- ✅ Dynamic 2FA code input field
- ✅ Support for both OTP codes and recovery codes
- ✅ Recovery codes are automatically marked as used

### 3. **Security Features**
- ✅ Encrypted storage of secret keys
- ✅ Encrypted storage of recovery codes
- ✅ Password verification required to disable 2FA
- ✅ Timestamp tracking of when 2FA was enabled
- ✅ One-time use recovery codes

## Database Changes

### New Columns Added to `owners` Table:
- `two_factor_secret` (text, nullable) - Encrypted TOTP secret key
- `two_factor_recovery_codes` (text, nullable) - Encrypted JSON array of recovery codes
- `two_factor_confirmed_at` (timestamp, nullable) - When 2FA was successfully enabled

## Files Modified/Created

### 1. **Migration**
- `database/migrations/2025_12_05_162355_add_two_factor_columns_to_owners_table.php`

### 2. **Model**
- `app/Models/Owner.php` - Added fillable fields and casts

### 3. **Controller**
- `app/Http/Controllers/Owner/OwnerSettingsController.php`
  - `enableTwoFactor()` - Generate QR code and secret
  - `confirmTwoFactor()` - Verify OTP and enable 2FA
  - `disableTwoFactor()` - Disable 2FA with password verification
  - `regenerateRecoveryCodes()` - Generate new recovery codes

- `app/Http/Controllers/Owner/AuthController.php`
  - Updated `authenticate()` method to handle 2FA verification
  - Added `verifyRecoveryCode()` method for recovery code validation

### 4. **Routes**
- `routes/owner.php` - Added 4 new routes for 2FA management

### 5. **Views**
- `resources/views/owner/owner-settings/index.blade.php` - Complete 2FA UI
- `resources/views/owner/auth/login.blade.php` - Added 2FA code input

## How It Works

### Setting Up 2FA

1. **Owner navigates to Owner Settings page**
2. **Clicks "Enable Two-Factor Authentication"**
   - System generates a unique secret key
   - QR code is displayed
   - Secret key is shown for manual entry

3. **Owner scans QR code with authenticator app**
   - Supported apps: Google Authenticator, Authy, Microsoft Authenticator, etc.

4. **Owner enters the 6-digit code from their app**
   - System verifies the code
   - If valid, 2FA is enabled
   - 8 recovery codes are generated and displayed
   - Owner should save these codes securely

### Login with 2FA

1. **Owner enters email and password**
2. **System checks if 2FA is enabled**
   - If yes, shows 2FA code input field
   - If no, logs in directly

3. **Owner enters 6-digit code**
   - Can use code from authenticator app
   - OR use a recovery code

4. **System verifies the code**
   - If valid, login succeeds
   - If recovery code used, it's marked as used and removed

### Disabling 2FA

1. **Owner clicks "Disable 2FA"**
2. **System prompts for password**
3. **After password verification:**
   - 2FA is disabled
   - Secret key is removed
   - Recovery codes are removed
   - Confirmation timestamp is cleared

## API Endpoints

### 2FA Management
- `POST /owner/owner-settings/two-factor/enable` - Start 2FA setup
- `POST /owner/owner-settings/two-factor/confirm` - Verify and enable 2FA
- `POST /owner/owner-settings/two-factor/disable` - Disable 2FA
- `POST /owner/owner-settings/two-factor/recovery-codes` - Regenerate recovery codes

### Authentication
- `POST /owner/login` - Login with optional 2FA code

## Dependencies

### Composer Packages
- `pragmarx/google2fa-qrcode` - For TOTP generation and QR code creation

## Security Considerations

1. **Encryption**: All sensitive data (secret keys, recovery codes) are encrypted using Laravel's encryption
2. **Password Verification**: Disabling 2FA requires password confirmation
3. **One-Time Recovery Codes**: Each recovery code can only be used once
4. **Secure Storage**: Secret keys are never displayed after initial setup
5. **Timestamp Tracking**: System tracks when 2FA was enabled for audit purposes

## User Experience

### For Owners WITHOUT 2FA:
- Login is unchanged (email + password)
- Can enable 2FA from settings page

### For Owners WITH 2FA:
- Login requires email + password + 6-digit code
- Can view/regenerate recovery codes
- Can disable 2FA with password
- Recovery codes can be used if authenticator app is unavailable

## Testing Checklist

- [ ] Enable 2FA with QR code scan
- [ ] Enable 2FA with manual secret entry
- [ ] Login with authenticator app code
- [ ] Login with recovery code
- [ ] Verify recovery code is removed after use
- [ ] Regenerate recovery codes
- [ ] Disable 2FA
- [ ] Verify 2FA is required after enabling
- [ ] Test invalid codes are rejected
- [ ] Test password verification for disable

## Future Enhancements (Optional)

1. **SMS/Email Backup**: Send backup codes via email
2. **Trusted Devices**: Remember devices for 30 days
3. **2FA Statistics**: Track when 2FA was last used
4. **Admin Override**: Allow super admin to disable 2FA for users
5. **Backup Methods**: Support for SMS or email-based 2FA

## Support

If users lose access to their authenticator app:
1. They can use one of their recovery codes
2. If recovery codes are also lost, contact system administrator
3. Administrator can manually disable 2FA from database if needed

## Notes

- Recovery codes are displayed only once during setup or regeneration
- Users should save recovery codes in a secure location
- Each recovery code can only be used once
- The system uses TOTP (Time-based One-Time Password) standard
- Codes are valid for 30 seconds (standard TOTP window)
