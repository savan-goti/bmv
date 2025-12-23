# Testing Google OAuth Routes

## Test 1: Check if redirect route works
Visit: http://localhost/owner/auth/google

This should either:
- Redirect you to Google's OAuth page (if credentials are configured)
- Show an error about missing credentials (if not configured)

## Test 2: The callback route
The callback route `/owner/auth/google/callback` should ONLY be accessed by Google after authentication.
Do NOT access it directly in the browser.

## Current Issue
You're accessing the callback URL directly, which won't work because:
1. Google hasn't sent the required OAuth data
2. The route expects specific query parameters from Google

## Proper Testing Flow
1. Add Google OAuth credentials to .env:
   ```
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret  
   GOOGLE_REDIRECT_URI=http://localhost/owner/auth/google/callback
   ```

2. Visit http://localhost/owner/login

3. Click "Continue with Google" button

4. Google will handle the redirect and callback automatically
