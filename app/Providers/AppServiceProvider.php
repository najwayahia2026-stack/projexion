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
    if (config('app.env') === 'production' || config('app.url') !== 'http://localhost') {
        \URL::forceScheme('https');
    }

    try {
        // 2. إنشاء الأدوار (Roles) - نستخدم updateOrInsert وهي آمنة جداً
        if (\Schema::hasTable('roles')) {
            $roles = ['admin', 'student', 'supervisor', 'committee', 'أدمين', 'طالب', 'مشرف', 'لجنة'];
            foreach ($roles as $roleName) {
                \DB::table('roles')->updateOrInsert(
                    ['name' => $roleName, 'guard_name' => 'web'],
                    ['updated_at' => now()]
                );
            }
        }

        // 3. إنشاء حساب الأدمن - التحقق يدوياً لمنع خطأ Duplicate Entry
        if (\Schema::hasTable('users')) {
            $adminEmail = 'admin@projexion.com';
            $adminUsername = 'admin_main';
            
            // نتحقق أولاً هل يوجد مستخدم بهذا الإيميل أو اسم المستخدم؟
            $admin = \App\Models\User::where('email', $adminEmail)
                                     ->orWhere('username', $adminUsername)
                                     ->first();

            if (!$admin) {
                $admin = \App\Models\User::create([
                    'name' => 'مدير النظام',
                    'email' => $adminEmail,
                    'username' => $adminUsername,
                    'password' => \Hash::make('najwa2026'),
                    'email_verified_at' => now(),
                ]);
            }

            // إسناد الأدوار
            if ($admin && method_exists($admin, 'assignRole')) {
                $admin->syncRoles(['admin', 'أدمين']); 
            }
        }

        // 4. إضافة التخصصات
        if (\Schema::hasTable('specialties')) {
            $count = \App\Models\Specialty::count();
            if ($count < 10) {
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
        }

    } catch (\Exception $e) {
        // نكتفي بتسجيل الخطأ في السجلات (Logs)
        \Log::warning("Boot Provider setup skipped: " . $e->getMessage());
    }
}
}