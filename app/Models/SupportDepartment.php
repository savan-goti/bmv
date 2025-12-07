<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Get the queues for this department
     */
    public function queues()
    {
        return $this->hasMany(SupportQueue::class, 'department_id');
    }

    /**
     * Scope to filter active departments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
