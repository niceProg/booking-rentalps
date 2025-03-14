<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

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
        // dd(env('MIDTRANS_SERVER_KEY'));

        Config::$serverKey = 'SB-Mid-server-yJhLTv_XYTCE1Crtg3nsFp31';
        Config::$clientKey = 'SB-Mid-client-mAEFVJ5OPf3-1ril';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}