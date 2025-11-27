<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'department',
        'level',
        'status',
    ];

    /**
     * Get the owner that owns the job position.
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
