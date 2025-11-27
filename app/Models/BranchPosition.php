<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'positionable_type',
        'positionable_id',
        'job_position_id',
        'start_date',
        'end_date',
        'salary',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the positionable model (Admin or Staff).
     */
    public function positionable()
    {
        return $this->morphTo();
    }

    /**
     * Get the branch that owns the position.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the job position.
     */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }
}
