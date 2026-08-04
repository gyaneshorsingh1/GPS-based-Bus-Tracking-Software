<?php

namespace Tests\Feature;

use App\Models\ParentProfile;
use App\Models\School;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_admin_only_sees_parents_from_their_own_school(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $schoolA = $this->createSchool('Sunrise Academy', 'SCH-A');
        $schoolB = $this->createSchool('Moonlight School', 'SCH-B');

        $admin = User::factory()->create([
            'school_id' => $schoolA->id,
        ]);
        $admin->assignRole('School Admin');

        $ownParent = $this->createParent('Own Parent', 'own@example.com', $schoolA);
        $otherParent = $this->createParent('Other Parent', 'other@example.com', $schoolB);

        $response = $this->actingAs($admin)->get(route('parents.index'));
        $response->assertOk();
        $response->assertSee('Own Parent');
        $response->assertDontSee('Other Parent');

        $response = $this->actingAs($admin)->get(route('parents.show', $otherParent));
        $response->assertForbidden();

        $response = $this->actingAs($admin)->get(route('parents.show', $ownParent));
        $response->assertOk();
    }

    public function test_school_admin_cannot_create_or_move_parent_to_another_school(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $schoolA = $this->createSchool('Sunrise Academy', 'SCH-A');
        $schoolB = $this->createSchool('Moonlight School', 'SCH-B');

        $admin = User::factory()->create([
            'school_id' => $schoolA->id,
        ]);
        $admin->assignRole('School Admin');

        $response = $this->actingAs($admin)->post(route('parents.store'), [
            'name' => 'New Parent',
            'email' => 'new@example.com',
            'password' => 'password123',
            'school_id' => $schoolB->id,
            'father_name' => 'Father Name',
            'mother_name' => 'Mother Name',
            'phone' => '9800000000',
            'alternate_phone' => null,
            'address' => 'Kathmandu',
            'occupation' => 'Engineer',
        ]);

        $response->assertRedirect(route('parents.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'school_id' => $schoolA->id,
        ]);

        $parent = ParentProfile::whereHas('user', fn ($q) => $q->where('email', 'new@example.com'))->first();
        $this->assertNotNull($parent);
        $this->assertSame($schoolA->id, $parent->school_id);

        $response = $this->actingAs($admin)->put(route('parents.update', $parent), [
            'name' => 'New Parent',
            'email' => 'new@example.com',
            'school_id' => $schoolB->id,
            'father_name' => 'Father Name',
            'phone' => '9800000000',
            'address' => 'Kathmandu',
        ]);

        $response->assertRedirect(route('parents.index'));

        $parent->refresh();
        $this->assertSame($schoolA->id, $parent->school_id);
        $this->assertSame($schoolA->id, $parent->user->school_id);
    }

    private function createSchool(string $name, string $code): School
    {
        return School::create([
            'name' => $name,
            'code' => $code,
            'email' => strtolower($code).'@school.com',
            'phone' => '9800000000',
            'address' => 'Kathmandu',
            'principal_name' => 'Principal',
            'status' => 'active',
        ]);
    }

    private function createParent(string $name, string $email, School $school): ParentProfile
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'school_id' => $school->id,
        ]);
        $user->assignRole('Parent');

        return ParentProfile::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'father_name' => 'Father',
            'mother_name' => 'Mother',
            'phone' => '9800000000',
            'address' => 'Kathmandu',
        ]);
    }
}
