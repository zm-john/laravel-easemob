<?php

namespace Quhang\LaravelEasemob;

use Illuminate\Support\ServiceProvider;

class EasemobServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/easemob.php' => config_path('easemob.php')]);
    }

    public function register()
    {
        $this->app->singleton('laravel-easemob', Easemob::class);
        $this->app->singleton(Service::class, Service::class);
    }
}
