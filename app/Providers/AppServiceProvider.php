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
    // 1. تأمين الروابط HTTPS في Railway
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }

    try {
        // 2. إنشاء الأدوار (Roles) أولاً لضمان وجودها
        if (\Schema::hasTable('roles')) {
            $roles = ['admin', 'student', 'supervisor', 'committee', 'أدمين', 'طالب', 'مشرف', 'لجنة'];
            
            foreach ($roles as $roleName) {
                \DB::table('roles')->updateOrInsert(
                    ['name' => $roleName, 'guard_name' => 'web'],
                    ['updated_at' => now()]
                );
            }
        }

        // 3. إنشاء حساب الأدمن وإسناد الأدوار له
        if (\Schema::hasTable('users')) {
            $adminEmail = 'admin@pro-jexion.com';
            
            $admin = \App\Models\User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'مدير النظام',
                    'username' => 'admin_main',
                    'password' => \Hash::make('12345678'), // كلمة المرور
                    'email_verified_at' => now(),
                ]
            );

            // إسناد الأدوار (بشرط وجود حزمة Spatie مفعلة)
            if (method_exists($admin, 'assignRole')) {
                $admin->syncRoles(['admin', 'أدمين']); 
            }
        }

        // 4. إضافة التخصصات
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
        // تسجيل الخطأ في السجلات لمراجعته إذا لزم الأمر دون تعطل الموقع
        \Log::error("Error in Boot Provider: " . $e->getMessage());
    }
}
}