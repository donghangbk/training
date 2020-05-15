<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $applicationServices = [];

    protected $adminServices = [
        \App\Services\Admin\Interfaces\UserServiceInterface::class => \App\Services\Admin\UserService::class,
        \App\Services\Admin\Interfaces\SettingServiceInterface::class => \App\Services\Admin\SettingService::class,
    ];

    protected $userServices = [
        \App\Services\User\Interfaces\ProfileServiceInterface::class => \App\Services\User\ProfileService::class,
        \App\Services\User\Interfaces\TimesheetServiceInterface::class => \App\Services\User\TimesheetService::class
    ];


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
        $this->applicationServices = array_merge($this->adminServices, $this->userServices);

        foreach ($this->applicationServices as $interface => $service) {
            $this->app->bind($interface, $service);
        }
    }
}
