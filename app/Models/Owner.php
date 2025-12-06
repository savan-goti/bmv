<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Owner extends Authenticatable
{
    use Notifiable;

    protected $table = 'owners';
    protected $fillable = [
        'session_id',
        'bar_code',
        'token',
        'profile_image',
        'full_name',
        'username',
        'email',
        'phone_code',
        'phone',
        'otp',
        'password',
        'dob',
        'gender',
        'address',
        'description',
        'permissions',
        'language_preference',
        'marital_status',
        'status',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'created_by',
        'creator_id',
    ];

    protected $hidden = [
        'password',
        'token',
        'otp',
        'remember_token',
    ];

    protected $casts = [
        'permissions' => 'array',
        'dob' => 'date',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Relationship: Owner created by another owner
     */
    public function creator()
    {
        return $this->belongsTo(Owner::class, 'creator_id');
    }

    /**
     * Relationship: Owners created by this owner
     */
    public function createdOwners()
    {
        return $this->hasMany(Owner::class, 'created_by');
    }

    /**
     * Accessor for full profile image URL
     */
    function getProfileImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(OWNER_PROFILE_IMAGE_PATH) . $image)) {
                $outputImage = asset(OWNER_PROFILE_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
}
