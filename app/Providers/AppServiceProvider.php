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
    // تأمين الروابط HTTPS
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }

    try {
        // --- الحل القوي: إعادة تعيين الأدوار لضمان وجودها ---
        if (\Schema::hasTable('roles')) {
            $requiredRoles = ['أدمين', 'مشرف', 'لجنة', 'طالب'];
            
            foreach ($requiredRoles as $roleName) {
                // البحث عن الدور، وإذا لم يكن موجوداً سيتم إنشاؤه فوراً
                \DB::table('roles')->updateOrInsert(
                    ['name' => $roleName, 'guard_name' => 'web'],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // --- إضافة التخصصات ---
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
        // منع تعطل الموقع
    }
}
}