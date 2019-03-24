<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AuthUserProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(User::class, function () {
            return \Auth::user() ?? new User();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
