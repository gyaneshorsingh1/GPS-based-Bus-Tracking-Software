<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed application users.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Find a school
        |--------------------------------------------------------------------------
        */

        $school = School::first();

        if (! $school) {
            $this->command->error(
                'No school exists. Please create a school before running UserSeeder.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        |
        | Super Admin is a system-level user.
        | Therefore school_id is NULL.
        |
        */

        $superAdmin = User::updateOrCreate(
            [
                'email' => 'superadmin@example.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'school_id' => null,
            ]
        );

        $superAdmin->syncRoles([
            'Super Admin',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SCHOOL ADMIN / PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $schoolAdmin = User::updateOrCreate(
            [
                'email' => 'principal@example.com',
            ],
            [
                'name' => 'School Principal',
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]
        );

        $schoolAdmin->syncRoles([
            'School Admin',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        $driver = User::updateOrCreate(
            [
                'email' => 'driver@example.com',
            ],
            [
                'name' => 'School Driver',
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]
        );

        $driver->syncRoles([
            'Driver',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PARENT
        |--------------------------------------------------------------------------
        */

        $parent = User::updateOrCreate(
            [
                'email' => 'parent@example.com',
            ],
            [
                'name' => 'Student Parent',
                'password' => Hash::make('password'),
                'school_id' => $school->id,
            ]
        );

        $parent->syncRoles([
            'Parent',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS MESSAGE
        |--------------------------------------------------------------------------
        */

        $this->command->info('Users and roles seeded successfully.');

        $this->command->line('');
        $this->command->line('Login accounts:');
        $this->command->line('Super Admin : superadmin@example.com');
        $this->command->line('Principal   : principal@example.com');
        $this->command->line('Driver      : driver@example.com');
        $this->command->line('Parent      : parent@example.com');
        $this->command->line('');
        $this->command->warn('All seeded users use password: password');
    }
}
