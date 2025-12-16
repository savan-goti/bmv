<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Status;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'website',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoAttribute($logo)
    {
        $outputImage = asset('assets/img/no_img.jpg');

        if ($logo && $logo != null && $logo != '') {
            if (file_exists(public_path('uploads/brands/') . $logo)) {
                $outputImage = asset('uploads/brands/' . $logo);
            }
        }

        return $outputImage;
    }
}
