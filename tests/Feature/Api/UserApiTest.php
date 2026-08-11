<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private function seedRolesAndPermissions(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    }

    private function superAdmin(): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('Super Admin');

        return $user;
    }

    private function driver(): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('Driver');

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    public function test_super_admin_can_login_and_receive_token(): void
    {
        $this->seedRolesAndPermissions();

        $user = User::factory()->create([
            'email' => 'apiadmin@example.com',
            'password' => 'password',
            'status' => 'active',
        ]);
        $user->assignRole('Super Admin');

        $response = $this->postJson('/api/login', [
            'email' => 'apiadmin@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'token',
                'token_type',
                'user' => ['id', 'name', 'email', 'roles'],
            ])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'apiadmin@example.com');

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'auth-token',
        ]);
    }

    public function test_login_with_invalid_credentials_returns_422(): void
    {
        $this->seedRolesAndPermissions();

        $response = $this->postJson('/api/login', [
            'email' => 'nobody@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_protected_endpoints_require_authentication(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_fetch_own_profile(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->superAdmin();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.roles.0', 'Super Admin');
    }

    public function test_logout_revokes_token(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->superAdmin();

        Sanctum::actingAs($user);

        $this->postJson('/api/logout')->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'auth-token',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    public function test_non_super_admin_cannot_access_user_management(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->driver();

        Sanctum::actingAs($user);

        $this->getJson('/api/users')->assertForbidden();
        $this->postJson('/api/users', [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'role' => 'Parent',
            'status' => 'active',
        ])->assertForbidden();
        $this->deleteJson('/api/users/1')->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | User Management (Super Admin)
    |--------------------------------------------------------------------------
    */

    public function test_super_admin_can_list_users(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();
        User::factory()->count(5)->create(['status' => 'active']);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name', 'email', 'roles']],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ])
            ->assertJsonPath('meta.total', 6)
            ->assertJsonMissingPath('data.0.password');
    }

    public function test_super_admin_can_search_and_filter_users(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        $parent = User::factory()->create(['name' => 'Searchable Parent', 'status' => 'active']);
        $parent->assignRole('Parent');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users?search=Searchable&role=Parent');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $parent->id)
            ->assertJsonPath('data.0.roles.0', 'Parent');
    }

    public function test_super_admin_can_view_single_user(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        $target = User::factory()->create(['name' => 'Target User', 'status' => 'active']);
        $target->assignRole('Driver');

        Sanctum::actingAs($admin);

        $this->getJson("/api/users/{$target->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $target->id)
            ->assertJsonPath('data.roles.0', 'Driver');
    }

    public function test_super_admin_can_create_user(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'role' => 'Parent',
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'User created successfully.')
            ->assertJsonPath('data.email', 'newuser@example.com')
            ->assertJsonPath('data.roles.0', 'Parent');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_create_user_validates_input(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'role' => 'Non Existent Role',
            'status' => 'bogus',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role', 'status']);
    }

    public function test_super_admin_can_update_user(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        $target = User::factory()->create(['name' => 'Old Name', 'status' => 'active']);
        $target->assignRole('Parent');

        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/users/{$target->id}", [
            'name' => 'New Name',
            'email' => $target->email,
            'password' => '',
            'role' => 'Driver',
            'status' => 'inactive',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.status', 'inactive')
            ->assertJsonPath('data.roles.0', 'Driver');

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'New Name',
            'status' => 'inactive',
        ]);
    }

    public function test_super_admin_can_delete_user(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        $target = User::factory()->create(['status' => 'active']);
        $target->assignRole('Parent');

        Sanctum::actingAs($admin);

        $this->deleteJson("/api/users/{$target->id}")
            ->assertOk()
            ->assertJsonPath('message', 'User deleted successfully.');

        $this->assertSoftDeleted('users', ['id' => $target->id]);
    }

    public function test_user_cannot_delete_own_account(): void
    {
        $this->seedRolesAndPermissions();

        $admin = $this->superAdmin();

        Sanctum::actingAs($admin);

        $this->deleteJson("/api/users/{$admin->id}")
            ->assertStatus(422)
            ->assertJsonPath('message', 'You cannot delete your own account.');
    }
}
