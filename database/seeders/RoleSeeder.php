<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Modules a School Admin cannot manage.
     */
    private const SCHOOL_ADMIN_EXCLUDED_MODULES = [
        'school-admin',
        'role',
        'permission',
    ];

    /**
     * Permissions assigned to the Driver role.
     */
    private const DRIVER_PERMISSIONS = [
        'dashboard.view',
        'profile.view',
        'profile.update',
        'trip.view',
        'trip.start',
        'trip.end',
        'gps.track',
        'attendance.mark',
        'pickup.mark',
        'drop.mark',
        'notification.view',
        'emergency.create',
    ];

    /**
     * Permissions assigned to the Parent role.
     */
    private const PARENT_PERMISSIONS = [
        'dashboard.view',
        'profile.view',
        'profile.update',
        'student.view',
        'gps.view',
        'trip.view',
        'attendance.view',
        'notification.view',
    ];

    /**
     * Seed the application's roles and their permissions.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Super Admin: every permission.
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);
        $superAdmin->syncPermissions(Permission::all());

        // School Admin: everything except school-admin.*, role.* and permission.*.
        $schoolAdmin = Role::firstOrCreate([
            'name' => 'School Admin',
            'guard_name' => 'web',
        ]);
        $schoolAdmin->syncPermissions(
            PermissionSeeder::names(self::SCHOOL_ADMIN_EXCLUDED_MODULES)
        );

        // Driver: trip execution and emergency reporting.
        $driver = Role::firstOrCreate([
            'name' => 'Driver',
            'guard_name' => 'web',
        ]);
        $driver->syncPermissions(self::DRIVER_PERMISSIONS);

        // Parent: monitoring their children.
        $parent = Role::firstOrCreate([
            'name' => 'Parent',
            'guard_name' => 'web',
        ]);
        $parent->syncPermissions(self::PARENT_PERMISSIONS);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
