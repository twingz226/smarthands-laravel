<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        // Create a default admin user if it doesn't exist
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('adminpassword123'), // Change this password after first login
                'role' => 'admin',
            ]);
        }

        $this->call([
            ServicesTableSeeder::class,
            EmployeeSeeder::class,
            HomeMediaSeeder::class,
            ServiceMediaSeeder::class,
            ContactInfoSeeder::class,
        ]);
    }
}
