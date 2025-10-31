<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobCompleted;
use Carbon\Carbon;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'scheduled_date',
        'status',
        'address',
        'special_instructions',
        'started_at',
        'completed_at',
        'assigned_at',
        'reassigned_at',
        'rating_token',
        'booking_id'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'assigned_at' => 'datetime',
        'reassigned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'completed_at',
        'created_at',
        'updated_at',
        'scheduled_date'
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the customer for this job
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service for this job
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the employees assigned to this job
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'job_employee')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    /**
     * Get all checklists for this job
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    /**
     * Get the ratings for this job
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Always format scheduled_date in application timezone when retrieved
     */
    public function getScheduledDateAttribute($value)
    {
        // Always parse from UTC and convert to application timezone
        return Carbon::parse($value, 'UTC')->timezone(config('app.timezone'));
    }

    /**
     * Always set scheduled_date in UTC before saving to database
     */
    public function setScheduledDateAttribute($value)
    {
        // If $value is already a Carbon instance, assume it's in the app timezone
        // and convert it to UTC for storage.
        $this->attributes['scheduled_date'] = Carbon::parse($value, config('app.timezone'))
            ->setTimezone('UTC')
            ->format('Y-m-d H:i:s');
    }

    /**
     * Format scheduled_date in application timezone
     */
    public function getFormattedScheduledDate($format = 'M d, Y h:i A')
    {
        return $this->scheduled_date->timezone(config('app.timezone'))->format($format);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($job) {
            // Only proceed if status is being changed
            if ($job->isDirty('status')) {
                // If job is cancelled, update the related booking status to cancelled
                if ($job->status === self::STATUS_CANCELLED && $job->booking) {
                    $job->booking->update(['status' => \App\Models\Booking::STATUS_CANCELLED]);
                }
                // If job is completed, update the related booking status to completed
                elseif ($job->status === self::STATUS_COMPLETED && $job->booking) {
                    $job->booking->update(['status' => \App\Models\Booking::STATUS_COMPLETED]);
                }
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the rating for this job (for backward compatibility)
     */
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Check if job is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if job is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if job is assigned
     */
    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    /**
     * Check if job is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if job is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Generate a unique rating token
     */
    public static function generateRatingToken(): string
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * Mark job as completed and send rating email
     */
    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->rating_token = self::generateRatingToken();
        $this->save();

        // Send completion email with rating link (queued for better performance)
        Mail::to($this->customer->email)->queue(new JobCompleted($this));
    }
}