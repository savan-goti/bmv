<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HsnSac extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hsn_sacs';

    protected $fillable = ['code', 'description', 'type', 'status'];

    protected $casts = [
        'status' => \App\Enums\Status::class,
    ];
}
