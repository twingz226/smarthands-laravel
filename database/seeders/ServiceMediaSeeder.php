<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeMedia;

class ServiceMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Customized Deep Cleaning',
                'description' => 'Tailored cleaning solutions to meet your specific needs and preferences.',
                'media_type' => HomeMedia::TYPE_IMAGE,
                'media_path' => 'images/service1.jpg',
                'section' => HomeMedia::SECTION_SERVICES,
                'display_order' => 1,
                'is_active' => true,
                'price' => '₱299/hr minimum of 6 hours',
                'service_type' => 'hourly',
                'service_id' => 1,
            ],
            [
                'title' => 'Apartment Deep Cleaning',
                'description' => 'Thorough cleaning for apartments, covering every corner and surface.',
                'media_type' => HomeMedia::TYPE_IMAGE,
                'media_path' => 'images/service2.jpg',
                'section' => HomeMedia::SECTION_SERVICES,
                'display_order' => 2,
                'is_active' => true,
                'price' => '₱299/hr minimum of 6 hours',
                'service_type' => 'hourly',
                'service_id' => 2,
            ],
            [
                'title' => '2-Story/Bungalow House Cleaning',
                'description' => 'Comprehensive cleaning for larger homes with attention to all levels.',
                'media_type' => HomeMedia::TYPE_IMAGE,
                'media_path' => 'images/service3.jpg',
                'section' => HomeMedia::SECTION_SERVICES,
                'display_order' => 3,
                'is_active' => true,
                'price' => '₱75/sqm',
                'service_type' => 'sqm',
                'service_id' => 3,
            ],
            [
                'title' => 'Move-in/Move-out Cleaning',
                'description' => 'Make your transition smooth with our professional move cleaning services.',
                'media_type' => HomeMedia::TYPE_IMAGE,
                'media_path' => 'images/service4.jpg',
                'section' => HomeMedia::SECTION_SERVICES,
                'display_order' => 4,
                'is_active' => true,
                'price' => 'up to 25 sqm ₱299/hr (min 6 hrs); 26+ sqm: ₱75/sqm',
                'service_type' => 'hourly',
                'service_id' => 4,
            ],
            [
                'title' => 'Post-Construction/Renovation Cleaning',
                'description' => 'Specialized cleaning to remove construction dust and debris.',
                'media_type' => HomeMedia::TYPE_IMAGE,
                'media_path' => 'images/service5.jpg',
                'section' => HomeMedia::SECTION_SERVICES,
                'display_order' => 5,
                'is_active' => true,
                'price' => '₱75/sqm',
                'service_type' => 'sqm',
                'service_id' => 5,
            ],
        ];

        foreach ($services as $service) {
            HomeMedia::updateOrCreate(
                [
                    'section' => HomeMedia::SECTION_SERVICES,
                    'display_order' => $service['display_order']
                ],
                $service
            );
        }
    }
}
