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
    // تأمين الروابط HTTPS في Railway
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }

    try {
        // --- 1. إضافة الأدوار (Roles) ---
        // تأكدي من أسماء الأدوار (user, admin) إذا كنتِ تستخدمين أسماء مختلفة غيريها هنا
        if (\Schema::hasTable('roles') && \DB::table('roles')->count() == 0) {
            \DB::table('roles')->insert([
                ['name' => 'user', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // --- 2. إضافة التخصصات (Specialties) ---
        if (\Schema::hasTable('specialties') && \App\Models\Specialty::count() < 10) {
            $specialties = [
                'الطب البشري', 'طب الأسنان', 'الصيدلة', 'التمريض', 'المختبرات', 'الأشعة',
                'الهندسة المدنية', 'الهندسة المعمارية', 'الهندسة الكهربائية', 'الهندسة الميكانيكية', 
                'الهندسة الصناعية', 'علوم حاسوب', 'نظم معلومات', 'تقنية المعلومات', 
                'الأمن السيبراني', 'الذكاء الاصطناعي', 'إدارة الأعمال', 'المحاسبة', 'الاقتصاد', 
                'الشريعة والقانون', 'الدراسات الإسلامية', 'الإعلام', 'الترجمة', 'اللغة العربية'
            ];

            foreach ($specialties as $name) {
                \App\Models\Specialty::firstOrCreate(['name' => trim($name)]);
            }
        }
    } catch (\Exception $e) {
        // لتجنب تعطل الموقع
    }
}
}