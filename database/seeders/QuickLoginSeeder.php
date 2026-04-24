<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuickLoginSeeder extends Seeder
{
    public function run(): void
    {
        $aiSpecialty = Specialty::where('name', 'الذكاء الاصطناعي')->first();

        // Create demo users for each role
        $users = [
            [
                'name' => 'طالب تجريبي',
                'email' => 'student@smartgrad.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ],
            [
                'name' => 'مشرف تجريبي',
                'email' => 'supervisor@smartgrad.com',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
            ],
            [
                'name' => 'لجنة التقييم',
                'email' => 'committee@smartgrad.com',
                'password' => Hash::make('password'),
                'role' => 'committee',
                'specialty_id' => $aiSpecialty?->id,
            ],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@smartgrad.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            $specialtyId = $userData['specialty_id'] ?? null;
            unset($userData['role'], $userData['specialty_id']);

            if ($specialtyId) {
                $userData['specialty_id'] = $specialtyId;
            }

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($specialtyId && !$user->specialty_id) {
                $user->update(['specialty_id' => $specialtyId]);
            }

            // Assign role if not already assigned
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}
