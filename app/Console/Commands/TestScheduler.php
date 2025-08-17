<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestScheduler extends Command
{
    protected $signature = 'scheduler:test';
    protected $description = 'Test if the scheduler is running properly';

    public function handle()
    {
        Log::info('Scheduler test ran at: ' . now());
        $this->info('Scheduler test completed. Check storage/logs/laravel.log');
    }
} 