<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Enums\SupportRole;

class SupportTeamMember extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'support_team_members';

    protected $fillable = [
        'created_by_id',
        'created_by_type',
        'profile_image',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'departments',
        'default_queues',
        'status',
        'notification_method',
        'tickets_assigned',
        'open_tickets',
        'avg_response_time',
        'last_login_at',
        'last_login_ip',
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
            'departments' => 'array',
            'default_queues' => 'array',
            'role' => SupportRole::class,
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'tickets_assigned' => 'integer',
            'open_tickets' => 'integer',
            'avg_response_time' => 'decimal:2',
        ];
    }

    /**
     * Get the creator of this support team member (polymorphic)
     */
    public function creator()
    {
        return $this->morphTo(__FUNCTION__, 'created_by_type', 'created_by_id');
    }

    /**
     * Get the departments this member belongs to
     */
    public function departmentRecords()
    {
        if (!$this->departments) {
            return collect();
        }
        
        return SupportDepartment::whereIn('id', $this->departments)->get();
    }

    /**
     * Get the default queues for this member
     */
    public function queueRecords()
    {
        if (!$this->default_queues) {
            return collect();
        }
        
        return SupportQueue::whereIn('id', $this->default_queues)->get();
    }

    /**
     * Get audit logs for this support team member
     */
    public function auditLogs()
    {
        return $this->hasMany(SupportAuditLog::class, 'support_team_member_id');
    }

    /**
     * Check if member is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if member is disabled
     */
    public function isDisabled(): bool
    {
        return $this->status === 'disabled';
    }

    /**
     * Get profile image attribute with fallback
     */
    public function getProfileImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path('uploads/support_team/') . $image)) {
                $outputImage = asset('uploads/support_team/' . $image);
            }
        }

        return $outputImage;
    }

    /**
     * Scope to filter by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to filter by status
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by status
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', 'disabled');
    }
}
