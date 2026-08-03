<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * CRUD actions applied to resource modules.
     */
    private const CRUD = ['create', 'view', 'update', 'delete'];

    /**
     * Module map: module name => allowed actions.
     *
     * Single source of truth shared with RoleSeeder.
     */
    public const MODULES = [
        'dashboard' => ['view'],
        'school' => self::CRUD,
        'school-admin' => self::CRUD,
        'driver' => self::CRUD,
        'parent' => self::CRUD,
        'student' => self::CRUD,
        'bus' => self::CRUD,
        'route' => self::CRUD,
        'stop' => self::CRUD,
        'bus-assignment' => self::CRUD,
        'student-assignment' => self::CRUD,
        'trip' => ['create', 'view', 'update', 'delete', 'start', 'end'],
        'gps' => ['view', 'track'],
        'attendance' => ['view', 'mark', 'update'],
        'pickup' => ['view', 'mark'],
        'drop' => ['view', 'mark'],
        'notification' => ['view', 'send'],
        'report' => ['view', 'export'],
        'emergency' => ['view', 'create', 'resolve'],
        'profile' => ['view', 'update'],
        'role' => self::CRUD,
        'permission' => self::CRUD,
        'settings' => ['view', 'update'],
    ];

    /**
     * Flatten the module map into "module.action" permission names.
     *
     * @param  array<int, string>  $excludeModules  Modules to skip.
     * @return array<int, string>
     */
    public static function names(array $excludeModules = []): array
    {
        $names = [];

        foreach (self::MODULES as $module => $actions) {
            if (in_array($module, $excludeModules, true)) {
                continue;
            }

            foreach ($actions as $action) {
                $names[] = "{$module}.{$action}";
            }
        }

        return $names;
    }

    /**
     * Seed the application's permissions.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::names() as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
