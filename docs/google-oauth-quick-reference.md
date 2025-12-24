# Google OAuth Quick Reference

## 🚀 Quick Start

### 1. Configure .env
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com
```

### 2. Add Callback URLs to Google Console
```
https://yourdomain.com/owner/auth/google/callback
https://yourdomain.com/admin/auth/google/callback
https://yourdomain.com/seller/auth/google/callback
https://yourdomain.com/staff/auth/google/callback
```

### 3. Test
Visit: `/owner/login`, `/admin/login`, `/seller/login`, `/staff/login`

---

## 📍 Routes

| Role | Login URL | Callback URL |
|------|-----------|--------------|
| Owner | `/owner/auth/google` | `/owner/auth/google/callback` |
| Admin | `/admin/auth/google` | `/admin/auth/google/callback` |
| Seller | `/seller/auth/google` | `/seller/auth/google/callback` |
| Staff | `/staff/auth/google` | `/staff/auth/google/callback` |

---

## 🔐 Behavior Matrix

| Role | New Account | Link Existing | Approval Needed |
|------|-------------|---------------|-----------------|
| Owner | ✅ Yes | ✅ Yes | ❌ No |
| Admin | ❌ No | ✅ Yes | ❌ No |
| Seller | ✅ Yes | ✅ Yes | ✅ Yes |
| Staff | ❌ No | ✅ Yes | ❌ No |

---

## 🗄️ Database Columns

```sql
google_id               VARCHAR(255) UNIQUE
google_token            TEXT
google_refresh_token    TEXT
avatar                  VARCHAR(255)
```

---

## 🎨 Frontend Button

```blade
<a href="{{ route('admin.auth.google') }}" class="btn btn-light w-100">
    <svg>...</svg>
    Continue with Google
</a>
```

---

## ⚠️ Error Messages

| Error | Meaning |
|-------|---------|
| "No account found" | Admin/Staff need pre-created account |
| "Account is inactive" | Status = 0 in database |
| "Pending approval" | Seller not approved yet |
| "Failed to login" | OAuth error, check logs |

---

## 🧪 Quick Test

1. **Owner**: Create new account via Google ✅
2. **Admin**: Link existing account ✅
3. **Seller**: Create account (pending) ✅
4. **Staff**: Link existing account ✅

---

## 📚 Documentation

- **Full Guide**: `docs/google-oauth-implementation.md`
- **Testing**: `docs/google-oauth-testing-guide.md`
- **Summary**: `docs/google-oauth-summary.md`

---

## 🔧 Troubleshooting

```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify routes
php artisan route:list --name=auth.google

# Check migrations
php artisan migrate:status
```

---

## ✅ Checklist

- [ ] Google credentials in `.env`
- [ ] Callback URLs in Google Console
- [ ] Migrations run
- [ ] Test all 4 roles
- [ ] Verify database updates
- [ ] Check error handling
