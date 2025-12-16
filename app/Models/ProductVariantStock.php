<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantStock extends Model
{
    use HasFactory;

    protected $table = 'product_variant_stock';

    protected $fillable = [
        'product_variant_id',
        'total_stock',
        'reserved_stock',
        'available_stock',
        'low_stock_alert',
        'warehouse_location',
    ];

    protected $casts = [
        'total_stock' => 'integer',
        'reserved_stock' => 'integer',
        'available_stock' => 'integer',
        'low_stock_alert' => 'integer',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function isLowStock()
    {
        return $this->available_stock <= $this->low_stock_alert;
    }

    public function isOutOfStock()
    {
        return $this->available_stock <= 0;
    }

    public function updateAvailableStock()
    {
        $this->available_stock = $this->total_stock - $this->reserved_stock;
        $this->save();
    }
}
