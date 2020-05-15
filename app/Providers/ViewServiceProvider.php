<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\View\Composer\MenuComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \View::composer(
            'layouts.partials.sidebar', MenuComposer::class
        );
    }
}
