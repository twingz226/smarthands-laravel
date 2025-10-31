<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchiveOldCustomers extends Command
{
    protected $signature = 'customers:archive';
    protected $description = 'Archive customers with no activity in the last month';

    public function handle()
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        // Find customers with no recent activity
        $customers = Customer::where('is_archived', false)
            ->where(function ($query) use ($oneMonthAgo) {
                $query->whereDoesntHave('bookings', function ($q) use ($oneMonthAgo) {
                    $q->where('created_at', '>=', $oneMonthAgo);
                })
                ->whereDoesntHave('jobs', function ($q) use ($oneMonthAgo) {
                    $q->where('created_at', '>=', $oneMonthAgo);
                })
                ->whereDoesntHave('ratings', function ($q) use ($oneMonthAgo) {
                    $q->where('created_at', '>=', $oneMonthAgo);
                });
            })
            ->get();

        foreach ($customers as $customer) {
            $customer->update([
                'is_archived' => true,
                'archived_at' => now(),
                'archive_reason' => 'Automatically archived due to inactivity for over 1 month'
            ]);
        }

        $this->info("Successfully archived {$customers->count()} customers.");
    }
} 