<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    /**
     * Get available time slots for a given date
     */
    public function getAvailableSlots(Request $request)
    {
        $date = Carbon::parse($request->date)->startOfDay();
        
        // Define business hours (9 AM to 6 PM)
        $startHour = 9;
        $endHour = 18;
        
        // Duration of each slot in hours
        $slotDuration = 2;
        
        // Get existing bookings for the date
        $existingBookings = Booking::whereDate('cleaning_date', $date)
            ->pluck('cleaning_date')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();
        
        $availableSlots = [];
        
        // Generate time slots
        for ($hour = $startHour; $hour < $endHour; $hour += $slotDuration) {
            $slotTime = sprintf('%02d:00', $hour);
            
            // Skip if slot is already booked
            if (!in_array($slotTime, $existingBookings)) {
                $displayTime = Carbon::createFromTime($hour)->format('h:i A');
                $endTime = Carbon::createFromTime($hour + $slotDuration)->format('h:i A');
                
                $availableSlots[] = [
                    'time' => $displayTime,
                    'display' => "$displayTime - $endTime",
                ];
            }
        }
        
        return response()->json($availableSlots);
    }
} 