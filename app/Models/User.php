<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ActivityLog;
use App\Models\Payment;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'points', // For loyalty/rewards system
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * All users are admins in this system
     */
    public function isAdmin(): bool
    {
        return true; // As per your original implementation
    }

    /**
     * Relationship with bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get upcoming bookings (future dates)
     */
    public function upcomingBookings()
    {
        return $this->bookings()
            ->where('cleaning_date', '>=', now())
            ->orderBy('cleaning_date');
    }

    /**
     * Get past bookings (historical records)
     */
    public function pastBookings()
    {
        return $this->bookings()
            ->where('cleaning_date', '<', now())
            ->orderBy('cleaning_date', 'desc');
    }

    /**
     * Relationship with payments
     */
    
    public function nextBooking()
    {
        return $this->upcomingBookings()->first();
    }

    /**
     * Relationship with activity logs
     */
    public function activities()
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

   
    public function hasUpcomingBookings(): bool
    {
        return $this->upcomingBookings()->exists();
    }
}