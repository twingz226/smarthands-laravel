<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'name',
        'description',
        'is_active',
        'is_completed',
        'category_id',
        'due_date',
        'frequency'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Get the job for this checklist
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Mark checklist item as complete
     */
    public function markComplete(): void
    {
        $this->update(['is_completed' => true]);
    }

    /**
     * Mark checklist item as incomplete
     */
    public function markIncomplete(): void
    {
        $this->update(['is_completed' => false]);
    }
}