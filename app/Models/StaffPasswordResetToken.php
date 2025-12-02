<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPasswordResetToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staff_password_reset_tokens';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the staff associated with this reset token.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'email', 'email');
    }

    /**
     * Check if the token has expired (60 minutes).
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->addMinutes(60)->isPast();
    }
}
