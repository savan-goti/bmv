<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sessions';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'guard',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'last_activity' => 'integer',
    ];

    /**
     * Scope a query to only include sessions for a specific guard.
     */
    public function scopeForGuard($query, string $guard)
    {
        return $query->where('guard', $guard);
    }

    /**
     * Scope a query to only include sessions for a specific user.
     */
    public function scopeForUser($query, int $userId, string $guard)
    {
        return $query->where('user_id', $userId)
                     ->where('guard', $guard);
    }

    /**
     * Check if this is the current session.
     */
    public function isCurrentSession()
    {
        return $this->id === session()->getId();
    }

    /**
     * Set the guard for this session.
     */
    public static function setGuard(string $sessionId, string $guard)
    {
        return static::where('id', $sessionId)->update(['guard' => $guard]);
    }

    /**
     * Delete all sessions for a user except the current one.
     */
    public static function logoutOtherSessions(int $userId, string $guard, string $currentSessionId)
    {
        return static::where('user_id', $userId)
                     ->where('guard', $guard)
                     ->where('id', '!=', $currentSessionId)
                     ->delete();
    }

    /**
     * Delete a specific session for a user.
     */
    public static function logoutSession(int $userId, string $guard, string $sessionId)
    {
        return static::where('id', $sessionId)
                     ->where('user_id', $userId)
                     ->where('guard', $guard)
                     ->delete();
    }
}
