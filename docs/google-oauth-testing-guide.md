# Google OAuth Testing Guide

## Prerequisites

Before testing, ensure you have:
1. ✅ Google OAuth credentials configured in `.env`
2. ✅ Migrations run successfully
3. ✅ Test accounts created for each role

## Test Scenarios

### 1. Owner Google Login

#### Test Case 1.1: New Owner via Google
**Steps:**
1. Navigate to `/owner/login`
2. Click "Continue with Google" button
3. Select a Google account
4. Authorize the application

**Expected Result:**
- New owner account created
- Email automatically verified
- Redirected to owner dashboard
- Avatar from Google profile saved

#### Test Case 1.2: Existing Owner Linking Google
**Steps:**
1. Create an owner account with email: `test@example.com`
2. Logout
3. Click "Continue with Google"
4. Login with Google account using same email

**Expected Result:**
- Google account linked to existing owner
- Redirected to owner dashboard
- Google ID, token, and avatar saved

#### Test Case 1.3: Existing Google Owner Login
**Steps:**
1. After linking (Test 1.2), logout
2. Click "Continue with Google"
3. Login with same Google account

**Expected Result:**
- Direct login without creating new account
- Tokens refreshed
- Redirected to owner dashboard

---

### 2. Admin Google Login

#### Test Case 2.1: Existing Admin Linking Google
**Steps:**
1. Owner creates admin account with email: `admin@example.com`
2. Admin logs out
3. Navigate to `/admin/login`
4. Click "Continue with Google"
5. Login with Google account using same email

**Expected Result:**
- Google account linked to existing admin
- Redirected to admin dashboard
- Google ID, token, and avatar saved

#### Test Case 2.2: New Admin via Google (Should Fail)
**Steps:**
1. Navigate to `/admin/login`
2. Click "Continue with Google"
3. Login with Google account that doesn't exist in system

**Expected Result:**
- Error message: "No admin account found with this email. Please contact the owner."
- Redirected back to login page
- No account created

#### Test Case 2.3: Inactive Admin (Should Fail)
**Steps:**
1. Owner sets admin status to inactive
2. Navigate to `/admin/login`
3. Click "Continue with Google"
4. Login with linked Google account

**Expected Result:**
- Error message: "Your account is inactive. Please contact the owner."
- Redirected back to login page

---

### 3. Seller Google Login

#### Test Case 3.1: New Seller via Google
**Steps:**
1. Navigate to `/seller/login`
2. Click "Continue with Google"
3. Select a Google account
4. Authorize the application

**Expected Result:**
- New seller account created
- Email automatically verified
- Status set to inactive (0)
- `is_approved` set to false
- Error message: "Your account is pending approval. Please wait for admin approval."

#### Test Case 3.2: Existing Seller Linking Google
**Steps:**
1. Create seller account with email: `seller@example.com`
2. Owner/Admin approves seller and sets status to active
3. Seller logs out
4. Click "Continue with Google"
5. Login with Google account using same email

**Expected Result:**
- Google account linked to existing seller
- Redirected to seller dashboard
- Google ID, token, and avatar saved

#### Test Case 3.3: Unapproved Seller (Should Fail)
**Steps:**
1. Create new seller via Google (Test 3.1)
2. Try to login again with same Google account

**Expected Result:**
- Error message: "Your account is pending approval. Please wait for admin approval."
- Redirected back to login page

#### Test Case 3.4: Inactive Seller (Should Fail)
**Steps:**
1. Owner/Admin sets approved seller status to inactive
2. Navigate to `/seller/login`
3. Click "Continue with Google"
4. Login with linked Google account

**Expected Result:**
- Error message: "Your account is inactive. Please contact the administrator."
- Redirected back to login page

---

### 4. Staff Google Login

#### Test Case 4.1: Existing Staff Linking Google
**Steps:**
1. Admin creates staff account with email: `staff@example.com`
2. Staff logs out
3. Navigate to `/staff/login`
4. Click "Continue with Google"
5. Login with Google account using same email

**Expected Result:**
- Google account linked to existing staff
- Redirected to staff dashboard
- Google ID, token, and avatar saved

#### Test Case 4.2: New Staff via Google (Should Fail)
**Steps:**
1. Navigate to `/staff/login`
2. Click "Continue with Google"
3. Login with Google account that doesn't exist in system

**Expected Result:**
- Error message: "No staff account found with this email. Please contact the administrator."
- Redirected back to login page
- No account created

#### Test Case 4.3: Inactive Staff (Should Fail)
**Steps:**
1. Admin sets staff status to inactive
2. Navigate to `/staff/login`
3. Click "Continue with Google"
4. Login with linked Google account

**Expected Result:**
- Error message: "Your account is inactive. Please contact the administrator."
- Redirected back to login page

---

## Database Verification

After each successful Google login, verify in the database:

