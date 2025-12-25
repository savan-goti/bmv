<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Status;
use App\Enums\CategoryType;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_type',
        'name',
        'slug',
        'image',
        'status',
    ];

    protected $casts = [
        'category_type' => CategoryType::class,
        'status' => Status::class,
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class);
    }
}
