<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'hire_date',
        'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all jobs assigned to the employee
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_employee')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    /**
     * Get all ratings received by the employee
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Calculate the average rating for the employee
     */
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}