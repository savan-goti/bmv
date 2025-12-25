# Google OAuth - Quick Reference Guide

## For Developers

### How to Use Google Login

#### In Routes
```php
use App\Http\Controllers\Auth\GoogleAuthController;

// For any guard (owner, admin, staff, seller)
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('GUARD_NAME');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('GUARD_NAME');
})->name('auth.google.callback');
```

#### In Blade Templates
```html
<!-- Login with Google Button -->
<a href="{{ route('GUARD.auth.google') }}" class="btn btn-google">
    <i class="fab fa-google"></i> Login with Google
</a>
```

Replace `GUARD` with: `owner`, `admin`, `staff`, or `seller`

### Adding a New Guard

1. **Add to validation array** in `GoogleAuthController`:
```php
if (!in_array($guard, ['owner', 'admin', 'staff', 'seller', 'YOUR_NEW_GUARD'])) {
    abort(404);
}
```

2. **Add model mapping**:
```php
private function getModelForGuard($guard)
{
    $models = [
        'owner' => Owner::class,
        'admin' => Admin::class,
        'staff' => Staff::class,
        'seller' => Seller::class,
        'YOUR_NEW_GUARD' => YourNewModel::class, // Add this
    ];
    return $models[$guard];
}
```

3. **Create routes** in your new guard's route file:
```php
Route::get('/auth/google', function() {
    return app(GoogleAuthController::class)->redirectToGoogle('YOUR_NEW_GUARD');
})->name('auth.google');

Route::get('/auth/google/callback', function() {
    return app(GoogleAuthController::class)->handleGoogleCallback('YOUR_NEW_GUARD');
})->name('auth.google.callback');
```

4. **Ensure database columns exist**:
   - `google_id` (nullable, unique)
   - `email` (unique)
   - `status` (optional)
   - `remember_token`

### Error Messages

| Error | Meaning | Solution |
|-------|---------|----------|
| "No account found with this email" | User doesn't exist in database | Create account first via admin panel |
| "Your account is not active" | Account status is not 'active' | Activate account in admin panel |
| "Failed to login with Google" | OAuth error occurred | Check logs for details |

### Debugging

#### Enable Detailed Logging
Check `storage/logs/laravel.log` for:
```
Google login attempted for non-existent {guard}: {email}
Google login attempted for inactive {guard}: {email}
Successful Google login for {guard}: {email}
Google OAuth Error for {guard}: {error_message}
```

#### Test OAuth Flow
1. Navigate to `/bmv/{guard}/login`
2. Click "Login with Google"
3. Check browser network tab for redirects
4. Verify callback URL matches: `/bmv/{guard}/auth/google/callback`
5. Check logs for any errors

### Common Issues

#### Issue: "Invalid state" error
**Cause**: Session expired or callback URL mismatch
**Solution**: Clear browser cache and try again

#### Issue: User not logged in after callback
**Cause**: Account doesn't exist or is inactive
**Solution**: Check error message and logs

#### Issue: Redirect loop
**Cause**: Middleware or guard configuration issue
**Solution**: Verify guard is properly configured in `config/auth.php`

### Configuration

#### Environment Variables
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
```

#### Google Cloud Console
1. Go to Google Cloud Console
2. Create OAuth 2.0 credentials
3. Add authorized redirect URIs:
   - `http://your-domain.com/bmv/owner/auth/google/callback`
   - `http://your-domain.com/bmv/admin/auth/google/callback`
   - `http://your-domain.com/bmv/staff/auth/google/callback`
   - `http://your-domain.com/bmv/seller/auth/google/callback`

### Security Best Practices

1. **Never auto-create accounts** - Users must be pre-created by admins
2. **Always validate status** - Check account is active before login
3. **Log all attempts** - Keep audit trail of OAuth attempts
4. **Use HTTPS** - Always use HTTPS in production
5. **Rotate secrets** - Regularly update OAuth credentials

### API Reference

#### `redirectToGoogle($guard)`
- **Purpose**: Initiates OAuth flow
- **Parameter**: Guard name (string)
- **Returns**: Redirect to Google
- **Throws**: 404 if invalid guard

#### `handleGoogleCallback($guard)`
- **Purpose**: Processes OAuth callback
- **Parameter**: Guard name (string)
- **Returns**: Redirect to dashboard or login with error
- **Throws**: 404 if invalid guard

### Testing

#### Manual Test
```bash
# 1. Start local server
php artisan serve

# 2. Navigate to login page
http://localhost:8000/bmv/owner/login

# 3. Click "Login with Google"
# 4. Complete Google authentication
# 5. Verify redirect to dashboard
```

#### Unit Test Example
```php
public function test_google_oauth_redirects_to_google()
{
    $response = $this->get(route('owner.auth.google'));
    $response->assertRedirect();
    $this->assertStringContainsString('google.com', $response->headers->get('Location'));
}
```

### Troubleshooting Checklist

- [ ] Google OAuth credentials configured in `.env`
- [ ] Redirect URIs added in Google Cloud Console
- [ ] User account exists in database
- [ ] User account status is 'active'
- [ ] `google_id` column exists in user table
- [ ] Routes are properly registered
- [ ] Guard is configured in `config/auth.php`
- [ ] Socialite package is installed
- [ ] HTTPS is enabled (in production)

### Support

For issues or questions:
1. Check `docs/GOOGLE_AUTH_IMPLEMENTATION.md` for detailed documentation
2. Review `storage/logs/laravel.log` for error details
3. Verify configuration in Google Cloud Console
4. Test with a different Google account
5. Contact the development team

---
**Last Updated**: December 25, 2025
