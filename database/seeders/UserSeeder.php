<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'Super Admin',
            ],
            [
                'name' => 'School Admin',
                'email' => 'schooladmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'School Admin',
            ],
            [
                'name' => 'Driver',
                'email' => 'driver@example.com',
                'password' => Hash::make('password'),
                'role' => 'Driver',
            ],
            [
                'name' => 'Parent',
                'email' => 'parent@example.com',
                'password' => Hash::make('password'),
                'role' => 'Parent',
            ],
        ];

        foreach ($users as $data) {

            $role = Role::where('name', $data['role'])->first();

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                ]
            );

            $user->syncRoles([$role]);
        }
    }
}