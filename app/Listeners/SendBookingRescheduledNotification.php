<?php

namespace App\Listeners;

use App\Events\BookingRescheduled;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification as DBNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingRescheduledNotification
{
    /**
     * Handle the event.
     *
     * @param  BookingRescheduled  $event
     * @return void
     */
    public function handle(BookingRescheduled $event)
    {
        $booking = $event->booking;
        $customer = $event->customer;
        $oldDate = Carbon::parse($event->oldDate);
        $newDate = Carbon::parse($event->newDate);
        $actor = $event->actor; // may be null

        $admins = User::where('role', 'admin')->get();

        // Determine actor label
        if ($actor && method_exists($actor, 'isAdmin') && $actor->isAdmin()) {
            $performedBy = "Admin {$actor->name}";
        } elseif ($actor) {
            $performedBy = $actor->name;
        } else {
            $performedBy = $customer->name; // fallback
        }

        $message = "{$customer->name}'s {$booking->service->name} service booking has been rescheduled by {$performedBy} from " .
                  $oldDate->format('M d, Y h:i A') . " to " . $newDate->format('M d, Y h:i A');


        foreach ($admins as $admin) {
            // Guard against duplicate notifications within a short window
            $recentDuplicate = DBNotification::where('notifiable_id', $admin->id)
                ->where('notifiable_type', get_class($admin))
                ->where('type', \App\Models\Notification::TYPE_BOOKING_RESCHEDULED)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->where('data->booking_id', $booking->id)
                ->exists();

            if ($recentDuplicate) {
                continue;
            }
            $admin->notify(
                Notification::TYPE_BOOKING_RESCHEDULED,
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
