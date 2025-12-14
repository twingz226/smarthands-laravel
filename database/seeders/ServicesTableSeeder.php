<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Customized Deep Cleaning',
                'description' => 'Tailored cleaning solutions to meet your specific needs and preferences. Pricing starts at ₱299 per hour with a 6-hour minimum. After the first 6 hours, you will be charged per hour based on actual time spent.',
                'price' => 299.00,
                'duration_minutes' => 360, // 6 hours minimum
                'pricing_type' => 'duration'
            ],
            [
                'name' => 'Apartment Deep Cleaning',
                'description' => 'Thorough cleaning for apartments, covering every corner and surface. Base price of ₱299 per hour with a 6-hour minimum. Additional hours beyond the minimum will be charged at the same hourly rate.',
                'price' => 299.00,
                'duration_minutes' => 360, // 6 hours minimum
                'pricing_type' => 'duration'
            ],
            [
                'name' => '2-Story/Bungalow House Cleaning',
                'description' => 'Comprehensive cleaning for larger homes with attention to all levels.',
                'price' => 75.00,
                'duration_minutes' => null,
                'pricing_type' => 'sqm'
            ],
            [
                'name' => 'Move-in/Move-out Cleaning',
                'description' => 'Professional deep cleaning service for when you\'re moving in or out of a property. Our comprehensive cleaning ensures your new or old space is spotless. For properties up to 25 square meters, we charge ₱299 per hour with a minimum of 6 hours. For larger properties (26+ sqm), we offer a flat rate of ₱75 per square meter for complete peace of mind during your move. Pricing: up to 25 sqm: ₱299/hr (6-hour minimum) | 26+ sqm: ₱75 per square meter',
                'price' => 299.00,
                'duration_minutes' => 360, // 6 hours minimum
                'pricing_type' => 'duration'
            ],
            [
                'name' => 'Post-Construction/Renovation Cleaning',
                'description' => 'Specialized cleaning to remove construction dust and debris.',
                'price' => 75.00,
                'duration_minutes' => null,
                'pricing_type' => 'sqm'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
