<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'light_logo',
        'dark_logo',
        'favicon',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'pinterest_url',
        'tiktok_url',
        'whatsapp_url',
        'telegram_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

     function getLightLogoAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SETTINGS_IMAGE_PATH) . $image)) {
                $outputImage = asset(SETTINGS_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
    function getDarkLogoAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SETTINGS_IMAGE_PATH) . $image)) {
                $outputImage = asset(SETTINGS_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
    function getFaviconAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(SETTINGS_IMAGE_PATH) . $image)) {
                $outputImage = asset(SETTINGS_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
}
