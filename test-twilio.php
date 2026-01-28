#!/usr/bin/env php
<?php

/**
 * Twilio Test Script
 * 
 * This script tests the Twilio SMS functionality
 * Run: php test-twilio.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

echo "\n";
echo "========================================\n";
echo "   TWILIO SMS TEST SCRIPT\n";
echo "========================================\n\n";

// Check if Twilio credentials are configured
$sid = config('services.twilio.sid');
$token = config('services.twilio.token');
$from = config('services.twilio.from');

echo "Checking Twilio Configuration...\n";
echo "--------------------------------\n";
echo "TWILIO_SID: " . ($sid ? "✓ Configured" : "✗ Missing") . "\n";
echo "TWILIO_AUTH_TOKEN: " . ($token ? "✓ Configured" : "✗ Missing") . "\n";
echo "TWILIO_PHONE_NUMBER: " . ($from ? "✓ Configured ($from)" : "✗ Missing") . "\n";
echo "\n";

if (!$sid || !$token || !$from) {
    echo "❌ ERROR: Twilio credentials are not properly configured in .env file\n";
    echo "\nPlease add the following to your .env file:\n";
    echo "TWILIO_SID=your_account_sid\n";
    echo "TWILIO_AUTH_TOKEN=your_auth_token\n";
    echo "TWILIO_PHONE_NUMBER=+1234567890\n\n";
    exit(1);
}

// Ask for test phone number
echo "Enter phone number to test (with country code, e.g., +919876543210): ";
$testPhone = trim(fgets(STDIN));

if (empty($testPhone)) {
    echo "❌ ERROR: Phone number is required\n";
    exit(1);
}

// Validate phone number format
if (!preg_match('/^\+[1-9]\d{1,14}$/', $testPhone)) {
    echo "⚠️  WARNING: Phone number should be in E.164 format (e.g., +919876543210)\n";
    echo "Continuing anyway...\n\n";
}

// Generate test OTP
$otp = TwilioService::generateOTP(6);
echo "\nGenerated Test OTP: $otp\n";
echo "--------------------------------\n\n";

// Test Twilio SMS
echo "Sending test SMS via Twilio...\n";

try {
    $twilioService = new TwilioService();
    $message = "Your BMV test OTP is: $otp. This is a test message from Twilio integration.";
    
    $result = $twilioService->sendOTP($testPhone, $message);
    
    if ($result) {
        echo "\n✅ SUCCESS: SMS sent successfully!\n";
        echo "Check your phone ($testPhone) for the OTP message.\n";
        echo "\nOTP Details:\n";
        echo "  - OTP Code: $otp\n";
        echo "  - Expires In: " . TwilioService::getOTPExpirationMinutes() . " minutes\n";
        echo "  - Phone: $testPhone\n";
    } else {
        echo "\n❌ FAILED: Could not send SMS\n";
        echo "Check the logs for more details: storage/logs/laravel.log\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nFull Error Details:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n========================================\n";
echo "   TEST COMPLETE\n";
echo "========================================\n\n";

// Show log location
echo "💡 TIP: Check logs for detailed information:\n";
echo "   storage/logs/laravel.log\n\n";
