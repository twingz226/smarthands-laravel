<?php

namespace App\Traits;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

/**
 * Trait HasNotifications
 * @package App\Traits
 * Uses Laravel's built-in database notifications (notifications table)
 */
trait HasNotifications
{
    /**
     * Create a new notification for the user.
     */
    public function notify($type, $message, $link = null, $bookingId = null, $customerId = null, array $extraData = [])
    {
        // Use Laravel's database notifications structure
        return $this->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => $type,
            'data' => array_merge([
                'message' => $message,
                'link' => $link,
                'booking_id' => $bookingId,
                'customer_id' => $customerId,
            ], $extraData),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead()
    {
        // Use Notifiable's unreadNotifications() relationship
        $this->unreadNotifications()->update(['read_at' => now()]);
        return $this;
    }
}
