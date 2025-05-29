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
                'name' => 'Basic Cleaning',
                'description' => 'Standard cleaning service for homes.',
                'price' => 50.00,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Deep Cleaning',
                'description' => 'Thorough cleaning service for homes.',
                'price' => 100.00,
                'duration_minutes' => 120
            ],
            [
                'name' => 'Move In/Out Cleaning',
                'description' => 'Cleaning service for moving in or out.',
                'price' => 150.00,
                'duration_minutes' => 180
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
