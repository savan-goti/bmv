<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'created_by_role',
        'updated_by',
        'updated_by_role',
        'name',
        'code',
        'type',
        'username',
        'branch_link',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'manager_name',
        'manager_phone',
        'social_media',
        'opening_date',
        'status',
    ];

    protected $casts = [
        'social_media' => 'array',
        'opening_date' => 'date',
    ];



    /**
     * Get the positions for the branch.
     */
    public function positions()
    {
        return $this->hasMany(BranchPosition::class);
    }
}
