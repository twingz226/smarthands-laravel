<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification as DBNotification;

class SendBookingCancelledNotification
{
    /**
     * Handle the event.
     *
     * @param  BookingCancelled  $event
     * @return void
     */
    public function handle(BookingCancelled $event)
    {
        $booking = $event->booking;
        $customer = $event->customer;
        $reason = $event->reason;
        $actor = $event->actor; // may be null
        
        $admins = User::where('role', 'admin')->get();
        
        // Determine actor label
        if ($actor && $actor->isAdmin()) {
            $performedBy = "Admin {$actor->name}";
        } elseif ($actor) {
            $performedBy = $actor->name;
        } else {
            $performedBy = $customer->name; // fallback
        }

        $message = "{$customer->name}'s {$booking->service->name} service booking has been cancelled by {$performedBy}";
        if ($reason) {
            $message .= ". Reason: {$reason}";
        }

        foreach ($admins as $admin) {
            // Guard against duplicate notifications within a short window
            $recentDuplicate = DBNotification::where('notifiable_id', $admin->id)
                ->where('notifiable_type', get_class($admin))
                ->where('type', Notification::TYPE_BOOKING_CANCELLED)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->where('data->booking_id', $booking->id)
                ->exists();

            if ($recentDuplicate) {
                continue;
            }

            $admin->createNotification(
                Notification::TYPE_BOOKING_CANCELLED,
                $message,
                route('bookings.show', $booking->id),
                $booking->id,
                $customer->id,
                [
                    'actor_id' => $actor?->id,
                    'actor_name' => $actor?->name,
                    'actor_role' => $actor?->role,
                ]
            );
        }
    }
}
