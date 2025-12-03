<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'profile_image',
        'name',
        'father_name',
        'email',
        'date_of_birth',
        'gender',
        'phone',
        'password',
        'role',
        'education',
        'position_id',
        'address',
        'status',
        'resignation_date',
        'purpose',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the job position associated with the admin.
     */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'position_id');
    }

    /**
     * Get all branch positions for the admin.
     */
    public function branchPositions()
    {
        return $this->morphMany(BranchPosition::class, 'positionable');
    }

    /**
     * Get all staff members belonging to this admin.
     */
    public function staffs()
    {
        return $this->hasMany(Staff::class, 'admin_id');
    }

    function getProfileImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path(ADMIN_PROFILE_IMAGE_PATH) . $image)) {
                $outputImage = asset(ADMIN_PROFILE_IMAGE_PATH . $image);
            }
        }

        return $outputImage;
    }
}
