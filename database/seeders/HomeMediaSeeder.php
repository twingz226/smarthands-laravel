<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HomeMedia;

class HomeMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed a default hero video pointing to the existing static asset
        // This allows the homepage hero to be managed dynamically
        $exists = HomeMedia::where('section', HomeMedia::SECTION_HERO)
            ->where('media_type', HomeMedia::TYPE_VIDEO)
            ->where('media_path', 'clean.mp4')
            ->exists();

        if (!$exists) {
            HomeMedia::create([
                'title' => 'Default Hero Video',
                'description' => 'Seeded default hero video (clean.mp4)',
                'media_type' => HomeMedia::TYPE_VIDEO,
                'media_path' => 'clean.mp4', // stored under public/clean.mp4
                'poster_image' => null, // e.g., 'images/hero-poster.jpg' if available
                'section' => HomeMedia::SECTION_HERO,
                'display_order' => 0,
                'is_active' => true,
            ]);
        }
    }
}
