<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'short_description',
        'long_description',
        'manufacturer_name',
        'manufacturer_brand',
        'manufacturer_part_number',
        'specifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
