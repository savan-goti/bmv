<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;

    protected $table = 'product_views';

    public $timestamps = false; // Only created_at

    protected $fillable = [
        'product_id',
        'user_id',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'user_id');
    }

    public static function recordView($productId, $userId = null, $ipAddress = null)
    {
        return static::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'ip_address' => $ipAddress ?? request()->ip(),
            'created_at' => now(),
        ]);
    }

    public static function getUniqueViewsCount($productId, $days = 30)
    {
        return static::where('product_id', $productId)
            ->where('created_at', '>=', now()->subDays($days))
            ->distinct('ip_address')
            ->count('ip_address');
    }
}
