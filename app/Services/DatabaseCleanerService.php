<?php

namespace App\Services;

use App\Models\CleanerLog;
use App\Models\JobAssignment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseCleanerService
{
    public function cleanCompletedJobs()
    {
        return DB::transaction(function () {
            $threshold = now()->subDays(30);
            $count = JobAssignment::where('status', 'completed')
                        ->where('updated_at', '<', $threshold)
                        ->delete();
            
            return $count;
        });
    }

    public function cleanOrphanedServices()
    {
        return DB::transaction(function () {
            $count = Service::doesntHave('bookings')
                       ->doesntHave('assignments')
                       ->delete();
            
            return $count;
        });
    }

    public function cleanDuplicateServices()
    {
        return DB::transaction(function () {
            $duplicates = Service::select('name')
                           ->groupBy('name')
                           ->havingRaw('COUNT(*) > 1')
                           ->get();

            $count = 0;
            foreach ($duplicates as $duplicate) {
                $services = Service::where('name', $duplicate->name)
                             ->orderBy('created_at')
                             ->get();

                // Keep the oldest, delete others
                if ($services->count() > 1) {
                    $count += $services->slice(1)->each->delete()->count();
                }
            }

            return $count;
        });
    }

    public function cleanOldSystemLogs()
    {
        return DB::transaction(function () {
            $threshold = now()->subMonths(3);
            $count = CleanerLog::where('created_at', '<', $threshold)->delete();
            
            return $count;
        });
    }
}