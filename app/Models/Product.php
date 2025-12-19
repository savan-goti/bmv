<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Status;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Basic Info
        'product_type',
        'product_name',
        'slug',
        'sku',
        'barcode',
        'short_description',
        'full_description',
        
        // Ownership & Audit
        'owner_id',
        'seller_id',
        'branch_id',
        'added_by_role',
        'added_by_user_id',
        'approved_by_admin_id',
        'approved_at',
        
        // Category & Brand
        'category_id',
        'sub_category_id',
        'child_category_id',
        'brand_id',
        'collection_id',
        
        // Pricing
        'purchase_price',
        'original_price',
        'sell_price',
        'discount_type',
        'discount_value',
        'gst_rate',
        'tax_included',
        'commission_type',
        'commission_value',
        
        // Inventory
        'stock_type',
        'total_stock',
        'reserved_stock',
        'available_stock',
        'low_stock_alert',
        'warehouse_location',
        
        // Variations
        'has_variation',
        
        // Media
        'thumbnail_image',
        'video_url',
        'image_alt_text',
        
        // Shipping
        'weight',
        'length',
        'width',
        'height',
        'shipping_class',
        'free_shipping',
        'cod_available',
        
        // Status & Workflow
        'product_status',
        'is_active',
        'is_featured',
        'is_returnable',
        'return_days',
        
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'search_tags',
        'schema_markup',
    ];

    protected $casts = [
        'is_active' => Status::class,
        'approved_at' => 'datetime',
        'tax_included' => 'boolean',
        'has_variation' => 'boolean',
        'free_shipping' => 'boolean',
        'cod_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_returnable' => 'boolean',
        'schema_markup' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\Owner::class);
    }

    public function seller()
    {
        return $this->belongsTo(\App\Models\Seller::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'approved_by_admin_id');
    }

    public function productInformation()
    {
        return $this->hasOne(ProductInformation::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function analytics()
    {
        return $this->hasOne(ProductAnalytics::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function productViews()
    {
        return $this->hasMany(ProductView::class);
    }

    public function addedBy()
    {
        return $this->morphTo();
    }

    // Helper methods
    public function isSimple()
    {
        return $this->product_type === 'simple';
    }

    public function isVariable()
    {
        return $this->product_type === 'variable';
    }

    public function isDigital()
    {
        return $this->product_type === 'digital';
    }

    public function isService()
    {
        return $this->product_type === 'service';
    }

    public function isApproved()
    {
        return $this->product_status === 'approved';
    }

    public function isPending()
    {
        return $this->product_status === 'pending';
    }

    public function isDraft()
    {
        return $this->product_status === 'draft';
    }

    public function isRejected()
    {
        return $this->product_status === 'rejected';
    }

    public function getFinalPrice()
    {
        if ($this->discount_type === 'percentage') {
            return $this->sell_price - ($this->sell_price * $this->discount_value / 100);
        }
        return $this->sell_price - $this->discount_value;
    }

    public function getDiscountAmount()
    {
        if ($this->discount_type === 'percentage') {
            return $this->sell_price * $this->discount_value / 100;
        }
        return $this->discount_value;
    }

    public function isLowStock()
    {
        return $this->available_stock <= $this->low_stock_alert;
    }

    public function isOutOfStock()
    {
        return $this->available_stock <= 0;
    }
}
