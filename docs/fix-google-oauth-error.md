# Fix Google OAuth Error 400: redirect_uri_mismatch

## ❌ Error
```
Error 400: redirect_uri_mismatch
```

## ✅ Solution

The redirect URIs in your Google Cloud Console don't match the ones used by the application. Follow these steps:

---

## Step 1: Get Your Application URL

First, determine your application's base URL:
- **Local Development**: `http://localhost` or `http://127.0.0.1:8000`
- **Production**: `https://yourdomain.com`

---

## Step 2: Update Google Cloud Console

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Select your project
3. Navigate to **APIs & Services** → **Credentials**
4. Click on your **OAuth 2.0 Client ID**
5. Under **Authorized redirect URIs**, add these EXACT URLs:

### For Local Development (http://localhost or http://127.0.0.1)

```
http://localhost/owner/auth/google/callback
http://localhost/admin/auth/google/callback
http://localhost/seller/auth/google/callback
http://localhost/staff/auth/google/callback
```

**OR if using port 8000:**

```
http://127.0.0.1:8000/owner/auth/google/callback
http://127.0.0.1:8000/admin/auth/google/callback
http://127.0.0.1:8000/seller/auth/google/callback
http://127.0.0.1:8000/staff/auth/google/callback
```

### For Production (https://yourdomain.com)

```
https://yourdomain.com/owner/auth/google/callback
https://yourdomain.com/admin/auth/google/callback
https://yourdomain.com/seller/auth/google/callback
https://yourdomain.com/staff/auth/google/callback
```

6. Click **SAVE**

---

## Step 3: Update Your .env File

Make sure your `.env` file has the correct values:

```env
APP_URL=http://localhost
# OR for production:
# APP_URL=https://yourdomain.com

GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
```

**Important**: Remove `GOOGLE_REDIRECT_URI` from `.env` if it exists - it's no longer needed!

---

## Step 4: Clear Config Cache

Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## Step 5: Test

1. Navigate to one of the login pages:
   - `http://localhost/owner/login`
   - `http://localhost/admin/login`
   - `http://localhost/seller/login`
   - `http://localhost/staff/login`

2. Click **"Continue with Google"**

3. You should now be redirected to Google without errors!

---

## Common Issues

### Issue: Still getting redirect_uri_mismatch

**Check:**
1. ✅ URLs in Google Console match EXACTLY (including http/https)
2. ✅ No trailing slashes in URLs
3. ✅ `APP_URL` in `.env` is correct
4. ✅ Config cache is cleared
5. ✅ You saved changes in Google Console

### Issue: Using WAMP/XAMPP

If you're using WAMP/XAMPP with a custom domain like `http://bmv.local`:

```
http://bmv.local/owner/auth/google/callback
http://bmv.local/admin/auth/google/callback
http://bmv.local/seller/auth/google/callback
http://bmv.local/staff/auth/google/callback
```

And update `.env`:
```env
APP_URL=http://bmv.local
```

---

## Verification

To verify your redirect URLs are correct, run:

```bash
php artisan route:list | grep google
```

You should see:
```
GET|HEAD  owner/auth/google .............. owner.auth.google
GET|HEAD  owner/auth/google/callback .... owner.auth.google.callback
GET|HEAD  admin/auth/google .............. admin.auth.google
GET|HEAD  admin/auth/google/callback .... admin.auth.google.callback
GET|HEAD  seller/auth/google ............. seller.auth.google
GET|HEAD  seller/auth/google/callback ... seller.auth.google.callback
GET|HEAD  staff/auth/google .............. staff.auth.google
GET|HEAD  staff/auth/google/callback .... staff.auth.google.callback
```

---

## Quick Checklist

- [ ] Added all 4 callback URLs to Google Console
- [ ] URLs match your `APP_URL` exactly
- [ ] Saved changes in Google Console
- [ ] Updated `.env` with correct `APP_URL`
- [ ] Removed `GOOGLE_REDIRECT_URI` from `.env`
- [ ] Cleared config cache
- [ ] Tested Google login

---

## Need Help?

If you're still having issues:

1. Check the exact URL Google is trying to redirect to (shown in the error)
2. Compare it with what's in your Google Console
3. Make sure they match EXACTLY
4. Check Laravel logs: `storage/logs/laravel.log`

---

## Example Configuration

### Local Development Setup

**.env:**
```env
APP_URL=http://localhost
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abc123def456
```

**Google Console Authorized Redirect URIs:**
```
http://localhost/owner/auth/google/callback
http://localhost/admin/auth/google/callback
http://localhost/seller/auth/google/callback
http://localhost/staff/auth/google/callback
```

### Production Setup

**.env:**
```env
APP_URL=https://yourdomain.com
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abc123def456
```

**Google Console Authorized Redirect URIs:**
```
https://yourdomain.com/owner/auth/google/callback
https://yourdomain.com/admin/auth/google/callback
https://yourdomain.com/seller/auth/google/callback
https://yourdomain.com/staff/auth/google/callback
```

---

## ✅ Fixed!

After following these steps, your Google OAuth should work perfectly! 🎉
