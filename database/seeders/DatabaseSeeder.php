<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تشغيل الملفات المنفصلة التي لديكِ (الأدوار والتخصصات)
        $this->call([
            RoleSeeder::class,
            SpecialtySeeder::class,
            QuickLoginSeeder::class,
        ]);

        // 2. إنشاء حساب الأدمن الأساسي إذا لم يكن موجوداً
        User::firstOrCreate(
            ['email' => 'admin@projexion.com'],
            [
                'name' => 'مدير النظام',
                'username' => 'admin_main',
                'password' => Hash::make('najwa2026'),
                'email_verified_at' => now(),
            ]
        );
        
        // ملاحظة: تأكدي أن RoleSeeder يحتوي على منطق إسناد دور "admin" للمستخدم
    }
}