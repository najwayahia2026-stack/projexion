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
        // ملاحظة: أضفنا الأسماء بالإنجليزية لتطابق الـ Controller الخاص بكِ
        if (\Schema::hasTable('roles')) {
            $roles = [
                'admin', 
                'student', 
                'supervisor', 
                'committee',
                'أدمين',
                'طالب',
                'مشرف',
                'لجنة'
            ];

            foreach ($roles as $roleName) {
                \DB::table('roles')->updateOrInsert(
                    ['name' => $roleName, 'guard_name' => 'web'],
                    ['updated_at' => now()]
                );
            }
        }

        // --- 2. إضافة التخصصات ---
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