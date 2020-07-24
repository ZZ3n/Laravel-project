<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\UserService','App\Services\UserServiceImpl');
        $this->app->bind('App\Repositories\UserRepository','App\Repositories\UserRepositoryImpl');

        $this->app->bind('App\Services\MeetingService','App\Services\MeetingServiceImpl');
        $this->app->bind('App\Repositories\MeetingRepository','App\Repositories\MeetingRepositoryImpl');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.topNav','topNav');
        Blade::component('components.loginBox','loginBox');
    }
}
