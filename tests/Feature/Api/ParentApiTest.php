<?php

namespace Tests\Feature\Api;

use App\Models\Attendance;
use App\Models\Bus;
use App\Models\ParentProfile;
use App\Models\Route;
use App\Models\RouteStop;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ParentApiTest extends TestCase
{
    use RefreshDatabase;

    private function seedRolesAndPermissions(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    }

    private function makeSchool(): School
    {
        return School::create([
            'name' => 'Test School',
            'code' => 'SCH-'.uniqid(),
            'email' => 'school-'.uniqid().'@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'status' => 'active',
        ]);
    }

    private function parentProfile(User $user): ParentProfile
    {
        return ParentProfile::where('user_id', $user->id)->firstOrFail();
    }

    private function makeParent(?School $school = null): User
    {
        $school ??= $this->makeSchool();

        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('Parent');

        ParentProfile::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'father_name' => 'Father Name',
            'mother_name' => 'Mother Name',
            'phone' => '1234567890',
            'alternate_phone' => '0987654321',
            'address' => 'Home Address',
            'occupation' => 'Engineer',
        ]);

        return $user;
    }

    private function makeRoute(School $school): Route
    {
        return Route::create([
            'school_id' => $school->id,
            'name' => 'Main Route',
            'route_code' => 'RT-001',
            'start_location' => 'Station A',
            'end_location' => 'School Gate',
            'estimated_distance' => 5.5,
            'estimated_duration' => 20,
            'is_active' => true,
        ]);
    }

    private function makeBus(School $school, Route $route): Bus
    {
        return Bus::create([
            'school_id' => $school->id,
            'route_id' => $route->id,
            'bus_number' => 'BUS-01',
            'registration_number' => 'ABC-1234',
            'capacity' => 40,
            'gps_device_id' => 'IMEI-0001',
            'status' => 'Active',
        ]);
    }

    private function makeStudent(School $school, ParentProfile $parent, ?Bus $bus = null): Student
    {
        return Student::create([
            'school_id' => $school->id,
            'parent_id' => $parent->id,
            'bus_id' => $bus?->id,
            'admission_no' => 'ADM-'.uniqid(),
            'first_name' => 'Child',
            'last_name' => 'One',
            'date_of_birth' => '2015-01-01',
            'gender' => 'Male',
            'grade' => '5',
            'section' => 'A',
            'roll_no' => '01',
            'pickup_location' => 'Stop 1',
            'drop_location' => 'School Gate',
            'pickup_latitude' => 28.6139,
            'pickup_longitude' => 77.2090,
            'drop_latitude' => 28.6000,
            'drop_longitude' => 77.2000,
            'is_active' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication & Role Authorization
    |--------------------------------------------------------------------------
    */

    public function test_parent_api_requires_authentication(): void
    {
        $this->getJson('/api/parent/profile')->assertUnauthorized();
        $this->getJson('/api/parent/students')->assertUnauthorized();
    }

    public function test_driver_cannot_access_parent_api(): void
    {
        $this->seedRolesAndPermissions();

        $driver = User::factory()->create(['status' => 'active']);
        $driver->assignRole('Driver');

        Sanctum::actingAs($driver);

        $this->getJson('/api/parent/profile')->assertForbidden();
        $this->getJson('/api/parent/students')->assertForbidden();
        $this->getJson('/api/parent/notifications')->assertForbidden();
    }

    public function test_super_admin_cannot_access_parent_api(): void
    {
        $this->seedRolesAndPermissions();

        $admin = User::factory()->create(['status' => 'active']);
        $admin->assignRole('Super Admin');

        Sanctum::actingAs($admin);

        $this->getJson('/api/parent/profile')->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_view_own_profile(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->makeParent();
        $parent = $this->parentProfile($user);
        $child = $this->makeStudent($parent->school, $parent);

        Sanctum::actingAs($user);

        $this->getJson('/api/parent/profile')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user' => ['id', 'name', 'email', 'status'],
                    'school' => ['id', 'name'],
                    'father_name',
                    'mother_name',
                    'phone',
                    'alternate_phone',
                    'address',
                    'occupation',
                    'children_count',
                ],
            ])
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonPath('data.children_count', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_list_own_students(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Sanctum::actingAs($user);

        $this->getJson('/api/parent/students')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $student->id)
            ->assertJsonPath('data.0.full_name', 'Child One')
            ->assertJsonPath('data.0.bus.id', $bus->id)
            ->assertJsonPath('data.0.bus.bus_number', 'BUS-01');
    }

    public function test_parent_can_view_single_student_with_bus_and_route(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $student->id)
            ->assertJsonPath('data.bus.route.name', 'Main Route')
            ->assertJsonStructure(['data' => ['bus' => ['id', 'bus_number', 'route']]]);
    }

    public function test_parent_cannot_view_another_parents_student_or_related_data(): void
    {
        $this->seedRolesAndPermissions();

        $schoolA = $this->makeSchool();
        $schoolB = $this->makeSchool();
        $user = $this->makeParent($schoolA);
        $otherUser = $this->makeParent($schoolB);

        $otherParent = $this->parentProfile($otherUser);
        $otherRoute = $this->makeRoute($schoolB);
        $otherBus = $this->makeBus($schoolB, $otherRoute);
        $otherStudent = $this->makeStudent($schoolB, $otherParent, $otherBus);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$otherStudent->id}")->assertNotFound();
        $this->getJson("/api/parent/students/{$otherStudent->id}/bus")->assertNotFound();
        $this->getJson("/api/parent/students/{$otherStudent->id}/route")->assertNotFound();
        $this->getJson("/api/parent/students/{$otherStudent->id}/tracking")->assertNotFound();
        $this->getJson("/api/parent/students/{$otherStudent->id}/trip")->assertNotFound();
    }

    public function test_parent_gets_404_for_unknown_student(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->makeParent();

        Sanctum::actingAs($user);

        $this->getJson('/api/parent/students/9999')->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Bus / Route
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_view_child_bus(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/bus")
            ->assertOk()
            ->assertJsonPath('data.id', $bus->id)
            ->assertJsonPath('data.bus_number', 'BUS-01')
            ->assertJsonPath('data.route.name', 'Main Route');
    }

    public function test_parent_gets_404_when_child_has_no_bus(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->makeParent();
        $parent = $this->parentProfile($user);
        $student = $this->makeStudent($parent->school, $parent);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/bus")->assertNotFound();
    }

    public function test_parent_can_view_child_route_with_stops(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        RouteStop::create([
            'route_id' => $route->id,
            'name' => 'Stop A',
            'latitude' => 28.6000,
            'longitude' => 77.2000,
            'stop_order' => 1,
        ]);
        RouteStop::create([
            'route_id' => $route->id,
            'name' => 'Stop B',
            'latitude' => 28.6100,
            'longitude' => 77.2100,
            'stop_order' => 2,
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/route")
            ->assertOk()
            ->assertJsonPath('data.name', 'Main Route')
            ->assertJsonPath('data.start_location', 'Station A')
            ->assertJsonPath('data.end_location', 'School Gate')
            ->assertJsonCount(2, 'data.stops')
            ->assertJsonPath('data.stops.0.name', 'Stop A');
    }

    /*
    |--------------------------------------------------------------------------
    | Tracking (reuses existing FleetMapService / NazarTrack pipeline)
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_view_child_bus_tracking(): void
    {
        Http::fake(['*' => Http::response(['data' => []])]);

        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/tracking")
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'bus' => [
                        'id',
                        'bus_number',
                        'latitude',
                        'longitude',
                        'tracking_status',
                        'next_stop',
                        'eta_minutes',
                    ],
                    'route' => ['id', 'name', 'stops'],
                    'updated_at',
                ],
            ])
            ->assertJsonPath('data.bus.id', $bus->id)
            ->assertJsonPath('data.route.name', 'Main Route');
    }

    /*
    |--------------------------------------------------------------------------
    | Trips (reuses existing Attendance data)
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_view_child_trip_status(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Attendance::create([
            'student_id' => $student->id,
            'bus_id' => $bus->id,
            'trip' => 'home_to_school',
            'date' => now()->toDateString(),
            'check_in_at' => now()->subMinutes(30),
            'marked_by' => null,
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/trip")
            ->assertOk()
            ->assertJsonCount(2, 'data.trips')
            ->assertJsonPath('data.trips.0.trip', 'home_to_school')
            ->assertJsonPath('data.trips.0.status', 'in_progress')
            ->assertJsonPath('data.trips.1.trip', 'school_to_home')
            ->assertJsonPath('data.trips.1.status', 'not_started');
    }

    public function test_parent_can_view_completed_and_not_started_trips(): void
    {
        $this->seedRolesAndPermissions();

        $school = $this->makeSchool();
        $user = $this->makeParent($school);
        $parent = $this->parentProfile($user);
        $route = $this->makeRoute($school);
        $bus = $this->makeBus($school, $route);
        $student = $this->makeStudent($school, $parent, $bus);

        Attendance::create([
            'student_id' => $student->id,
            'bus_id' => $bus->id,
            'trip' => 'home_to_school',
            'date' => now()->toDateString(),
            'check_in_at' => now()->subMinutes(40),
            'check_out_at' => now()->subMinutes(30),
            'marked_by' => null,
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/parent/students/{$student->id}/trip")
            ->assertOk()
            ->assertJsonCount(2, 'data.trips')
            ->assertJsonPath('data.trips.0.status', 'completed')
            ->assertJsonPath('data.trips.1.trip', 'school_to_home')
            ->assertJsonPath('data.trips.1.status', 'not_started');
    }

    /*
    |--------------------------------------------------------------------------
    | Notifications (reuses the existing notifications table)
    |--------------------------------------------------------------------------
    */

    public function test_parent_can_view_and_manage_own_notifications(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->makeParent();

        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Bus is arriving'],
            'read_at' => null,
        ]);
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Child checked in'],
            'read_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/parent/notifications')
            ->assertOk()
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.0.data.message', 'Bus is arriving')
            ->assertJsonStructure(['data' => [['id', 'type', 'data', 'read_at', 'created_at']]]);

        $this->getJson('/api/parent/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.count', 1);

        $unread = $user->unreadNotifications()->first();

        $this->postJson("/api/parent/notifications/{$unread->id}/read")->assertOk();

        $this->getJson('/api/parent/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.count', 0);

        $user->notifications()->first()->update(['read_at' => null]);

        $this->postJson('/api/parent/notifications/read-all')->assertOk();

        $this->getJson('/api/parent/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.count', 0);
    }

    public function test_parent_cannot_mark_another_users_notification_as_read(): void
    {
        $this->seedRolesAndPermissions();

        $user = $this->makeParent();
        $other = $this->makeParent();

        $notification = $other->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Private'],
            'read_at' => null,
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/parent/notifications/{$notification->id}/read")
            ->assertNotFound();
    }
}
