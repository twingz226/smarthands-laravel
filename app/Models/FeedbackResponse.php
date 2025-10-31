<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_id', 'response_type', 'response_text',
        'responded_by', 'is_internal_note'
    ];

    protected $casts = [
        'is_internal_note' => 'boolean',
    ];

    public const TYPE_ACKNOWLEDGMENT = 'acknowledgment';
    public const TYPE_RESOLUTION = 'resolution';
    public const TYPE_FOLLOW_UP = 'follow_up';

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(CustomerFeedback::class, 'feedback_id');
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
} 