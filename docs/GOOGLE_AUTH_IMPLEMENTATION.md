# Google OAuth Authentication - Multi-Guard Implementation

## Overview
The `GoogleAuthController` has been refactored to handle Google OAuth authentication for all user types (Owner, Admin, Staff, and Seller) in a centralized, maintainable way.

## Architecture

### Controller: `App\Http\Controllers\Auth\GoogleAuthController`

This controller manages Google OAuth for all guards using a single, unified implementation.

#### Key Features:
1. **Multi-Guard Support**: Handles owner, admin, staff, and seller authentication
2. **Dynamic Model Selection**: Automatically selects the correct model based on guard type
3. **Security First**: Does not auto-create accounts; users must exist in the database
4. **Status Validation**: Checks if user account is active before login
5. **Comprehensive Logging**: Logs all authentication attempts and errors
6. **Guard-Specific Redirects**: Redirects to appropriate dashboard after login

### Methods

#### `redirectToGoogle($guard)`
- **Purpose**: Initiates Google OAuth flow
- **Parameter**: `$guard` - The user type (owner, admin, staff, seller)
- **Returns**: Redirect to Google OAuth consent screen
- **URL Format**: `/bmv/{guard}/auth/google/callback`

#### `handleGoogleCallback($guard)`
- **Purpose**: Handles the callback from Google after authentication
- **Parameter**: `$guard` - The user type (owner, admin, staff, seller)
- **Process**:
  1. Retrieves user data from Google
  2. Checks if user exists with Google ID
  3. If not, checks if user exists with email
  4. Links Google ID to existing account
  5. Validates account status
  6. Logs in user with appropriate guard
  7. Redirects to dashboard

#### `getModelForGuard($guard)` (Private)
- **Purpose**: Returns the appropriate model class for the given guard
- **Returns**: Model class (Owner, Admin, Staff, or Seller)

## Routes Configuration

All route files have been updated to use the centralized controller:

### Owner Routes (`routes/owner.php`)
```php
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('owner');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('owner');
})->name('auth.google.callback');
```

### Admin Routes (`routes/admin.php`)
```php
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('admin');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('admin');
})->name('auth.google.callback');
```

### Staff Routes (`routes/staff.php`)
```php
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('staff');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('staff');
})->name('auth.google.callback');
```

### Seller Routes (`routes/seller.php`)
```php
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('seller');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('seller');
})->name('auth.google.callback');
```

## Security Features

### 1. No Auto-Registration
Users cannot create accounts through Google login. They must be created by an administrator first. This prevents unauthorized access.

### 2. Account Status Validation
Before logging in, the controller checks if the account has a `status` field and validates it's set to 'active'.

### 3. Email Verification
The controller links Google accounts to existing users by email, ensuring proper identity verification.

### 4. Guard Validation
The controller validates that the guard parameter is one of the allowed types, preventing unauthorized access attempts.

## Database Requirements

Each user table (owners, admins, staffs, sellers) must have:
- `google_id` column (nullable string) - Stores Google user ID
- `email` column (unique string) - Used for account matching
- `status` column (optional) - If present, must be 'active' for login
- `remember_token` column - For "remember me" functionality

## Error Handling

### User Not Found
```
Error Message: "No account found with this email. Please contact your administrator."
Redirect: Back to login page
Log: Warning level
```

### Inactive Account
```
Error Message: "Your account is not active. Please contact your administrator."
Redirect: Back to login page
Log: Warning level
```

### OAuth Error
```
Error Message: "Failed to login with Google. Please try again."
Redirect: Back to login page
Log: Error level with full stack trace
```

## Logging

All authentication events are logged:
- **Warning**: Non-existent user attempts, inactive account attempts
- **Info**: Successful logins
- **Error**: OAuth failures, exceptions

Log format:
```
Google login attempted for non-existent {guard}: {email}
Google login attempted for inactive {guard}: {email}
Successful Google login for {guard}: {email}
Google OAuth Error for {guard}: {error_message}
```

## Testing

### Manual Testing Steps

1. **Test Owner Login**
   - Navigate to `/bmv/owner/login`
   - Click "Login with Google"
   - Verify redirect to Google
   - Complete Google authentication
   - Verify redirect to owner dashboard

2. **Test Admin Login**
   - Navigate to `/bmv/admin/login`
   - Click "Login with Google"
   - Verify redirect to Google
   - Complete Google authentication
   - Verify redirect to admin dashboard

3. **Test Staff Login**
   - Navigate to `/bmv/staff/login`
   - Click "Login with Google"
   - Verify redirect to Google
   - Complete Google authentication
   - Verify redirect to staff dashboard

4. **Test Seller Login**
   - Navigate to `/bmv/seller/login`
   - Click "Login with Google"
   - Verify redirect to Google
   - Complete Google authentication
   - Verify redirect to seller dashboard

### Test Cases

1. **Existing User with Google ID**: Should login directly
2. **Existing User without Google ID**: Should link account and login
3. **Non-existent User**: Should show error message
4. **Inactive User**: Should show error message
5. **OAuth Error**: Should show error message and log details

## Maintenance

### Adding a New Guard
To add a new user type:

1. Add the guard name to the validation array in both methods
2. Add the model mapping in `getModelForGuard()`
3. Create routes in the new guard's route file
4. Ensure the model has required database columns

### Removing Google Login
To remove Google login for a specific guard:
1. Remove the routes from the guard's route file
2. Remove the "Login with Google" button from the login view

## Benefits of This Implementation

1. **DRY Principle**: Single source of truth for Google OAuth logic
2. **Maintainability**: Changes to OAuth logic only need to be made once
3. **Consistency**: All guards use the same authentication flow
4. **Security**: Centralized security checks and validation
5. **Debugging**: Easier to debug with centralized logging
6. **Scalability**: Easy to add new guards or modify existing ones

## Related Files

- Controller: `app/Http/Controllers/Auth/GoogleAuthController.php`
- Routes: `routes/owner.php`, `routes/admin.php`, `routes/staff.php`, `routes/seller.php`
- Models: `app/Models/Owner.php`, `app/Models/Admin.php`, `app/Models/Staff.php`, `app/Models/Seller.php`
- Config: `config/services.php` (Google OAuth credentials)
- Environment: `.env` (Google OAuth keys)

## Configuration

Ensure your `.env` file has:
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://your-domain.com/bmv/{guard}/auth/google/callback
```

And `config/services.php` has:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

Note: The redirect URI is dynamically set in the controller, so you can use a placeholder in the config.
