<?php

namespace App\Models;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingRescheduled;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookings';

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_RESCHEDULED = 'rescheduled';
    const STATUS_RESERVED = 'reserved';

    protected $fillable = [
        'customer_id',
        'service_id',
        'user_id',
        'cleaner_id',
        'cleaning_date',
        'duration',
        'price',
        'status',
        'special_instructions',
        'admin_notes',
        'cancellation_reason',
        'booking_token',
        'rescheduled_at',
        'reschedule_reason',
        'customer_reschedule_count',
        'rescheduled_by',
        'is_admin_reschedule',
        'customer_name',
        'customer_email',
        'customer_contact',
        'customer_address',
        'customer_confirmed'
    ];

    protected $casts = [
        'cleaning_date' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'deleted_at' => 'datetime',
        'price' => 'decimal:2',
        'is_admin_reschedule' => 'boolean',
        'customer_confirmed' => 'boolean'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        // Removed automatic event dispatch
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        // Dispatch BookingCreated event when a new booking is created
        static::created(function ($booking) {
            event(new BookingCreated($booking, $booking->customer, Auth::user()));
        });
        
        // Dispatch BookingCancelled event when booking is cancelled
        static::updated(function ($booking) {
            if ($booking->isDirty('status') && $booking->status === self::STATUS_CANCELLED) {
                event(new BookingCancelled(
                    $booking, 
                    $booking->customer, 
                    $booking->cancellation_reason ?? null,
                    Auth::user()
                ));
            }
            
            // Handle rescheduling
            if ($booking->isDirty('cleaning_date')) {
                $originalDate = $booking->getOriginal('cleaning_date');
                event(new BookingRescheduled(
                    $booking, 
                    $booking->customer, 
                    $originalDate, 
                    $booking->cleaning_date,
                    Auth::user()
                ));
            }
        });
    }

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Always format cleaning_date in application timezone when retrieved
     */
    public function getCleaningDateAttribute($value)
    {
        // Always parse from UTC and convert to application timezone
        return Carbon::parse($value, 'UTC')->timezone(config('app.timezone'));
    }

    /**
     * Always set cleaning_date in UTC before saving to database
     */
    public function setCleaningDateAttribute($value)
    {
        // If $value is already a Carbon instance, assume it's in the app timezone
        // and convert it to UTC for storage.
        $this->attributes['cleaning_date'] = Carbon::parse($value, config('app.timezone'))
            ->setTimezone('UTC')
            ->format($this->dateFormat);
    }

    /**
     * Format any datetime attribute in application timezone
     */
    public function getFormattedDate($attribute, $format = 'F j, Y g:i A')
    {
        return $this->{$attribute}->timezone(config('app.timezone'))->format($format);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function job(): HasOne
    {
        return $this->hasOne(Job::class, 'booking_id');
    }



    public function cleaner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cleaner_id');
    }

    public function rescheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public static function hasAvailableSlots($date)
    {
        $startOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->startOfDay();
        $endOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->endOfDay();
        
        $bookingsCount = self::where('cleaning_date', '>=', $startOfDay->setTimezone('UTC'))
            ->where('cleaning_date', '<=', $endOfDay->setTimezone('UTC'))
            ->whereNotIn('status', ['cancelled'])
            ->count();
            
        return $bookingsCount < 10;
    }

    public static function getAvailableSlots($date)
    {
        $slots = [
            '09:00' => true, // 9:00 AM
            '10:00' => true, // 10:00 AM
            '11:00' => true, // 11:00 AM
            '12:00' => true, // 12:00 PM
            '13:00' => true, // 1:00 PM
            '14:00' => true, // 2:00 PM
            '15:00' => true, // 3:00 PM
        ];

        $startOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->startOfDay();
        $endOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->endOfDay();

        $bookings = self::where('cleaning_date', '>=', $startOfDay->setTimezone('UTC'))
            ->where('cleaning_date', '<=', $endOfDay->setTimezone('UTC'))
            ->whereNotIn('status', ['cancelled'])
            ->get();

        foreach ($bookings as $booking) {
            $time = $booking->cleaning_date->format('H:i');
            if (isset($slots[$time])) {
                // Assuming 1 booking per slot makes it unavailable for simplicity
                // You might need more complex logic here based on your business rules
                $slots[$time] = false;
            }
        }

        return $slots;
    }

    /**
     * Get available time slots based on cleaner availability and booking status
     * Rules:
     * 1. If a time slot has bookings but no cleaners assigned → UNAVAILABLE (temporary booking)
     * 2. If a time slot has cleaners assigned and available cleaners >= 3 → AVAILABLE
     * 3. If a time slot has all cleaners assigned to confirmed bookings → UNAVAILABLE
     */
    public static function getAvailableSlotsWithCleanerCount($date)
    {
        $slots = [
            '09:00' => ['available' => true, 'available_cleaners' => 0], // 9:00 AM
            '10:00' => ['available' => true, 'available_cleaners' => 0], // 10:00 AM
            '11:00' => ['available' => true, 'available_cleaners' => 0], // 11:00 AM
            '12:00' => ['available' => true, 'available_cleaners' => 0], // 12:00 PM
            '13:00' => ['available' => true, 'available_cleaners' => 0], // 1:00 PM
            '14:00' => ['available' => true, 'available_cleaners' => 0], // 2:00 PM
            '15:00' => ['available' => true, 'available_cleaners' => 0], // 3:00 PM
        ];

        // Get total cleaners (all employees are considered active cleaners)
        $totalCleaners = Employee::count();
        
        // Get jobs and their assigned cleaners for each time slot
        foreach ($slots as $time => &$slotData) {
            $dateTime = Carbon::parse($date . ' ' . $time, config('app.timezone'));
            $startOfHour = $dateTime->copy()->startOfHour()->setTimezone('UTC');
            $endOfHour = $dateTime->copy()->endOfHour()->setTimezone('UTC');
            
            // Count assigned cleaners for this time slot (from actual job assignments)
            $assignedCleaners = Job::where('scheduled_date', '>=', $startOfHour)
                ->where('scheduled_date', '<=', $endOfHour)
                ->withCount('employees')
                ->get()
                ->sum('employees_count');
            
            // Count existing bookings for this time slot
            $bookingCount = self::where('cleaning_date', '>=', $startOfHour)
                ->where('cleaning_date', '<=', $endOfHour)
                ->whereNotIn('status', ['cancelled'])
                ->count();
            
            // Calculate available cleaners based on actual assignments
            $availableCleaners = $totalCleaners - $assignedCleaners;
            
            $slotData['available_cleaners'] = max(0, $availableCleaners);
            $slotData['assigned_cleaners'] = $assignedCleaners;
            $slotData['booking_count'] = $bookingCount;
            
            // Apply temporary booking logic
            $cleanersNeededPerBooking = 2; // Assume 2 cleaners per booking
            $totalCleanersNeeded = $bookingCount * $cleanersNeededPerBooking;
            $unassignedBookings = max(0, $bookingCount - ($assignedCleaners / $cleanersNeededPerBooking));
            
            if ($bookingCount > 0 && $assignedCleaners == 0) {
                // Rule 1: Has bookings but no cleaners assigned → UNAVAILABLE (temporary booking)
                $slotData['available'] = false;
                $slotData['reason'] = 'temporary_booking';
            } elseif ($totalCleanersNeeded > $assignedCleaners) {
                // Rule 1b: Need more cleaners than currently assigned → UNAVAILABLE (need more cleaners)
                $slotData['available'] = false;
                $slotData['reason'] = 'insufficient_assignments';
            } elseif ($assignedCleaners > 0 && $availableCleaners >= 3) {
                // Rule 2: Has cleaners assigned and sufficient available cleaners → AVAILABLE
                $slotData['available'] = true;
                $slotData['reason'] = 'cleaners_assigned';
            } elseif ($availableCleaners < 3) {
                // Rule 3: All cleaners assigned or insufficient cleaners → UNAVAILABLE
                $slotData['available'] = false;
                $slotData['reason'] = 'insufficient_cleaners';
            } else {
                // Default: No bookings, no assignments → AVAILABLE
                $slotData['available'] = true;
                $slotData['reason'] = 'available';
            }
        }

        return $slots;
    }

    /**
     * Get dates that are considered fully booked based on the given threshold.
     * Returns a collection of objects with booking_date and booking_count.
     *
     * A date is fully booked when the number of bookings on that date (excluding cancelled)
     * is greater than or equal to the threshold.
     */
    public static function getFullyBookedDatesWithCounts(int $fullyBookedThreshold = 10)
    {
        return self::selectRaw('DATE(cleaning_date) as booking_date, COUNT(*) as booking_count')
            ->whereNotIn('status', [self::STATUS_CANCELLED])
            ->groupBy('booking_date')
            ->having('booking_count', '>=', $fullyBookedThreshold)
            ->orderBy('booking_date', 'asc')
            ->get();
    }

    /**
     * Get booking count for a specific calendar date (in application timezone), excluding cancelled.
     */
    public static function getBookingCountForDate(string $date): int
    {
        $startOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->startOfDay();
        $endOfDay = Carbon::parse($date)->timezone(config('app.timezone'))->endOfDay();

        return self::where('cleaning_date', '>=', $startOfDay->setTimezone('UTC'))
            ->where('cleaning_date', '<=', $endOfDay->setTimezone('UTC'))
            ->whereNotIn('status', [self::STATUS_CANCELLED])
            ->count();
    }

    /**
     * Compute fully booked dates across a range using application timezone day buckets.
     * Returns a collection of objects: (booking_date => Y-m-d, booking_count => int)
     */
    public static function getFullyBookedDatesWithCountsByAppTimezone(Carbon $start, Carbon $end, int $fullyBookedThreshold = 10)
    {
        $results = collect();
        for ($date = $start->copy()->startOfDay(); $date->lte($end); $date->addDay()) {
            $dateString = $date->toDateString();
            $count = self::getBookingCountForDate($dateString);
            if ($count >= $fullyBookedThreshold) {
                $results->push((object) [
                    'booking_date' => $dateString,
                    'booking_count' => $count,
                ]);
            }
        }
        return $results;
    }

    public static function hasAvailableHourlySlot($dateTime, $serviceId)
    {
        // Implement your hourly slot availability logic here.
        $startOfHour = Carbon::parse($dateTime)->startOfHour()->setTimezone('UTC');
        $endOfHour = Carbon::parse($dateTime)->endOfHour()->setTimezone('UTC');
        $bookingsCount = self::where('cleaning_date', '>=', $startOfHour)
            ->where('cleaning_date', '<=', $endOfHour)

            ->whereNotIn('status', ['cancelled'])
            ->count();

        return $bookingsCount < 1; // Limit to 1 bookings per hour per service
    }

    /**
     * Check if customer has reached their reschedule limit for this booking
     * Default limit is 3 reschedules per booking
     */
    public function hasReachedRescheduleLimit(int $limit = 3): bool
    {
        return $this->customer_reschedule_count >= $limit;
    }

    /**
     * Get the remaining reschedule attempts for this booking
     */
    public function getRemainingReschedules(int $limit = 3): int
    {
        return max(0, $limit - $this->customer_reschedule_count);
    }

    /**
     * Increment customer reschedule count (only for customer-initiated reschedules)
     */
    public function incrementCustomerRescheduleCount(): void
    {
        $this->increment('customer_reschedule_count');
    }
    
}