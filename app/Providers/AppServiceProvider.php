<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Services\Interfaces\UserServiceInterface::class, \App\Services\UserService::class);
        $this->app->bind(\App\Services\Interfaces\TimesheetServiceInterface::class, \App\Services\TimesheetService::class);
        $this->app->bind(\App\Services\Interfaces\DashboardServiceInterface::class, \App\Services\DashboardService::class);
    }
}
