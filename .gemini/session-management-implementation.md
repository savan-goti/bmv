# Session Management Implementation

## Overview
Implemented comprehensive session management functionality for **Staff** and **Seller** panels, allowing users to view and manage their active sessions across different browsers and devices.

## Features Implemented

### 1. Backend Controllers

#### Staff Settings Controller (`app/Http/Controllers/Staff/SettingsController.php`)
- **getSessions()**: Retrieves all active sessions for the authenticated staff member
- **logoutSession()**: Logs out from a specific session by session ID
- **logoutOtherSessions()**: Logs out from all sessions except the current one

#### Seller Settings Controller (`app/Http/Controllers/Seller/SettingsController.php`)
- **getSessions()**: Retrieves all active sessions for the authenticated seller
- **logoutSession()**: Logs out from a specific session by session ID
- **logoutOtherSessions()**: Logs out from all sessions except the current one

### 2. Routes

#### Staff Routes (`routes/staff.php`)
```php
Route::get('/settings/sessions', 'getSessions')->name('settings.sessions');
Route::post('/settings/sessions/logout', 'logoutSession')->name('settings.sessions.logout');
Route::post('/settings/sessions/logout-others', 'logoutOtherSessions')->name('settings.sessions.logout-others');
```

#### Seller Routes (`routes/seller.php`)
```php
Route::get('/settings/sessions', 'getSessions')->name('settings.sessions');
Route::post('/settings/sessions/logout', 'logoutSession')->name('settings.sessions.logout');
Route::post('/settings/sessions/logout-others', 'logoutOtherSessions')->name('settings.sessions.logout-others');
```

### 3. Frontend Views

#### Staff Settings View (`resources/views/staff/settings/index.blade.php`)
- Added "Active Sessions" card displaying all active sessions
- Shows device type, browser, operating system, IP address, and last activity time
- Highlights current session with a badge
- Provides individual logout buttons for each session
- "Logout All Other Sessions" button to terminate all sessions except current

#### Seller Settings View (`resources/views/seller/settings/index.blade.php`)
- Same features as staff settings view
- Consistent UI/UX across both panels

### 4. JavaScript Functionality

Both views include:
- **loadSessions()**: Fetches active sessions via AJAX
- **displaySessions()**: Renders session cards with device information
- **parseUserAgent()**: Parses user agent string to extract:
  - Device type (Desktop, Mobile, Tablet)
  - Browser (Chrome, Firefox, Safari, Edge, Opera)
  - Operating System (Windows, macOS, Linux, Android, iOS)
  - Appropriate icon for device type
- **Logout handlers**: AJAX requests for logging out individual or all other sessions
- **Auto-refresh**: Sessions are reloaded after logout actions

### 5. Security Features

- **Session Validation**: Verifies session belongs to the authenticated user
- **Current Session Protection**: Prevents users from logging out their current session
- **Confirmation Dialogs**: Requires confirmation before logout actions
- **CSRF Protection**: All POST requests include CSRF tokens

### 6. UI/UX Features

- **Loading States**: Spinner shown while sessions are being loaded
- **Visual Indicators**: 
  - Current session highlighted with primary border and background
  - "Current Session" badge
  - Device-specific icons (computer, smartphone, tablet)
- **Responsive Design**: Works on all screen sizes
- **Error Handling**: Displays user-friendly error messages
- **Success Notifications**: Confirms successful logout actions

## Database

Uses Laravel's built-in `sessions` table with the following structure:
- `id`: Session identifier
- `user_id`: ID of the authenticated user
- `ip_address`: IP address of the session
- `user_agent`: Browser user agent string
- `last_activity`: Timestamp of last activity
- `payload`: Session data

## Configuration

Session driver is configured in `config/session.php`:
```php
'driver' => env('SESSION_DRIVER', 'database'),
'table' => env('SESSION_TABLE', 'sessions'),
```

## Usage

### For Staff:
1. Navigate to `/staff/settings`
2. Scroll to "Active Sessions" section
3. View all active sessions with device details
4. Click "Logout" on individual sessions to terminate them
5. Click "Logout All Other Sessions" to terminate all except current

### For Sellers:
1. Navigate to `/seller/settings`
2. Follow the same steps as staff

## Benefits

1. **Enhanced Security**: Users can monitor and control access to their accounts
2. **Multi-Device Management**: Easy to see and manage sessions across devices
3. **Suspicious Activity Detection**: Users can identify unauthorized sessions
4. **Privacy Control**: Ability to remotely logout from forgotten sessions
5. **User Awareness**: Clear visibility of active login locations and devices

## Technical Notes

- Sessions are stored in the database for persistence
- User agent parsing is done client-side for performance
- All session operations are performed via AJAX for smooth UX
- Session IDs are never exposed in the UI for security
