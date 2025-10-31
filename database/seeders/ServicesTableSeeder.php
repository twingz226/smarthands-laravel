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
                'description' => 'Tailored cleaning solutions to meet your specific needs and preferences.',
                'price' => 299.00,
                'duration_minutes' => 360, // 6 hours minimum
                'pricing_type' => 'duration'
            ],
            [
                'name' => 'Apartment Deep Cleaning',
                'description' => 'Thorough cleaning for apartments, covering every corner and surface.',
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
                'description' => 'Make your transition smooth with our professional move cleaning services. Pricing: Up to 25 sqm is charged hourly at ₱299/hr (minimum of 6 hours). For 26 sqm and above, pricing is ₱75 per sqm.',
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
