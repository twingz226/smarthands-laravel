<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employee_no',
        'position',
        'phone',
        'address',
        'hire_date',
        'status',
        'profile_photo',
        'id_badge_photo',
        'uniform_photo',
        'photo_approved_at',
        'photo_expires_at',
        'photo_consent_given',
        'photo_consent_date',
        'photo_notes',
        'availability_status',
        'availability_notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'photo_approved_at' => 'datetime',
        'photo_consent_date' => 'datetime',
        'photo_consent_given' => 'boolean',
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
     * Get all availability records for the employee
     */
    public function availability(): HasMany
    {
        return $this->hasMany(EmployeeAvailability::class);
    }

    /**
     * Calculate the average rating for the employee
     */
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo ? Storage::url($this->profile_photo) : null;
    }

    /**
     * Get ID badge photo URL
     */
    public function getIdBadgePhotoUrlAttribute(): ?string
    {
        return $this->id_badge_photo ? Storage::url($this->id_badge_photo) : null;
    }

    /**
     * Get uniform photo URL
     */
    public function getUniformPhotoUrlAttribute(): ?string
    {
        return $this->uniform_photo ? Storage::url($this->uniform_photo) : null;
    }

    /**
     * Check if employee has any photos
     */
    public function hasPhotos(): bool
    {
        return !empty($this->profile_photo) || !empty($this->id_badge_photo) || !empty($this->uniform_photo);
    }

    /**
     * Check if photos are approved (approved and not expired)
     */
    public function hasApprovedPhotos(): bool
    {
        if (!$this->hasPhotos()) {
            return false;
        }
        if (is_null($this->photo_approved_at)) {
            return false;
        }
        if (!is_null($this->photo_expires_at) && $this->photo_expires_at->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Check if photos have expired
     */
    public function photosExpired(): bool
    {
        return $this->photo_expires_at !== null && $this->photo_expires_at->isPast();
    }

    /**
     * Get primary photo (profile photo or first available photo)
     */
    public function getPrimaryPhotoUrl(): ?string
    {
        if ($this->profile_photo) {
            return $this->profile_photo_url;
        }
        if ($this->id_badge_photo) {
            return $this->id_badge_photo_url;
        }
        if ($this->uniform_photo) {
            return $this->uniform_photo_url;
        }
        return null;
    }

    /**
     * Scope for employees with approved photos
     */
    public function scopeWithApprovedPhotos($query)
    {
        return $query->whereNotNull('photo_approved_at')
                    ->where(function($q) {
                        $q->whereNull('photo_expires_at')
                          ->orWhere('photo_expires_at', '>', now());
                    });
    }

    /**
     * Scope for employees needing photo updates
     */
    public function scopeNeedsPhotoUpdate($query)
    {
        return $query->where(function($q) {
            $q->whereNull('photo_approved_at')
              ->orWhere('photo_expires_at', '<', now());
        });
    }

    /**
     * Check if employee is available at a specific datetime
     */
    public function isAvailableAt($datetime)
    {
        return EmployeeAvailability::isAvailableAt($this, $datetime);
    }

    /**
     * Check if employee is currently available
     */
    public function isCurrentlyAvailable()
    {
        return $this->isAvailableAt(now());
    }

    /**
     * Mark employee as unavailable for a job
     */
    public function markUnavailableForJob(Job $job)
    {
        // Create availability record
        EmployeeAvailability::markUnavailableForJob($this, $job);
        
        // Update employee status
        $this->update(['availability_status' => 'unavailable']);
    }

    /**
     * Clear availability for a specific job (when job is cancelled/completed)
     */
    public function clearAvailabilityForJob(Job $job)
    {
        EmployeeAvailability::clearForJob($job);
        
        // Update employee status if no other active assignments
        $hasActiveAssignments = $this->availability()
                                    ->where('end_datetime', '>', now())
                                    ->exists();
        
        if (!$hasActiveAssignments) {
            $this->update(['availability_status' => 'available']);
        }
    }

    /**
     * Scope for available employees at a specific datetime
     */
    public function scopeAvailableAt($query, $datetime)
    {
        $unavailableEmployeeIds = EmployeeAvailability::activeAt($datetime)
                                                        ->pluck('employee_id')
                                                        ->toArray();
        
        return $query->whereNotIn('id', $unavailableEmployeeIds);
    }

    /**
     * Scope for currently available employees
     */
    public function scopeCurrentlyAvailable($query)
    {
        return $this->scopeAvailableAt($query, now());
    }
}