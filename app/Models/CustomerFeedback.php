<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'customer_id',
        'employee_id',
        'overall_rating',
        'cleanliness_rating',
        'professionalism_rating',
        'punctuality_rating',
        'communication_rating',
        'value_rating',
        'comments',
        'is_anonymous',
        'feedback_type',
        'status'
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'cleanliness_rating' => 'integer',
        'professionalism_rating' => 'integer',
        'punctuality_rating' => 'integer',
        'communication_rating' => 'integer',
        'value_rating' => 'integer',
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Feedback types
    public const TYPE_IMMEDIATE = 'immediate';
    public const TYPE_POST_SERVICE = 'post_service';
    public const TYPE_FOLLOW_UP = 'follow_up';

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_RESPONDED = 'responded';
    public const STATUS_RESOLVED = 'resolved';

    /**
     * Get the job for this feedback
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the customer who gave the feedback
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the employee who received the feedback
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get all responses to this feedback
     */
    public function responses(): HasMany
    {
        return $this->hasMany(FeedbackResponse::class, 'feedback_id');
    }

    /**
     * Get the user who responded to this feedback
     */
    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Calculate average rating from all rating fields
     */
    public function getAverageRating(): float
    {
        $ratings = array_filter([
            $this->overall_rating,
            $this->cleanliness_rating,
            $this->professionalism_rating,
            $this->punctuality_rating,
            $this->communication_rating,
            $this->value_rating
        ]);

        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }

    /**
     * Get stars for display
     */
    public function getStars(int $rating): string
    {
        return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    }

    /**
     * Check if feedback is positive (4+ stars)
     */
    public function isPositive(): bool
    {
        return $this->overall_rating >= 4;
    }

    /**
     * Check if feedback is negative (2 or fewer stars)
     */
    public function isNegative(): bool
    {
        return $this->overall_rating <= 2;
    }

    /**
     * Check if feedback needs attention (3 or fewer stars)
     */
    public function needsAttention(): bool
    {
        return $this->overall_rating <= 3;
    }

    /**
     * Get customer display name (anonymous or actual name)
     */
    public function getCustomerDisplayName(): string
    {
        return $this->is_anonymous ? 'Anonymous Customer' : $this->customer->name;
    }

    /**
     * Scope for pending feedback
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for reviewed feedback
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', self::STATUS_REVIEWED);
    }

    /**
     * Scope for responded feedback
     */
    public function scopeResponded($query)
    {
        return $query->where('status', self::STATUS_RESPONDED);
    }

    /**
     * Scope for resolved feedback
     */
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    /**
     * Scope for negative feedback
     */
    public function scopeNegative($query)
    {
        return $query->where('overall_rating', '<=', 2);
    }

    /**
     * Scope for positive feedback
     */
    public function scopePositive($query)
    {
        return $query->where('overall_rating', '>=', 4);
    }

    /**
     * Scope for recent feedback
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
} 