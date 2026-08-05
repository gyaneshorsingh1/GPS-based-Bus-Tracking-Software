<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createSchool(string $code = 'SCH001'): School
    {
        return School::create([
            'name' => "School {$code}",
            'code' => $code,
            'email' => "admin{$code}@example.com",
            'phone' => '9800000000',
            'address' => 'Kathmandu',
            'principal_name' => 'Principal Name',
            'status' => 'active',
        ]);
    }

    private function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'school_id' => null,
        ], $attributes));
    }

    private function createDriver(School $school, User $user, string $suffix = '001'): Driver
    {
        return Driver::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'employee_id' => "DR{$suffix}",
            'first_name' => 'Ramesh',
            'last_name' => "Sharma{$suffix}",
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'phone' => '9800000001',
            'address' => 'Kathmandu',
            'license_number' => "LIC-{$suffix}",
            'license_type' => 'Bus',
            'license_issue_date' => '2020-01-01',
            'license_expiry_date' => '2030-01-01',
            'joining_date' => '2024-01-01',
            'status' => 'Active',
            'created_by' => $user->id,
        ]);
    }

    private function createBus(School $school, string $busNumber, ?Driver $driver = null): Bus
    {
        return Bus::create([
            'school_id' => $school->id,
            'bus_number' => $busNumber,
            'registration_number' => "BA {$busNumber}",
            'capacity' => 40,
            'status' => 'Active',
            'driver_id' => $driver?->id,
        ]);
    }

    private function createStudent(School $school, Bus $bus, string $admissionNo, ?ParentProfile $parent = null): Student
    {
        $parent ??= $this->createParent($school);

        return Student::create([
            'school_id' => $school->id,
            'parent_id' => $parent->id,
            'bus_id' => $bus->id,
            'admission_no' => $admissionNo,
            'first_name' => 'Aarav',
            'last_name' => 'Shrestha',
            'date_of_birth' => '2014-01-01',
            'gender' => 'Male',
            'grade' => '5',
            'section' => 'A',
            'roll_no' => '1',
            'pickup_location' => 'Baneshwor',
            'drop_location' => 'School',
            'is_active' => true,
        ]);
    }

    private function createParent(School $school): ParentProfile
    {
        $user = $this->createUser();
        $user->assignRole('Parent');

        return ParentProfile::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'father_name' => 'Bishal Shrestha',
            'phone' => '9800000002',
            'address' => 'Kathmandu',
        ]);
    }

    public function test_school_admin_can_view_attendance_for_own_school_buses(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH100');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-100');

        $this->actingAs($admin)->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('BUS-100');

        $this->actingAs($admin)->get(route('attendance.buses.show', $bus))
            ->assertOk()
            ->assertSee('BUS-100');
    }

    public function test_school_admin_cannot_access_another_schools_bus(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH101');
        $admin->school_id = $school->id;
        $admin->save();

        $otherSchool = $this->createSchool('SCH102');
        $bus = $this->createBus($otherSchool, 'BUS-101');

        $this->actingAs($admin)->get(route('attendance.buses.show', $bus))
            ->assertForbidden();
    }

    public function test_super_admin_sees_all_buses(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $superAdmin = $this->createUser();
        $superAdmin->assignRole('Super Admin');

        $school = $this->createSchool('SCH103');
        $bus = $this->createBus($school, 'BUS-102');

        $this->actingAs($superAdmin)->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('BUS-102');

        $this->actingAs($superAdmin)->get(route('attendance.buses.show', $bus))
            ->assertOk();
    }

    public function test_driver_only_sees_their_own_bus(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $driverUser = $this->createUser();
        $driverUser->assignRole('Driver');

        $school = $this->createSchool('SCH104');
        $driver = $this->createDriver($school, $driverUser, '104');

        $ownBus = $this->createBus($school, 'BUS-103', $driver);
        $otherBus = $this->createBus($school, 'BUS-104');

        $this->actingAs($driverUser)->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('BUS-103')
            ->assertDontSee('BUS-104');

        $this->actingAs($driverUser)->get(route('attendance.buses.show', $otherBus))
            ->assertForbidden();
    }

    public function test_parent_cannot_access_attendance(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $parent = $this->createUser();
        $parent->assignRole('Parent');

        $this->actingAs($parent)->get(route('attendance.index'))
            ->assertForbidden();
    }

    public function test_school_admin_can_check_student_in(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH105');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-105');
        $student = $this->createStudent($school, $bus, 'ADM105');

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertRedirect(route('attendance.buses.show', [
                'bus' => $bus,
                'date' => now()->toDateString(),
            ]));

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'bus_id' => $bus->id,
        ]);

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', now()->toDateString())
            ->first();
        $this->assertNotNull($attendance->check_in_at);
        $this->assertNull($attendance->check_out_at);
    }

    public function test_check_out_requires_a_prior_check_in(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH106');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-106');
        $student = $this->createStudent($school, $bus, 'ADM106');

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_out',
            ])
            ->assertSessionHasErrors('check_out');

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_double_check_in_is_rejected(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH107');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-107');
        $student = $this->createStudent($school, $bus, 'ADM107');

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertSessionHasErrors('check_in');

        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_double_check_out_is_rejected(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH108');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-108');
        $student = $this->createStudent($school, $bus, 'ADM108');

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_out',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_out',
            ])
            ->assertSessionHasErrors('check_out');
    }

    public function test_student_from_another_bus_is_rejected(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH109');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-109');
        $otherBus = $this->createBus($school, 'BUS-110');
        $student = $this->createStudent($school, $otherBus, 'ADM109');

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_index_only_lists_active_buses(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $superAdmin = $this->createUser(['name' => 'Super Admin User']);
        $superAdmin->assignRole('Super Admin');

        $school = $this->createSchool('SCH110');
        $activeBus = $this->createBus($school, 'BUS-111');
        $maintenanceBus = Bus::create([
            'school_id' => $school->id,
            'bus_number' => 'BUS-112',
            'registration_number' => 'BA BUS-112',
            'capacity' => 40,
            'status' => 'Maintenance',
        ]);

        $this->actingAs($superAdmin)->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('BUS-111')
            ->assertDontSee('BUS-112');
    }

    public function test_attendance_is_blocked_for_non_active_buses(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH111');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = Bus::create([
            'school_id' => $school->id,
            'bus_number' => 'BUS-113',
            'registration_number' => 'BA BUS-113',
            'capacity' => 40,
            'status' => 'Inactive',
        ]);
        $student = $this->createStudent($school, $bus, 'ADM110');

        $this->actingAs($admin)->get(route('attendance.buses.show', $bus))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('attendance.mark', ['bus' => $bus, 'student' => $student]), [
                'action' => 'check_in',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_school_admin_can_view_bus_attendance_history(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser(['name' => 'Admin Who Marked']);
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH112');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-114');
        $student = $this->createStudent($school, $bus, 'ADM111');

        Attendance::create([
            'student_id' => $student->id,
            'bus_id' => $bus->id,
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'marked_by' => $admin->id,
        ]);

        $this->actingAs($admin)->get(route('attendance.buses.history', $bus))
            ->assertOk()
            ->assertSee($student->full_name)
            ->assertSee('ADM111')
            ->assertSee('Admin Who Marked');
    }

    public function test_school_admin_cannot_view_another_schools_bus_history(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH113');
        $admin->school_id = $school->id;
        $admin->save();

        $otherSchool = $this->createSchool('SCH114');
        $bus = $this->createBus($otherSchool, 'BUS-115');

        $this->actingAs($admin)->get(route('attendance.buses.history', $bus))
            ->assertForbidden();
    }

    public function test_driver_can_view_own_bus_history_but_not_others(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $driverUser = $this->createUser();
        $driverUser->assignRole('Driver');

        $school = $this->createSchool('SCH115');
        $driver = $this->createDriver($school, $driverUser, '115');

        $ownBus = $this->createBus($school, 'BUS-116', $driver);
        $otherBus = $this->createBus($school, 'BUS-117');

        $this->actingAs($driverUser)->get(route('attendance.buses.history', $ownBus))
            ->assertOk();

        $this->actingAs($driverUser)->get(route('attendance.buses.history', $otherBus))
            ->assertForbidden();
    }

    public function test_history_respects_date_range_filter(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH116');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-118');

        $studentA = $this->createStudent($school, $bus, 'ADM112');
        $studentB = $this->createStudent($school, $bus, 'ADM113');

        Attendance::create([
            'student_id' => $studentA->id,
            'bus_id' => $bus->id,
            'date' => now()->toDateString(),
            'check_in_at' => now(),
            'marked_by' => $admin->id,
        ]);

        Attendance::create([
            'student_id' => $studentB->id,
            'bus_id' => $bus->id,
            'date' => now()->subDays(40)->toDateString(),
            'check_in_at' => now()->subDays(40),
            'marked_by' => $admin->id,
        ]);

        $this->actingAs($admin)->get(route('attendance.buses.history', [
            'bus' => $bus,
            'from' => now()->subDays(10)->toDateString(),
            'to' => now()->toDateString(),
        ]))
            ->assertOk()
            ->assertSee('ADM112')
            ->assertDontSee('ADM113');
    }

    public function test_history_shows_empty_state(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $admin = $this->createUser();
        $admin->assignRole('School Admin');

        $school = $this->createSchool('SCH117');
        $admin->school_id = $school->id;
        $admin->save();

        $bus = $this->createBus($school, 'BUS-119');

        $this->actingAs($admin)->get(route('attendance.buses.history', $bus))
            ->assertOk()
            ->assertSee('No attendance records found for this period.');
    }
}
