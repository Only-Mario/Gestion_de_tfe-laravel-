<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Mail::extend('gmail-relais', function (array $config) {
            return new \App\Services\GmailRelaisTransport($config['url'], $config['cle']);
        });
        \Illuminate\Support\Facades\Mail::extend('hebergeur', function (array $config) {
            return new \App\Services\HebergeurTransport($config['path']);
        });
    }
}
