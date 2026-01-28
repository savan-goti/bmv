<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Config;

class TestTwilio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio:test {phone? : Phone number to send test SMS (with country code)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Twilio SMS functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('   TWILIO SMS TEST');
        $this->info('========================================');
        $this->newLine();

        // Check Twilio configuration
        $this->info('Checking Twilio Configuration...');
        $this->line('--------------------------------');
        
        $sid = Config::get('services.twilio.sid');
        $token = Config::get('services.twilio.token');
        $from = Config::get('services.twilio.from');
        
        $this->line('TWILIO_SID: ' . ($sid ? '✓ Configured' : '✗ Missing'));
        $this->line('TWILIO_AUTH_TOKEN: ' . ($token ? '✓ Configured' : '✗ Missing'));
        $this->line('TWILIO_PHONE_NUMBER: ' . ($from ? "✓ Configured ($from)" : '✗ Missing'));
        $this->newLine();

        if (!$sid || !$token || !$from) {
            $this->error('❌ Twilio credentials are not properly configured!');
            $this->newLine();
            $this->warn('Please add the following to your .env file:');
            $this->line('TWILIO_SID=your_account_sid');
            $this->line('TWILIO_AUTH_TOKEN=your_auth_token');
            $this->line('TWILIO_PHONE_NUMBER=+1234567890');
            return Command::FAILURE;
        }

        // Get phone number
        $phone = $this->argument('phone');
        
        if (!$phone) {
            $phone = $this->ask('Enter phone number to test (with country code, e.g., +919876543210)');
        }

        if (empty($phone)) {
            $this->error('❌ Phone number is required');
            return Command::FAILURE;
        }

        // Validate phone format
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $phone)) {
            $this->warn('⚠️  Phone number should be in E.164 format (e.g., +919876543210)');
            if (!$this->confirm('Continue anyway?', true)) {
                return Command::FAILURE;
            }
        }

        // Generate test OTP
        $otp = TwilioService::generateOTP(6);
        $this->info("Generated Test OTP: $otp");
        $this->line('--------------------------------');
        $this->newLine();

        // Send test SMS
        $this->info('Sending test SMS via Twilio...');
        
        try {
            $twilioService = new TwilioService();
            $message = "Your BMV test OTP is: $otp. This is a test message.";
            
            $result = $twilioService->sendOTP($phone, $message);
            
            if ($result) {
                $this->newLine();
                $this->info('✅ SUCCESS: SMS sent successfully!');
                $this->line("Check your phone ($phone) for the OTP message.");
                $this->newLine();
                
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['OTP Code', $otp],
                        ['Expires In', TwilioService::getOTPExpirationMinutes() . ' minutes'],
                        ['Phone Number', $phone],
                        ['From Number', $from],
                    ]
                );
            } else {
                $this->newLine();
                $this->error('❌ FAILED: Could not send SMS');
                $this->warn('Check the logs for more details: storage/logs/laravel.log');
            }
            
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ ERROR: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Full Error Details:');
            $this->line($e->getTraceAsString());
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('   TEST COMPLETE');
        $this->info('========================================');
        $this->newLine();
        
        $this->comment('💡 TIP: Check logs for detailed information:');
        $this->line('   storage/logs/laravel.log');
        $this->newLine();

        return Command::SUCCESS;
    }
}
