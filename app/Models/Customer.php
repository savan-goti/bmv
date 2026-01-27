<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'profile_image',
        'canonical',
        'username',
        'name',
        'email',
        'phone',
        'country_code',
        'phone_otp',
        'otp_expired_at',
        'phone_validate',
        'email_otp',
        'email_otp_expired_at',
        'gender',
        'dob',
        'address',
        'latitude',
        'longitude',
        'city',
        'state',
        'country',
        'pincode',
        'social_links',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'phone_otp',
        'email_otp',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'dob' => 'date',
            'otp_expired_at' => 'datetime',
            'email_otp_expired_at' => 'datetime',
            'phone_validate' => 'boolean',
            'social_links' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
