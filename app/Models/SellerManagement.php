<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SellerManagement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seller_management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'created_by_type',
        'created_by_id',
        'action',
        'notes',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the seller that this management record belongs to.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the user who created/managed this seller (polymorphic).
     */
    public function createdBy(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'created_by_type', 'created_by_id');
    }

    /**
     * Scope to filter by action type.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by seller.
     */
    public function scopeBySeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }
}
