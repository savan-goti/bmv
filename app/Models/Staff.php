<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'staffs';

    protected $fillable = [
        'admin_id',
        'profile_image',
        'avatar',
        'name',
        'father_name',
        'email',
        'google_id',
        'google_token',
        'google_refresh_token',
        'phone',
        'date_of_birth',
        'gender',
        'password',
        'assigned_role',
        'permissions',
        'education',
        'position_id',
        'address',
        'status',
        'resignation_date',
        'purpose',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'login_auth_method',
        'login_email_verification_enabled',
        'login_verification_code',
        'login_verification_code_expires_at',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'permissions' => 'array',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'login_email_verification_enabled' => 'boolean',
            'login_verification_code_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the admin associated with the staff.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the job position associated with the staff.
     */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'position_id');
    }

    /**
     * Get all branch positions for the staff.
     */
    public function branchPositions()
    {
        return $this->morphMany(BranchPosition::class, 'positionable');
    }

    function getProfileImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(STAFF_PROFILE_IMAGE_PATH) . $image)) {
                $outputImage = asset(STAFF_PROFILE_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
}
