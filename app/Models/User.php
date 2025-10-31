<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\ActivityLog;
use App\Models\CustomerFeedback;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasNotifications;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasNotifications {
        // Use our custom notify signature by default
        HasNotifications::notify insteadof Notifiable;
        // Keep access to Laravel's notify if needed
        Notifiable::notify as laravelNotify;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_photo',
        'points', // For loyalty/rewards system
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'is_locked',
        'lockout_time',
        'mfa_secret',
        'mfa_enabled',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'is_locked' => 'boolean',
        'lockout_time' => 'datetime',
        'mfa_enabled' => 'boolean',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar_url', 'full_name'];
    
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [];
    
    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }
    
    
    /**
     * Check if the user has unread notifications.
     *
     * @return bool
     */
    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->exists();
    }
    
    /**
     * Mark all notifications as read.
     *
     * @return int
     */
    public function markNotificationsAsRead()
    {
        return $this->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a customer.
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if the user account is locked.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        if (!$this->is_locked) {
            return false;
        }

        // If lockout time has passed, unlock the account
        if ($this->lockout_time && $this->lockout_time->isPast()) {
            $this->update([
                'is_locked' => false,
                'lockout_time' => null,
                'failed_login_attempts' => 0
            ]);
            return false;
        }

        return true;
    }
    
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/profile-photos/' . $this->profile_photo);
        }
        
        return asset('images/default-avatar.png');
    }
    
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Relationship with bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
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

    /**
     * Relationship with feedbacks
     */
    public function feedbacks()
    {
        return $this->hasMany(CustomerFeedback::class);
    }

    /**
     * Get the profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            // Check if the file exists in the storage directory
            $filePath = storage_path('app/public/profile-photos/' . $this->profile_photo);
            if (file_exists($filePath)) {
                return asset('storage/profile-photos/' . $this->profile_photo);
            }
            // If file doesn't exist in storage, try the public directory
            $publicPath = public_path('images/profile-photos/' . $this->profile_photo);
            if (file_exists($publicPath)) {
                return asset('images/profile-photos/' . $this->profile_photo);
            }
        }
        return asset('images/admin-logo.png'); // Default admin logo
    }
}