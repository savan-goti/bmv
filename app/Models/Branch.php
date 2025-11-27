<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'manager_name',
        'manager_phone',
        'opening_date',
        'status',
    ];

    /**
     * Get the owner that owns the branch.
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Get the positions for the branch.
     */
    public function positions()
    {
        return $this->hasMany(BranchPosition::class);
    }
}
