# Role-Based Session Management Implementation

## Overview
Implemented **role-based session management** that separates sessions by user type (guard), ensuring that staff, sellers, admins, and owners each have isolated session tracking and management.

## Problem Solved
Previously, all users shared the same session pool based only on `user_id`. This meant:
- A staff member with ID 1 and a seller with ID 1 would see each other's sessions
- No way to differentiate between different user types in session management
- Security concern: users could potentially interfere with sessions from different roles

## Solution
Added a `guard` column to the `sessions` table to track which authentication guard (user type) each session belongs to.

---

## Implementation Details

### 1. Database Migration
**File**: `database/migrations/2025_12_05_143621_add_guard_to_sessions_table.php`

Added `guard` column to the `sessions` table:
```php
Schema::table('sessions', function (Blueprint $table) {
    $table->string('guard')->nullable()->after('user_id')->index();
});
```

**Purpose**: Stores the guard name ('staff', 'seller', 'admin', 'owner') for each session.

### 2. Middleware: SetSessionGuard
**File**: `app/Http/Middleware/SetSessionGuard.php`

Automatically sets the guard name in the session record when a user is authenticated:

```php
public function handle(Request $request, Closure $next, string $guard = null): Response
{
    $response = $next($request);

    // If user is authenticated, update the session guard
    if ($guard && Auth::guard($guard)->check()) {
        $this->updateSessionGuard($request, $guard);
    }

    return $response;
}

protected function updateSessionGuard(Request $request, string $guard): void
{
    $sessionId = $request->session()->getId();
    
    if ($sessionId) {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update(['guard' => $guard]);
    }
}
```

**How it works**:
1. Middleware receives the guard name as a parameter
2. After processing the request, it checks if user is authenticated
3. If authenticated, updates the session record with the guard name

### 3. Middleware Registration
**File**: `bootstrap/app.php`

Registered the middleware alias:
```php
$middleware->alias([
    'auth' => \App\Http\Middleware\Authenticate::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'detect.bad.clients' => \App\Http\Middleware\DetectBadClients::class,
    'session.guard' => \App\Http\Middleware\SetSessionGuard::class, // NEW
]);
```

### 4. Route Updates

#### Staff Routes (`routes/staff.php`)
```php
Route::middleware(['auth:staff', 'session.guard:staff'])->group(function () {
    // All authenticated staff routes
});
```

#### Seller Routes (`routes/seller.php`)
```php
Route::middleware(['auth:seller', 'session.guard:seller'])->group(function () {
    // All authenticated seller routes
});
```

**Note**: Apply the same pattern to `admin` and `owner` routes.

### 5. Controller Updates

#### Staff Settings Controller
**File**: `app/Http/Controllers/Staff/SettingsController.php`

All session queries now filter by `guard = 'staff'`:

**getSessions()**:
```php
$sessions = DB::table('sessions')
    ->where('user_id', $staff->id)
    ->where('guard', 'staff')  // NEW: Only staff sessions
    ->orderBy('last_activity', 'desc')
    ->get();
```

**logoutSession()**:
```php
$session = DB::table('sessions')
    ->where('id', $sessionId)
    ->where('user_id', $staff->id)
    ->where('guard', 'staff')  // NEW: Verify it's a staff session
    ->first();

DB::table('sessions')
    ->where('id', $sessionId)
    ->where('guard', 'staff')  // NEW: Only delete staff sessions
    ->delete();
```

**logoutOtherSessions()**:
```php
DB::table('sessions')
    ->where('user_id', $staff->id)
    ->where('guard', 'staff')  // NEW: Only delete staff sessions
    ->where('id', '!=', $currentSessionId)
    ->delete();
```

#### Seller Settings Controller
**File**: `app/Http/Controllers/Seller/SettingsController.php`

Same changes as Staff controller, but filtering by `guard = 'seller'`.

---

## How It Works

### Session Creation Flow
1. User logs in (e.g., staff member)
2. Laravel creates a session record in the `sessions` table
3. `session.guard:staff` middleware runs
4. Middleware updates the session record: `guard = 'staff'`

