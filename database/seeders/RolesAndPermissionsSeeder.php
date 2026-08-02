<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::create(['name' => 'bus.view']);
        Permission::create(['name' => 'bus.create']);
        Permission::create(['name' => 'bus.update']);
        Permission::create(['name' => 'bus.delete']);

        Permission::create(['name' => 'student.view']);
        Permission::create(['name' => 'student.create']);
        Permission::create(['name' => 'student.update']);
        Permission::create(['name' => 'student.delete']);

        // Create Roles
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $schoolAdmin = Role::create(['name' => 'School Admin']);
        $driver = Role::create(['name' => 'Driver']);

        // Assign permissions
        $superAdmin->givePermissionTo(Permission::all());

        $schoolAdmin->givePermissionTo([
            'bus.view',
            'bus.create',
            'bus.update',
            'bus.delete',
            'student.view',
            'student.create',
            'student.update',
            'student.delete',
        ]);

        $driver->givePermissionTo([
            'bus.view',
        ]);
    }
}