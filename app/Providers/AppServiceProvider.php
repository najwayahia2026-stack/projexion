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
    // 1. تأمين الروابط HTTPS في Railway (ضروري جداً للموقع)
    if (config('app.env') === 'production' || config('app.url') !== 'http://localhost') {
        \URL::forceScheme('https');
    }

    // ملاحظة: تم نقل كود الجداول والبيانات إلى DatabaseSeeder 
    // لكي لا يتسبب في تعليق الموقع أثناء مرحلة البناء (Build)
}
}