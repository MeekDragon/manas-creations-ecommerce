<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production / behind load balancers
        if (env('APP_ENV') === 'production' || (env('APP_URL') && str_starts_with(env('APP_URL'), 'https://'))) {
            URL::forceScheme('https');
        }

        Mail::extend('brevo', function (array $config) {
            return new \App\Mail\BrevoApiTransport(env('MAIL_PASSWORD') ?: '');
        });
    }
}
