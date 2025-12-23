# Google OAuth Login Setup for Owner Panel

## Overview
Google OAuth login functionality has been successfully implemented for the owner panel. This allows owners to sign in using their Google accounts.

## What Has Been Implemented

### 1. **Database Changes**
- Added migration to add Google OAuth fields to the `owners` table:
  - `google_id` - Stores the unique Google user ID
  - `google_token` - Stores the Google access token
  - `google_refresh_token` - Stores the Google refresh token
  - `avatar` - Stores the Google profile picture URL

### 2. **Model Updates**
- Updated `Owner` model to include the new Google OAuth fields in the fillable array

### 3. **Configuration**
- Added Google OAuth configuration to `config/services.php`
- Installed Laravel Socialite package for OAuth handling

### 4. **Controller Methods**
Added two new methods to `AuthController`:
- `redirectToGoogle()` - Initiates the Google OAuth flow
- `handleGoogleCallback()` - Handles the OAuth callback and creates/logs in users

### 5. **Routes**
Added two new routes in `routes/owner.php`:
- `GET /owner/auth/google` - Redirects to Google OAuth
- `GET /owner/auth/google/callback` - Handles Google OAuth callback

### 6. **Login View**
- Added a "Continue with Google" button to the login page
- Styled with a divider for better UX

## Setup Instructions

### Step 1: Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API:
   - Go to "APIs & Services" > "Library"
   - Search for "Google+ API"
   - Click "Enable"
4. Create OAuth 2.0 credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - Choose "Web application"
   - Add authorized redirect URIs:
     - For local development: `http://localhost/owner/auth/google/callback`
     - For production: `https://yourdomain.com/owner/auth/google/callback`
   - Click "Create"
5. Copy the Client ID and Client Secret

### Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost/owner/auth/google/callback
```

**Important:** 
- Replace `your-google-client-id-here` with your actual Google Client ID
- Replace `your-google-client-secret-here` with your actual Google Client Secret
- Update `GOOGLE_REDIRECT_URI` to match your application URL (use your production URL when deploying)

### Step 3: Clear Configuration Cache

Run the following command to clear the configuration cache:

```bash
php artisan config:clear
```

## How It Works

### For New Users
1. User clicks "Continue with Google" on the login page
2. User is redirected to Google's OAuth consent screen
3. After granting permission, Google redirects back to your application
4. A new owner account is created with:
   - Full name from Google
   - Email from Google
   - Google ID, token, and avatar
   - Email is automatically verified
   - Random password is generated
   - Account status is set to active

### For Existing Users (by Email)
1. If an owner with the same email already exists
2. The Google account is linked to the existing owner account
3. Google ID, token, and avatar are updated
4. User is logged in

### For Existing Google Users
1. If an owner with the same Google ID exists
2. Google token and avatar are updated
3. User is logged in

## Security Features

1. **Account Status Check**: Inactive accounts cannot log in via Google
2. **Email Verification**: Google-authenticated users are automatically email-verified
3. **Token Management**: Google tokens are securely stored and updated
4. **Error Handling**: Comprehensive error handling with user-friendly messages

## Testing

### Local Testing
1. Make sure your `.env` file has the correct Google OAuth credentials
2. Ensure the redirect URI in Google Console matches your local URL
3. Visit the owner login page: `http://localhost/owner/login`
4. Click "Continue with Google"
5. Sign in with a Google account
6. You should be redirected to the owner dashboard

### Common Issues

**Issue**: "Redirect URI mismatch" error
- **Solution**: Make sure the redirect URI in your Google Console exactly matches the one in your `.env` file

**Issue**: "Invalid client" error
- **Solution**: Double-check your Client ID and Client Secret in the `.env` file

**Issue**: "Failed to login with Google"
- **Solution**: Check the Laravel logs at `storage/logs/laravel.log` for detailed error messages

## Production Deployment

Before deploying to production:

1. Update the redirect URI in Google Console to your production URL
2. Update `GOOGLE_REDIRECT_URI` in your production `.env` file
3. Run `php artisan config:clear` on production
4. Test the Google login flow on production

## Additional Notes

- Users who sign up via Google will have a random password generated
- They can still set a password later through the forgot password flow if needed
- The Google avatar is stored separately from the profile_image field
- You can modify the `handleGoogleCallback()` method to customize the user creation logic
- Consider adding additional scopes if you need more user information from Google

## Files Modified

1. `database/migrations/2025_12_20_161346_add_google_oauth_to_owners_table.php`
2. `app/Models/Owner.php`
3. `config/services.php`
4. `app/Http/Controllers/Owner/AuthController.php`
5. `routes/owner.php`
6. `resources/views/owner/auth/login.blade.php`

## Next Steps

1. Set up Google OAuth credentials in Google Cloud Console
2. Add credentials to `.env` file
3. Test the login flow
4. Customize the user creation logic if needed
5. Deploy to production with production credentials
