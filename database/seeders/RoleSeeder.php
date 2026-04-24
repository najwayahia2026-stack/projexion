<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'approve projects',
            'reject projects',
            'examine projects',
            'view groups',
            'create groups',
            'edit groups',
            'delete groups',
            'manage groups', // For adding students/managers
            'view evaluations',
            'create evaluations',
            'edit evaluations',
            'view reports',
            'create reports',
            'view dashboard',
            'send notifications',
            'view notifications',
            'manage users', // Admin only
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Student permissions
        $student = Role::firstOrCreate(['name' => 'student']);
        $student->givePermissionTo([
            'view projects',
            'create projects',
            'edit projects',
            'delete projects', // الطلاب يمكنهم حذف مشاريعهم
            'view groups',
            'view reports',
            'create reports',
            'view dashboard',
            'view notifications',
        ]);

        // Supervisor permissions (can create groups, view projects, examine, send notifications)
        // Supervisor now has all permissions that department_admin had (merged)
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisor->givePermissionTo([
            'view projects',
            'approve projects',
            'reject projects',
            'examine projects',
            'view groups',
            'create groups',
            'edit groups',
            'manage groups',
            'view evaluations',
            'view reports',
            'view dashboard',
            'send notifications',
            'view notifications',
        ]);

        // Committee permissions (can review and evaluate)
        $committee = Role::firstOrCreate(['name' => 'committee']);
        $committee->givePermissionTo([
            'view projects',
            'approve projects',
            'reject projects',
            'view evaluations',
            'create evaluations',
            'edit evaluations',
            'view dashboard',
            'view notifications',
        ]);

        // Admin permissions (full access)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
