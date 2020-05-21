<?php

namespace App\Providers;

use App\Models\Repair;
use App\Observers\RepairObserver;
use Illuminate\Support\ServiceProvider;

class ModelObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Repair::observe(RepairObserver::class);
    }
}
