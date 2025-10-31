<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\ContactMessage;  // Add this import

class TrashController extends Controller
{
    public function index()
    {
        // Get soft-deleted bookings
        $deletedBookings = Booking::onlyTrashed()
            ->with(['customer', 'service'])
            ->orderByDesc('deleted_at')
            ->get();

        // Get soft-deleted contact messages
        $deletedMessages = ContactMessage::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->get();

        // Get recent deletion activities
        $recentDeletedActivities = ActivityLog::query()
            ->where(function ($query) {
                $query->where('description', 'like', '%deleted%')
                    ->orWhere('description', 'like', '%removed%')
                    ->orWhere('description', 'like', '%trashed%');
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.trash.index', [
            'deletedBookings' => $deletedBookings,
            'deletedMessages' => $deletedMessages,  // Add this line
            'recentDeletedActivities' => $recentDeletedActivities,
        ]);
    }
}