<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Booking;
use App\Models\Customer;

class Notification extends BaseNotification
{
    // Notification types - kept for backward compatibility
    const TYPE_BOOKING_CREATED = 'booking_created';
    const TYPE_BOOKING_CANCELLED = 'booking_cancelled';
    const TYPE_BOOKING_RESCHEDULED = 'booking_rescheduled';
    const TYPE_NEW_CUSTOMER = 'new_customer';
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Get the booking associated with the notification.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'data->booking_id');
    }

    /**
     * Get the customer associated with the notification.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'data->customer_id');
    }
    
    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    
    /**
     * Mark the notification as read.
     *
     * @return bool
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if the notification is unread.
     *
     * @return bool
     */
    public function unread()
    {
        return ! $this->isRead();
    }
    
    /**
     * Mark the notification as unread.
     *
     * @return bool
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Get the notification's type in a human-readable format.
     *
     * @return string
     */
    public function getTypeTextAttribute()
    {
        $types = [
            self::TYPE_BOOKING_CREATED => 'New Booking',
            self::TYPE_BOOKING_CANCELLED => 'Booking Cancelled',
            self::TYPE_BOOKING_RESCHEDULED => 'Booking Rescheduled',
            self::TYPE_NEW_CUSTOMER => 'New Customer',
        ];
        
        return $types[$this->type] ?? ucwords(str_replace('_', ' ', $this->type));
    }

    /**
     * Scope a query to only include recent notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
    
    /**
     * Determine if the notification has been read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }
}
