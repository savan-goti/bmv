<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    use HasFactory;

    protected $table = 'product_information';

    protected $fillable = [
        'product_id',
        'product_type',
        'sku',
        'barcode',
        'short_description',
        'long_description',
        'purchase_price',
        'original_price',
        'discount_type',
        'gst_rate',
        'tax_included',
        'commission_type',
        'commission_value',
        'stock_type',
        'low_stock_alert',
        'warehouse_location',
        'has_variation',
        'image_alt_text',
        'video_url',
        'weight',
        'shipping_class',
        'length',
        'width',
        'height',
        'free_shipping',
        'cod_available',
        'manufacturer_name',
        'manufacturer_brand',
        'manufacturer_part_number',
        'specifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'search_tags',
        'product_status',
        'is_featured',
        'is_returnable',
        'return_days',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
