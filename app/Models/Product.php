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
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'image',
        'price',
        'discount',
        'quantity',
        'status',
        'published_at',
        'added_by_id',
        'added_by_type',
    ];

    protected $casts = [
        'status' => Status::class,
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function productInformation()
    {
        return $this->hasOne(ProductInformation::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function addedBy()
    {
        return $this->morphTo();
    }
}
