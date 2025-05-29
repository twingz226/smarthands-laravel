<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('customers')->insert([
            'Customer_Id' => 'TEMP001',
            'Name' => 'Temporary Customer',
            'Contact' => '0000000000',
            'Email' => 'temp@example.com',
            'Address' => 'Temporary Address',
            'Registered_Date' => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
