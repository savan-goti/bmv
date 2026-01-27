<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'purchase_price',
        'original_price',
        'sell_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variant
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductVariantStock::class);
    }

    /**
     * Get the final price after discount
     */
    public function getFinalPrice()
    {
        // If product has discount, apply it
        if ($this->product && $this->product->discount_value > 0) {
            if ($this->product->discount_type === 'percentage') {
                return $this->sell_price - ($this->sell_price * $this->product->discount_value / 100);
            }
            return $this->sell_price - $this->product->discount_value;
        }
        
        return $this->sell_price;
    }

    /**
     * Get the discount amount
     */
    public function getDiscountAmount()
    {
        if ($this->product && $this->product->discount_value > 0) {
            if ($this->product->discount_type === 'percentage') {
                return $this->sell_price * $this->product->discount_value / 100;
            }
            return $this->product->discount_value;
        }
        
        return 0;
    }

    /**
     * Get profit margin
     */
    public function getProfitMargin()
    {
        if ($this->purchase_price && $this->sell_price > 0) {
            return (($this->sell_price - $this->purchase_price) / $this->sell_price) * 100;
        }
        
        return 0;
    }

    /**
     * Get profit amount
     */
    public function getProfitAmount()
    {
        if ($this->purchase_price) {
            return $this->sell_price - $this->purchase_price;
        }
        
        return 0;
    }

    /**
     * Check if variant is active
     */
    public function isActive()
    {
        return $this->is_active && $this->product && $this->product->is_active;
    }

    /**
     * Scope to get only active variants
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