```sql
-- Check Owner
SELECT id, email, google_id, avatar, email_verified_at, last_login_at 
FROM owners 
WHERE email = 'test@example.com';

-- Check Admin
SELECT id, email, google_id, avatar, email_verified_at, last_login_at 
FROM admins 
WHERE email = 'admin@example.com';

-- Check Seller
SELECT id, email, google_id, avatar, email_verified_at, status, is_approved, last_login_at 
FROM sellers 
WHERE email = 'seller@example.com';

-- Check Staff
SELECT id, email, google_id, avatar, email_verified_at, last_login_at 
FROM staffs 
WHERE email = 'staff@example.com';
```

**Verify:**
- ✅ `google_id` is populated
- ✅ `avatar` contains Google profile picture URL
- ✅ `email_verified_at` is set
- ✅ `last_login_at` is updated
- ✅ `last_login_ip` is recorded

---

## Error Handling Tests

### Test OAuth Errors

#### Test Case E.1: Cancel OAuth Flow
**Steps:**
1. Click "Continue with Google"
2. Cancel the authorization on Google's page

**Expected Result:**
- Error message: "Failed to login with Google. Please try again."
- Redirected back to login page

#### Test Case E.2: Invalid Credentials
**Steps:**
1. Temporarily set invalid Google credentials in `.env`
2. Click "Continue with Google"

**Expected Result:**
- Error message displayed
- Redirected back to login page
- Error logged in Laravel logs

---

## Session Management Tests

### Test Case S.1: Session Guard
**Steps:**
1. Login via Google as Admin
2. Check session data

**Expected Result:**
- Session guard set to 'admin'
- Session regenerated
- Remember token created

### Test Case S.2: Multiple Role Logins
**Steps:**
1. Login as Owner via Google
2. In another browser/incognito, login as Admin via Google
3. Verify both sessions work independently

**Expected Result:**
- Both sessions active
- No cross-contamination
- Correct dashboard for each role

---

## UI/UX Tests

### Test Case U.1: Button Visibility
**Steps:**
1. Visit each login page
   - `/owner/login`
   - `/admin/login`
   - `/seller/login`
   - `/staff/login`

**Expected Result:**
- "Continue with Google" button visible on all pages
- Button has Google icon
- Divider line displays correctly
- Button is styled consistently

### Test Case U.2: Responsive Design
**Steps:**
1. Test login pages on:
   - Desktop (1920x1080)
   - Tablet (768x1024)
   - Mobile (375x667)

**Expected Result:**
- Google button displays correctly on all screen sizes
- Button is full-width
- Text is readable

---

## Security Tests

### Test Case SEC.1: Token Storage
**Steps:**
1. Login via Google
2. Check database for token storage

**Expected Result:**
- `google_token` stored as encrypted text
- `google_refresh_token` stored as encrypted text
- Tokens not visible in browser

### Test Case SEC.2: CSRF Protection
**Steps:**
1. Attempt to access callback URL directly without state parameter

**Expected Result:**
- Request rejected
- Error displayed

---

## Checklist

Before marking as complete, verify:

- [ ] All 4 roles have Google login buttons
- [ ] Owner can create account via Google
- [ ] Admin cannot create account via Google
- [ ] Seller can create account (pending approval)
- [ ] Staff cannot create account via Google
- [ ] Existing accounts can link Google
- [ ] Inactive accounts cannot login
- [ ] Unapproved sellers cannot login
- [ ] Email is auto-verified on Google login
- [ ] Avatar is saved from Google profile
- [ ] Last login info is updated
- [ ] Session guard is set correctly
- [ ] Error messages are user-friendly
- [ ] Logs are created for errors
- [ ] UI is consistent across all roles
- [ ] Responsive design works

---

## Troubleshooting

### Issue: "Failed to login with Google"
**Solutions:**
1. Check `.env` credentials
2. Verify callback URL in Google Console
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure Socialite package is installed

### Issue: "No account found"
**Solutions:**
1. Verify email matches exactly
2. Check if account exists in database
3. Verify role (admin/staff need pre-created accounts)

### Issue: "Account is inactive"
**Solutions:**
1. Check `status` column in database
2. Owner/Admin should activate the account

### Issue: Redirect loop
**Solutions:**
1. Clear browser cookies
2. Check session configuration
3. Verify middleware is correct

---

## Test Results Template

```
Test Date: _______________
Tester: _______________

| Test Case | Status | Notes |
|-----------|--------|-------|
| Owner 1.1 | ☐ Pass ☐ Fail | |
| Owner 1.2 | ☐ Pass ☐ Fail | |
| Owner 1.3 | ☐ Pass ☐ Fail | |
| Admin 2.1 | ☐ Pass ☐ Fail | |
| Admin 2.2 | ☐ Pass ☐ Fail | |
| Admin 2.3 | ☐ Pass ☐ Fail | |
| Seller 3.1 | ☐ Pass ☐ Fail | |
| Seller 3.2 | ☐ Pass ☐ Fail | |
| Seller 3.3 | ☐ Pass ☐ Fail | |
| Seller 3.4 | ☐ Pass ☐ Fail | |
| Staff 4.1 | ☐ Pass ☐ Fail | |
| Staff 4.2 | ☐ Pass ☐ Fail | |
| Staff 4.3 | ☐ Pass ☐ Fail | |
```
