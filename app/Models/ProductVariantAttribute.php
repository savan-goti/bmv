<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_variant_attributes';

    protected $fillable = [
        'product_variant_id',
        'attribute_name',
        'attribute_value',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
