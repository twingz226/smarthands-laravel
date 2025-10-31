<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_info';
    
    protected $fillable = [
        'email',
        'phone',
        'address',
        'service_area',
        'business_hours',
        'facebook_url',
        'instagram_url',
        'google_business_url',
        'about_content',
        'mission',
        'vision',
        'services_offered'
    ];
} 