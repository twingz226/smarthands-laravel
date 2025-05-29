<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ImportCustomers extends Command
{
    protected $signature = 'import:customers';
    protected $description = 'Import customers from old database';

    public function handle()
    {
        $oldCustomers = DB::connection('mysql_old')->table('customer_database')->get();

        foreach ($oldCustomers as $customer) {
            Customer::create([
                'Customer_Id' => $customer->Customer_Id,
                'Name' => $customer->Name,
                'Contact' => $customer->Contact,
                'Email' => $customer->Email,
                'Address' => $customer->Address,
                'Registered_Date' => $customer->Registered_Date
            ]);
        }

        $this->info('Customers imported successfully!');
    }
}