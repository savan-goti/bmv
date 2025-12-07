<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_team_member_id',
        'performed_by_id',
        'performed_by_type',
        'action',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    /**
     * Get the support team member this log belongs to
     */
    public function supportTeamMember()
    {
        return $this->belongsTo(SupportTeamMember::class, 'support_team_member_id');
    }

    /**
     * Get the user who performed the action (polymorphic)
     */
    public function performedBy()
    {
        return $this->morphTo(__FUNCTION__, 'performed_by_type', 'performed_by_id');
    }

    /**
     * Create an audit log entry
     */
    public static function log(
        ?int $supportTeamMemberId,
        string $action,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?object $performedBy = null
    ): self {
        return self::create([
            'support_team_member_id' => $supportTeamMemberId,
            'performed_by_id' => $performedBy?->id,
            'performed_by_type' => $performedBy ? get_class($performedBy) : null,
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
