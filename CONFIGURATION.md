# Laravel Project Configuration - Remove /public from URL

## Overview
This document describes the project-level changes made to configure your Laravel 12 project to work without `php artisan serve` and remove `/public` from the URL.

## Files Created/Modified

### 1. Root index.php (`c:\wamp64\www\bmv\index.php`)
**Purpose**: Main entry point that handles all requests and forwards them to Laravel's public/index.php

**Features**:
- Handles routing for the `/bmv` subdirectory
- Serves static files (CSS, JS, images, fonts) directly from the public directory
- Forwards dynamic requests to Laravel's public/index.php
- Properly adjusts server variables ($_SERVER) for Laravel routing
- Supports query strings

### 2. Root .htaccess (`c:\wamp64\www\bmv\.htaccess`)
**Purpose**: Apache configuration for URL rewriting and security

**Features**:
- Sets DirectoryIndex to index.php (fallback when mod_rewrite is unavailable)
- Disables directory browsing for security
- Protects sensitive files (dotfiles)
- Uses mod_rewrite when available for optimal performance
- Handles authorization headers for Laravel
- Rewrites all requests to index.php while preserving existing files/directories

### 3. Fallback index.html (`c:\wamp64\www\bmv\index.html`)
**Purpose**: Additional fallback for edge cases

## Expected URLs

After configuration, your site should be accessible at:
- ✓ `http://localhost/bmv/admin/login` (without /public)
- ✓ `http://localhost/bmv/` (redirects to Laravel)
- ✓ `http://localhost/bmv/any/route` (all Laravel routes work)

## Server Requirements

For this configuration to work, your Apache server must have:

1. **mod_rewrite enabled** ✓ (Confirmed during setup)
2. **AllowOverride set to All** (or at least `FileInfo Indexes`)

## Troubleshooting

### If you get 404 errors:

1. **Check WAMP is running**:
   - Open WAMP control panel
   - Ensure Apache service is started (green icon)

2. **Enable AllowOverride**:
   Edit your Apache configuration file (`c:\wamp64\bin\apache\apache2.x.x\conf\httpd.conf`):
   
   Find the section for `c:/wamp64/www` and ensure it looks like this:
   ```apache
   <Directory "c:/wamp64/www">
       Options +Indexes +FollowSymLinks +MultiViews
       AllowOverride All
       Require local
   </Directory>
   ```

3. **Verify mod_rewrite is enabled**:
   In `httpd.conf`, ensure this line is uncommented:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

4. **Restart Apache**:
   After making any changes to Apache configuration, restart the Apache service through WAMP.

### If static assets (CSS/JS) don't load:

- Check the browser console for 404 errors
- Verify the files exist in `c:\wamp64\www\bmv\public\`
- Clear your browser cache

### If you get a blank page:

- Check Laravel's error logs in `storage/logs/laravel.log`
- Ensure your `.env` file is properly configured
- Run `php artisan config:clear` and `php artisan cache:clear`

## How It Works

1. **Request arrives**: `http://localhost/bmv/admin/login`
2. **Apache processes .htaccess**: Checks if the file/directory exists
3. **Rewrites to index.php**: Since `/admin/login` doesn't exist as a file, it rewrites to `index.php`
4. **Root index.php executes**:
   - Removes `/bmv` from the URI
   - Checks if it's a static file request
   - If not, adjusts server variables and includes `public/index.php`
5. **Laravel handles the request**: Routes to the appropriate controller

## Testing

To verify the configuration is working:

1. Access `http://localhost/bmv/check-rewrite.php` to verify mod_rewrite status
2. Access `http://localhost/bmv/admin/login` to test the login page
3. Check that CSS/JS files load correctly in the browser developer tools

## Notes

- This configuration uses **project-level changes only**
- No virtual host configuration is required
- Works with WAMP's default localhost setup
- Compatible with Laravel 12
- Maintains proper routing for all Laravel features

## Created On
2025-12-11

## Status
✓ Project files configured
⚠ Requires server-level AllowOverride configuration to be set to "All"
