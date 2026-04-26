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
    // الحل السحري: إذا كان جدول التخصصات فارغاً، املأه فوراً
    try {
        if (\Schema::hasTable('specialties') && \App\Models\Specialty::count() == 0) {
            $data = ['الطب البشري', 'تقنية المعلومات', 'علوم حاسوب', 'هندسة برمجيات', 'إدارة أعمال'];
            foreach ($data as $name) {
                \App\Models\Specialty::create(['name' => $name]);
            }
        }
    } catch (\Exception $e) {
        // لتجنب أي خطأ في حال لم ينشأ الجدول بعد
    }
    
    // كود الـ HTTPS الذي أضفناه سابقاً
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
}