### Session Management Flow
1. Staff member visits `/staff/settings`
2. Clicks to view active sessions
3. Controller queries: `WHERE user_id = X AND guard = 'staff'`
4. Only staff sessions are returned (not seller/admin/owner sessions)
5. Staff can logout individual sessions or all other sessions
6. All logout operations are filtered by `guard = 'staff'`

---

## Benefits

### 1. **Session Isolation**
- Staff sessions are completely separate from seller sessions
- A staff member with ID 1 cannot see or manage a seller's sessions (even if seller also has ID 1)

### 2. **Security**
- Prevents cross-role session interference
- Users can only manage sessions from their own role
- No accidental logout of sessions from different user types

### 3. **Clarity**
- Clear separation of concerns
- Easy to track which user type each session belongs to
- Better auditing and monitoring capabilities

### 4. **Scalability**
- Easy to add new user types (just add new guard)
- Consistent pattern across all user roles
- Minimal code duplication

---

## Database Schema

### Sessions Table Structure
```
+----------------+------------------+
| Column         | Type             |
+----------------+------------------+
| id             | string (PK)      |
| user_id        | bigint (nullable)|
| guard          | string (nullable)| ← NEW
| ip_address     | string (nullable)|
| user_agent     | text (nullable)  |
| payload        | longtext         |
| last_activity  | integer          |
+----------------+------------------+

Indexes:
- id (PRIMARY)
- user_id (INDEX)
- guard (INDEX)  ← NEW
- last_activity (INDEX)
```

---

## Example Scenarios

### Scenario 1: Staff and Seller with Same ID
**Before**:
- Staff ID 1 logs in → creates session A
- Seller ID 1 logs in → creates session B
- Staff views sessions → sees both A and B ❌

**After**:
- Staff ID 1 logs in → creates session A with `guard='staff'`
- Seller ID 1 logs in → creates session B with `guard='seller'`
- Staff views sessions → sees only A ✅
- Seller views sessions → sees only B ✅

### Scenario 2: Multi-Device Login
**Staff Member**:
- Logs in on desktop → Session 1 (`guard='staff'`)
- Logs in on mobile → Session 2 (`guard='staff'`)
- Views sessions → sees both Session 1 and 2
- Logs out from mobile → only Session 2 is deleted

**Seller with Same User ID**:
- Logs in on tablet → Session 3 (`guard='seller'`)
- Views sessions → sees only Session 3
- Staff's sessions are completely invisible to seller

---

## Testing

### Manual Testing Steps

1. **Test Session Isolation**:
   - Create a staff account with ID 1
   - Create a seller account with ID 1
   - Log in as staff on one browser
   - Log in as seller on another browser
   - Check staff settings → should only see staff session
   - Check seller settings → should only see seller session

2. **Test Session Logout**:
   - Log in as staff on 2 different browsers
   - From one browser, logout the other session
   - Verify the other session is terminated
   - Verify seller sessions (if any) are unaffected

3. **Test Logout All Others**:
   - Log in as staff on 3 browsers
   - From one browser, click "Logout All Other Sessions"
   - Verify only the current session remains
   - Verify other user types' sessions are unaffected

---

## Future Enhancements

1. **Add Guard to Admin and Owner Routes**:
   ```php
   // routes/admin.php
   Route::middleware(['auth:admin', 'session.guard:admin'])->group(...);
   
   // routes/owner.php
   Route::middleware(['auth:owner', 'session.guard:owner'])->group(...);
   ```

2. **Session Analytics**:
   - Track session count by guard
   - Monitor active users per role
   - Generate reports on session activity

3. **Enhanced Security**:
   - Add device fingerprinting
   - Detect suspicious session patterns
   - Alert users of new logins from unknown devices

---

## Migration Command

To apply the changes:
```bash
php artisan migrate
```

This will add the `guard` column to the `sessions` table.

---

## Summary

✅ **Sessions are now role-based**
✅ **Staff can only see/manage staff sessions**
✅ **Sellers can only see/manage seller sessions**
✅ **Complete isolation between user types**
✅ **Improved security and clarity**
✅ **Scalable architecture for future roles**

The implementation ensures that each user role has its own isolated session management, preventing any cross-role interference and providing a more secure and organized system.
