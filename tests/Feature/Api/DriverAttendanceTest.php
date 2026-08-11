<?php

namespace Tests\Feature\Api;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DriverAttendanceTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private Driver $driver;

    private User $driverUser;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Bright Future School',
            'code' => 'SCH-API-1',
            'email' => 'api@brightfuture.com',
            'phone' => '9800000000',
            'address' => 'Kathmandu',
            'status' => 'active',
        ]);

        $this->driverUser = User::factory()->create();
        $this->driver = Driver::create([
            'school_id' => $this->school->id,
            'user_id' => $this->driverUser->id,
            'employee_id' => 'DR-API-1',
            'first_name' => 'Ramesh',
            'last_name' => 'Sharma',
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'phone' => '9800000001',
            'address' => 'Kathmandu',
            'license_number' => 'LIC-API-1',
            'license_type' => 'Bus',
            'license_issue_date' => '2020-01-01',
            'license_expiry_date' => '2030-01-01',
            'joining_date' => '2024-01-01',
            'status' => 'Active',
            'created_by' => $this->driverUser->id,
        ]);

        $this->bus = Bus::create([
            'school_id' => $this->school->id,
            'driver_id' => $this->driver->id,
            'bus_number' => 'API-BUS-1',
            'registration_number' => 'BA API-BUS-1',
            'capacity' => 40,
            'status' => 'Active',
        ]);
    }

    private function makeStudent(array $overrides = []): Student
    {
        $parent = ParentProfile::create([
            'user_id' => $this->driverUser->id,
            'school_id' => $this->school->id,
            'father_name' => 'Hari Bahadur',
            'phone' => '9812345678',
            'address' => 'Kathmandu',
        ]);

        return Student::create(array_merge([
            'school_id' => $this->school->id,
            'parent_id' => $parent->id,
            'bus_id' => $this->bus->id,
            'admission_no' => 'ADM-'.uniqid(),
            'first_name' => 'Sita',
            'last_name' => 'Sharma',
            'date_of_birth' => '2012-05-10',
            'gender' => 'Female',
            'grade' => '5',
            'section' => 'A',
            'roll_no' => '1',
            'pickup_location' => 'Chabahil',
            'drop_location' => 'School',
            'is_active' => true,
        ], $overrides));
    }

    public function test_driver_can_list_students_of_assigned_bus(): void
    {
        $this->makeStudent();
        $this->makeStudent(['first_name' => 'Rita', 'roll_no' => '2']);

        Sanctum::actingAs($this->driverUser);

        $response = $this->getJson('/api/v1/driver/attendances?bus_id='.$this->bus->id);

        $response->assertOk()
            ->assertJsonPath('data.total_students', 2)
            ->assertJsonCount(2, 'data.students')
            ->assertJsonPath('data.students.0.full_name', 'Sita Sharma')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'bus' => ['id', 'bus_number', 'registration_number', 'status'],
                    'total_students',
                    'students' => [
                        '*' => [
                            'id',
                            'admission_no',
                            'first_name',
                            'last_name',
                            'full_name',
                            'gender',
                            'grade',
                            'section',
                            'roll_no',
                            'photo',
                            'pickup_location',
                            'drop_location',
                            'parent',
                        ],
                    ],
                ],
            ]);
    }

    public function test_bus_id_is_required(): void
    {
        Sanctum::actingAs($this->driverUser);

        $this->getJson('/api/v1/driver/attendances')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('bus_id');
    }

    public function test_cannot_view_another_drivers_bus(): void
    {
        $otherDriverUser = User::factory()->create();
        $otherDriver = Driver::create([
            'school_id' => $this->school->id,
            'user_id' => $otherDriverUser->id,
            'employee_id' => 'DR-API-2',
            'first_name' => 'Other',
            'last_name' => 'Driver',
            'gender' => 'Male',
            'date_of_birth' => '1985-01-01',
            'phone' => '9800000002',
            'address' => 'Kathmandu',
            'license_number' => 'LIC-API-2',
            'license_type' => 'Bus',
            'license_issue_date' => '2020-01-01',
            'license_expiry_date' => '2030-01-01',
            'joining_date' => '2024-01-01',
            'status' => 'Active',
            'created_by' => $otherDriverUser->id,
        ]);

        $otherBus = Bus::create([
            'school_id' => $this->school->id,
            'driver_id' => $otherDriver->id,
            'bus_number' => 'API-BUS-2',
            'registration_number' => 'BA API-BUS-2',
            'capacity' => 40,
            'status' => 'Active',
        ]);

        Sanctum::actingAs($this->driverUser);

        $this->getJson('/api/v1/driver/attendances?bus_id='.$otherBus->id)
            ->assertNotFound();
    }

    public function test_user_without_driver_profile_gets_404(): void
    {
        $parentUser = User::factory()->create();

        Sanctum::actingAs($parentUser);

        $this->getJson('/api/v1/driver/attendances?bus_id='.$this->bus->id)
            ->assertNotFound();
    }
}
