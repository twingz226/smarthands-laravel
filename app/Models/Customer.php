<?php

namespace App\Models;

use App\Events\NewCustomerRegistered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact',
        'address',
        'registered_date',
        'customer_id',
        'is_archived',
        'archived_at',
        'archive_reason'
    ];

    protected $casts = [
        'registered_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => NewCustomerRegistered::class,
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        // Set default values
        static::creating(function ($customer) {
            if (empty($customer->registered_date)) {
                $customer->registered_date = now();
            }
        });
    }

    /**
     * Get all jobs for the customer
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get all bookings for the customer
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all ratings given by the customer
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get all activities for the customer
     */
    public function activities(): HasMany
    {
        return $this->hasMany(CustomerActivity::class);
    }

    // Scope for active (non-archived) customers
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    // Scope for archived customers
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}