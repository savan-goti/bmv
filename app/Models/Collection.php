<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Status;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'start_date',
        'end_date',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }

    public function getImageAttribute($image)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($image && $image != null && $image != '') {
            if (file_exists(public_path('uploads/collections/') . $image)) {
                $outputImage = asset('uploads/collections/' . $image);
            }
        }

        return $outputImage;
    }

    public function isActive()
    {
        if (!$this->start_date || !$this->end_date) {
            return $this->status == Status::Active;
        }

        $now = now();
        return $this->status == Status::Active 
            && $now->greaterThanOrEqualTo($this->start_date) 
            && $now->lessThanOrEqualTo($this->end_date);
    }
}
