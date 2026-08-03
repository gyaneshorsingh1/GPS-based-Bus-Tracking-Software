<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::findOrCreate('dashboard.view');

        Permission::findOrCreate('bus.view');
        Permission::findOrCreate('bus.create');
        Permission::findOrCreate('bus.update');
        Permission::findOrCreate('bus.delete');

        Permission::findOrCreate('student.view');
        Permission::findOrCreate('student.create');
        Permission::findOrCreate('student.update');
        Permission::findOrCreate('student.delete');

        // Create Roles
        $superAdmin = Role::findOrCreate('Super Admin');
        $schoolAdmin = Role::findOrCreate('School Admin');
        $driver = Role::findOrCreate('Driver');

        // New Role
        $parent = Role::findOrCreate('Parent');

        // Assign permissions
        $superAdmin->syncPermissions(Permission::all());

        $schoolAdmin->syncPermissions([
            'bus.view',
            'bus.create',
            'bus.update',
            'bus.delete',
            'student.view',
            'student.create',
            'student.update',
            'student.delete',
        ]);

        $driver->syncPermissions([
            'bus.view',
        ]);

        $parent->syncPermissions([
            // Add parent permissions here when needed
        ]);
    }
}