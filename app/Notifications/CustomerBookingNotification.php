<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Database notification sent to customers when admin actions
 * affect their bookings (confirm, cancel, reschedule, assign cleaners, etc.)
 */
class CustomerBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notification_type' => $this->data['notification_type'] ?? 'general',
            'message' => $this->data['message'] ?? 'You have a new notification.',
            'booking_id' => $this->data['booking_id'] ?? null,
            'job_id' => $this->data['job_id'] ?? null,
            'service_name' => $this->data['service_name'] ?? null,
            'cleaning_date' => $this->data['cleaning_date'] ?? null,
            'price' => $this->data['price'] ?? null,
            'cleaner_names' => $this->data['cleaner_names'] ?? null,
            'cancellation_reason' => $this->data['cancellation_reason'] ?? null,
        ];
    }
}
