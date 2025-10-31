<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if ContactInfo already exists
        if (ContactInfo::exists()) {
            return;
        }

        ContactInfo::create([
            'email' => 'smarthandsbcd@gmail.com',
            'phone' => '0953 957 4130',
            'address' => 'Site 3 Blk 3 Lot 33, Brgy. 13 Villa Victorias, Victorias City, Negros Occidental, Philippines',
            'service_area' => 'Bacolod City, Bago City, Binalbagan, Cadiz City, E.B. Magalona, Escalante City, Hinigaran, Manapla, Pontevedra, Pulupandan, Sagay City, San Enrique, Silay City, Talisay City, Valladolid, and Victorias City, Philippines',
            'business_hours' => 'Always open',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=100088701112041',
            'instagram_url' => 'https://www.instagram.com/smarthandscleaning',
            'google_business_url' => 'https://share.google/qYTYBgvGwwKCF9YIY',
            'about_content' => 'Welcome to Smarthands Cleaning Services, your trusted partner in professional cleaning solutions.',
            'mission' => 'At SMARTHANDS, our mission is to bring world-class cleaning standards and on-call service systems inspired by our experience in Middle East  not just in Bacolod City but also in nearby areas within our service coverage.
We aim to make life easier for busy individuals and families by offering affordable, reliable, and professional cleaning services.
We are committed to providing a modern, hassle-free booking system that gives convenience and comfort — making cleaning services more accessible to everyone in Bacolod and nearby cities.
We dream of helping build a happy, clean, and growing community.
',
            'vision' => 'Our vision is to be a leading cleaning service in Bacolod and nearby cities  known not only for excellence and trust but also for our heart to uplift others.
We dream of building a community where hardworking people  especially mothers can find purpose, confidence, and financial independence through honest work.
Beyond cleaning, we are committed to empowering local workers by creating job opportunities, teaching valuable skills and trainings, and showing that they can earn and grow without leaving the country to work overseas.
With SmartHands, we don’t just clean spaces and homes — we aim to change lives, one opportunity at a time.
',
            'services_offered' => 'We offer a comprehensive range of cleaning services including residential cleaning, commercial cleaning, deep cleaning, and specialized cleaning solutions.'
        ]);
    }
}
