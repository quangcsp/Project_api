<?php

namespace App\Providers;

use Fauth;
use App\Auth\CustomUser;
use Illuminate\Support\ServiceProvider;

class CustomAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('custom', function () {
            return new CustomUser();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
