# Fix cURL SSL Certificate Error

## ❌ Error
```
cURL error 60: SSL certificate problem: unable to get local issuer certificate
```

## ✅ Solution

This error occurs because PHP/cURL on Windows doesn't have the CA certificates needed to verify SSL connections. Here are **3 solutions** (choose one):

---

## Solution 1: Download CA Certificate Bundle (RECOMMENDED)

### Step 1: Download the Certificate
1. Go to: https://curl.se/ca/cacert.pem
2. Download the `cacert.pem` file
3. Save it to a safe location, for example:
   ```
   C:\wamp64\bin\php\php8.x.x\extras\ssl\cacert.pem
   ```
   (Create the `extras\ssl` folder if it doesn't exist)

### Step 2: Update php.ini
1. Find your `php.ini` file:
   - For WAMP: `C:\wamp64\bin\apache\apache2.x.x\bin\php.ini`
   - Or: `C:\wamp64\bin\php\php8.x.x\php.ini`
   
2. Open `php.ini` in a text editor (as Administrator)

3. Find this line (use Ctrl+F to search):
   ```ini
   ;curl.cainfo =
   ```

4. Replace it with (remove the semicolon and add the path):
   ```ini
   curl.cainfo = "C:\wamp64\bin\php\php8.x.x\extras\ssl\cacert.pem"
   ```

5. Also find and update this line:
   ```ini
   ;openssl.cafile=
   ```
   
   Replace with:
   ```ini
   openssl.cafile="C:\wamp64\bin\php\php8.x.x\extras\ssl\cacert.pem"
   ```

6. Save the file

### Step 3: Restart WAMP
1. Stop WAMP
2. Start WAMP
3. Test again

---

## Solution 2: Disable SSL Verification (ONLY FOR LOCAL DEVELOPMENT)

**⚠️ WARNING: This is NOT secure and should ONLY be used for local development!**

### Option A: Via Guzzle Configuration

Create a new file: `config/services.php` (or update existing) and modify the Google configuration:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('APP_URL') . '/owner/auth/google/callback',
    'guzzle' => [
        'verify' => false, // Disable SSL verification
    ],
],
```

### Option B: Via Environment Variable

Add this to your `.env` file:
```env
CURL_VERIFY=false
```

Then update each AuthController's `redirectToGoogle()` method:

```php
public function redirectToGoogle()
{
    $driver = Socialite::driver('google')
        ->redirectUrl(route('owner.auth.google.callback'));
    
    if (env('CURL_VERIFY') === 'false') {
        $driver->setHttpClient(
            new \GuzzleHttp\Client(['verify' => false])
        );
    }
    
    return $driver->redirect();
}
```

---

## Solution 3: Quick Fix for Testing

Add this to the top of your `handleGoogleCallback` method (TEMPORARY):

```php
public function handleGoogleCallback(Request $request)
{
    // TEMPORARY: Disable SSL verification for local development
    $client = new \GuzzleHttp\Client(['verify' => false]);
    Socialite::driver('google')->setHttpClient($client);
    
    try {
        $googleUser = Socialite::driver('google')->user();
        // ... rest of your code
```

---

## ✅ RECOMMENDED: Use Solution 1

Solution 1 is the **best and most secure** option. It properly configures SSL certificates.

---

## Quick Implementation

I'll implement **Solution 2 (Option A)** for you, which is safe for local development:
