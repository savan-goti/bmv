<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'dob' => 'date',
            'otp_expired_at' => 'datetime',
            'phone_validate' => 'boolean',
            'social_links' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }
}
