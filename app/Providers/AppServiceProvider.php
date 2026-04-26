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
        // --- 1. إضافة الأدوار (Roles) بالأسماء الصحيحة ---
        if (\Schema::hasTable('roles') && \DB::table('roles')->count() == 0) {
            $roles = [
                ['name' => 'مشرف', 'guard_name' => 'web'],
                ['name' => 'لجنة', 'guard_name' => 'web'],
                ['name' => 'طالب', 'guard_name' => 'web'],
            ];

            foreach ($roles as $role) {
                $role['created_at'] = now();
                $role['updated_at'] = now();
                \DB::table('roles')->insert($role);
            }
        }

        // --- 2. إضافة التخصصات (كما فعلنا سابقاً) ---
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