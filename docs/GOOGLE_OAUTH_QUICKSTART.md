# Quick Start: Google OAuth for Owner Login

## 🚀 Quick Setup (5 minutes)

### 1. Get Google Credentials
1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Create/select a project
3. Enable Google+ API
4. Create OAuth 2.0 Client ID
5. Add redirect URI: `http://localhost/owner/auth/google/callback`
6. Copy Client ID and Client Secret

### 2. Update .env File
```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost/owner/auth/google/callback
```

### 3. Clear Config Cache
```bash
php artisan config:clear
```

### 4. Test
1. Go to `http://localhost/owner/login`
2. Click "Continue with Google"
3. Sign in with Google
4. You're done! ✅

## 📚 Full Documentation
See [GOOGLE_OAUTH_SETUP.md](./GOOGLE_OAUTH_SETUP.md) for detailed documentation.

## 🔧 Troubleshooting

**Redirect URI mismatch?**
- Make sure Google Console redirect URI matches your .env file exactly

**Invalid client?**
- Double-check Client ID and Secret in .env file

**Still not working?**
- Check `storage/logs/laravel.log` for errors
- Run `php artisan config:clear` again
