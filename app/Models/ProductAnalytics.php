<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'view_count',
        'order_count',
        'rating_avg',
        'review_count',
        'last_calculated_at',
    ];

    protected $casts = [
        'view_count' => 'integer',
        'order_count' => 'integer',
        'rating_avg' => 'decimal:2',
        'review_count' => 'integer',
        'last_calculated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementOrderCount()
    {
        $this->increment('order_count');
    }

    public function updateRatingAverage()
    {
        $reviews = $this->product->productReviews()->where('is_approved', true)->get();
        
        if ($reviews->count() > 0) {
            $this->rating_avg = $reviews->avg('rating');
            $this->review_count = $reviews->count();
            $this->last_calculated_at = now();
            $this->save();
        }
    }
}
