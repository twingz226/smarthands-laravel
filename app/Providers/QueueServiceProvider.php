<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['queue']->getDatabaseTable();
        $this->app['queue']->setTableName('job_queue');
    }
}
