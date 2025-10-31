<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerActivity extends Model
{
    use HasFactory;

    protected $table = 'customer_activities';

    protected $fillable = [
        'customer_id',
        'type',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public const TYPE_BOOKING = 'booking';
    public const TYPE_JOB = 'job';
    public const TYPE_FEEDBACK = 'feedback';
    public const TYPE_PROFILE = 'profile';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
} 