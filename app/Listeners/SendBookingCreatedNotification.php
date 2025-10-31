<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Notifications\DatabaseNotification as DBNotification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SendBookingCreatedNotification
{
    /**
     * Handle the event.
     *
     * @param  BookingCreated  $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;
        $customer = $event->customer;
        $actor = $event->actor; // may be null
        $admins = User::where('role', 'admin')->get();

        // Determine actor label
        if ($actor && method_exists($actor, 'isAdmin') && $actor->isAdmin()) {
            $performedBy = "Admin {$actor->name}";
        } elseif ($actor) {
            $performedBy = $actor->name;
        } else {
            // Fallback: use booking customer's name if present
            $performedBy = $customer?->name ?? 'customer';
        }

        $message = "New booking from {$customer->name} for {$booking->service->name} service created by {$performedBy} on " .
                  $booking->cleaning_date->format('M d, Y') . " at " .
                  $booking->cleaning_date->format('h:i A');

        // Use the correct admin route for showing a booking
        $link = url('/admin/bookings/' . $booking->id);

        // Send individually to each admin and guard against duplicates within a short window
        foreach ($admins as $admin) {
            $recentDuplicate = DBNotification::where('notifiable_id', $admin->id)
                ->where('notifiable_type', get_class($admin))
                ->where('type', NewBookingNotification::class)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->where('data->booking_id', $booking->id)
                ->exists();

            if ($recentDuplicate) {
                continue; // skip duplicate
            }

            // Send immediately so notifications are persisted even if no queue worker is running
            NotificationFacade::sendNow($admin, new NewBookingNotification([
                'message' => $message,
                'link' => $link,
                'booking_id' => $booking->id,
                'customer_id' => $customer?->id,
                'actor_id' => $actor?->id,
                'actor_name' => $actor?->name,
                'actor_role' => $actor?->role,
            ]));
        }
    }
}
