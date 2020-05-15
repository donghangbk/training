<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // admin cannot create timesheet
        Gate::define("admin", function($user){
            return $user->role_id == User::ROLE_ADMIN;
        });

        // user cannot create user, setting
        Gate::define("user", function($user){
            return $user->role_id == User::ROLE_USER;
        });

    }
}
