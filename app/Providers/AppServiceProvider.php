<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // إجبار استخدام HTTPS في بيئة الإنتاج (Railway) لإصلاح مشكلة الـ Mixed Content
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}