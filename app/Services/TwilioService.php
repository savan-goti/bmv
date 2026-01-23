<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TwilioService
{
    protected $twilio;
    protected $from;

    public function __construct()
    {
        $sid = Config::get('services.twilio.sid');
        $token = Config::get('services.twilio.token');
        
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = Config::get('services.twilio.from');

        // Create Twilio client
        $this->twilio = new Client($sid, $token);
    }

    /**
     * Send OTP via SMS
     *
     * @param string $to Phone number with country code
     * @param string $otp OTP code
     * @return bool
     */
    public function sendOTP($to, $otp)
    {
        try {
            $message = "Your BMV verification code is: {$otp}. This code will expire in 10 minutes. Do not share this code with anyone.";
            $this->twilio->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            Log::info('OTP sent successfully', ['phone' => $to]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'phone' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send custom SMS
     *
     * @param string $to Phone number with country code
     * @param string $message Message content
     * @return bool
     */
   public function sendSms(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Twilio SMS Error: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Generate random OTP
     *
     * @param int $length
     * @return string
     */
    public static function generateOTP($length = 6)
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }

    /**
     * Get OTP expiration time (in minutes)
     *
     * @return int
     */
    public static function getOTPExpirationMinutes()
    {
        return config('services.twilio.otp_expiration', 10);
    }
}
