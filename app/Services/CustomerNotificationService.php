<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Job;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\CustomerBookingNotification;
use Illuminate\Notifications\DatabaseNotification as DBNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Centralized service for sending in-app notifications to customers
 * when admin actions affect their bookings/jobs.
 */
class CustomerNotificationService
{
    /**
     * Send a notification to the customer (User) who owns the booking.
     *
     * @param Booking $booking
     * @param string  $type     One of the Notification::TYPE_* constants
     * @param string  $message  Human-readable notification message
     * @param array   $extra    Additional data to store in the notification
     * @return void
     */
    public function notifyCustomer(Booking $booking, string $type, string $message, array $extra = []): void
    {
        $user = $booking->user;

        if (!$user) {
            Log::warning('CustomerNotificationService: No user found for booking', [
                'booking_id' => $booking->id,
                'type' => $type,
            ]);
            return;
        }

        // Only notify customer-role users (not admins looking at their own test bookings)
        if ($user->isAdmin()) {
            return;
        }

        // Guard against duplicate notifications within a short window
        $recentDuplicate = DBNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->where('type', CustomerBookingNotification::class)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->whereJsonContains('data->booking_id', $booking->id)
            ->whereJsonContains('data->notification_type', $type)
            ->exists();

        if ($recentDuplicate) {
            Log::debug('CustomerNotificationService: Duplicate notification skipped', [
                'booking_id' => $booking->id,
                'type' => $type,
                'user_id' => $user->id,
            ]);
            return;
        }

        $notificationData = array_merge([
            'notification_type' => $type,
            'message' => $message,
            'booking_id' => $booking->id,
            'service_name' => $booking->service->name ?? 'Cleaning Service',
            'cleaning_date' => $booking->cleaning_date
                ? $booking->cleaning_date->timezone(config('app.timezone'))->format('M d, Y h:i A')
                : null,
        ], $extra);

        try {
            NotificationFacade::sendNow($user, new CustomerBookingNotification($notificationData));

            Log::info('Customer notification sent', [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customer notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'type' => $type,
            ]);
        }
    }

    /**
     * Booking confirmed by admin.
     */
    public function bookingConfirmed(Booking $booking): void
    {
        $booking->loadMissing('service');
        $serviceName = $booking->service->name ?? 'Cleaning Service';
        $date = $booking->cleaning_date
            ? $booking->cleaning_date->timezone(config('app.timezone'))->format('M d, Y \\a\\t h:i A')
            : 'your scheduled date';

        $this->notifyCustomer(
            $booking,
            Notification::TYPE_CUSTOMER_BOOKING_CONFIRMED,
            "Your booking for {$serviceName} on {$date} has been confirmed."
        );
    }

    /**
     * Booking cancelled by admin.
     */
    public function bookingCancelled(Booking $booking, ?string $reason = null): void
    {
        $booking->loadMissing('service');
        $serviceName = $booking->service->name ?? 'Cleaning Service';
        $date = $booking->cleaning_date
            ? $booking->cleaning_date->timezone(config('app.timezone'))->format('M d, Y')
            : 'your scheduled date';

        $message = "Your booking for {$serviceName} on {$date} has been cancelled.";
        if ($reason) {
            $message .= " Reason: {$reason}";
        }

        $this->notifyCustomer(
            $booking,
            Notification::TYPE_CUSTOMER_BOOKING_CANCELLED,
            $message,
            ['cancellation_reason' => $reason]
        );
    }

    /**
     * Booking rescheduled by admin.
     */
    public function bookingRescheduled(Booking $booking, $oldDate, $newDate): void
    {
        $booking->loadMissing('service');
        $serviceName = $booking->service->name ?? 'Cleaning Service';
        $formattedNew = $booking->cleaning_date
            ? $booking->cleaning_date->timezone(config('app.timezone'))->format('M d, Y \\a\\t h:i A')
            : 'a new date';

        $this->notifyCustomer(
            $booking,
            Notification::TYPE_CUSTOMER_BOOKING_RESCHEDULED,
            "Your booking for {$serviceName} has been rescheduled to {$formattedNew}."
        );
    }

    /**
     * Cleaners assigned to job (linked to booking).
     */
    public function cleanersAssigned(Job $job): void
    {
        $job->loadMissing(['booking', 'service', 'employees']);

        if (!$job->booking) {
            return;
        }

        $serviceName = $job->service->name ?? 'Cleaning Service';
        $date = $job->scheduled_date
            ? $job->scheduled_date->timezone(config('app.timezone'))->format('M d, Y \\a\\t h:i A')
            : 'your scheduled date';

        $cleanerNames = $job->employees->pluck('name')->join(', ');

        $this->notifyCustomer(
            $job->booking,
            Notification::TYPE_CUSTOMER_CLEANERS_ASSIGNED,
            "Cleaners have been assigned to your {$serviceName} booking on {$date}.",
            [
                'job_id' => $job->id,
                'cleaner_names' => $cleanerNames,
            ]
        );
    }

    /**
     * Job started (in progress).
     */
    public function jobStarted(Job $job): void
    {
        $job->loadMissing(['booking', 'service']);

        if (!$job->booking) {
            return;
        }

        $serviceName = $job->service->name ?? 'Cleaning Service';

        $this->notifyCustomer(
            $job->booking,
            Notification::TYPE_CUSTOMER_JOB_STARTED,
            "Your {$serviceName} cleaning service has started! Our team is now working on your property.",
            ['job_id' => $job->id]
        );
    }

    /**
     * Job completed.
     */
    public function jobCompleted(Job $job): void
    {
        $job->loadMissing(['booking', 'service']);

        if (!$job->booking) {
            return;
        }

        $serviceName = $job->service->name ?? 'Cleaning Service';

        $this->notifyCustomer(
            $job->booking,
            Notification::TYPE_CUSTOMER_JOB_COMPLETED,
            "Your {$serviceName} cleaning is complete! We hope you're satisfied with the results. Please leave a rating.",
            ['job_id' => $job->id]
        );
    }

    /**
     * Price set for booking by admin.
     */
    public function priceSet(Booking $booking): void
    {
        $booking->loadMissing('service');
        $serviceName = $booking->service->name ?? 'Cleaning Service';
        $price = number_format((float) ($booking->price ?? 0), 2);

        $this->notifyCustomer(
            $booking,
            Notification::TYPE_CUSTOMER_PRICE_SET,
            "The price for your {$serviceName} booking has been set to ₱{$price}.",
            ['price' => $booking->price]
        );
    }
}
