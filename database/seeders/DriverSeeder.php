<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DriverSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Sample drivers with Nepal-related data.
     *
     * @var array<int, array<string, mixed>>
     */
    private const DRIVERS = [
        [
            'first_name' => 'Ramesh',
            'last_name' => 'Shrestha',
            'gender' => 'Male',
            'date_of_birth' => '1985-03-12',
            'phone' => '+977-9841122334',
            'address' => 'Kalanki, Ward 14',
            'city' => 'Kathmandu',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44600',
            'license_type' => 'Bus',
            'license_issue_date' => '2018-06-10',
            'license_expiry_date' => '2028-06-10',
            'experience_years' => 12,
            'joining_date' => '2021-02-15',
            'status' => 'Active',
            'emergency_contact_name' => 'Sita Shrestha',
            'emergency_contact_phone' => '+977-9841223344',
            'remarks' => 'Experienced on hilly routes.',
        ],
        [
            'first_name' => 'Sita',
            'last_name' => 'Gurung',
            'gender' => 'Female',
            'date_of_birth' => '1990-07-25',
            'phone' => '+977-9803344556',
            'address' => 'Chabahil, Ward 7',
            'city' => 'Kathmandu',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44600',
            'license_type' => 'Bus',
            'license_issue_date' => '2019-03-22',
            'license_expiry_date' => '2029-03-22',
            'experience_years' => 8,
            'joining_date' => '2022-08-01',
            'status' => 'Active',
            'emergency_contact_name' => 'Krishna Gurung',
            'emergency_contact_phone' => '+977-9803445566',
            'remarks' => 'Punctual and safety conscious.',
        ],
        [
            'first_name' => 'Krishna',
            'last_name' => 'Tamang',
            'gender' => 'Male',
            'date_of_birth' => '1988-11-02',
            'phone' => '+977-9855566778',
            'address' => 'Gwarko, Ward 10',
            'city' => 'Lalitpur',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44700',
            'license_type' => 'Bus',
            'license_issue_date' => '2017-01-15',
            'license_expiry_date' => '2027-01-15',
            'experience_years' => 15,
            'joining_date' => '2020-05-20',
            'status' => 'Active',
            'emergency_contact_name' => 'Maya Tamang',
            'emergency_contact_phone' => '+977-9855667788',
            'remarks' => 'Drives the Gwarko morning loop.',
        ],
        [
            'first_name' => 'Bikash',
            'last_name' => 'Thapa',
            'gender' => 'Male',
            'date_of_birth' => '1983-01-19',
            'phone' => '+977-9817788990',
            'address' => 'Baneshwor, Ward 4',
            'city' => 'Kathmandu',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44600',
            'license_type' => 'Bus',
            'license_issue_date' => '2016-09-08',
            'license_expiry_date' => '2026-09-08',
            'experience_years' => 18,
            'joining_date' => '2019-11-11',
            'status' => 'Active',
            'emergency_contact_name' => 'Anita Thapa',
            'emergency_contact_phone' => '+977-9817889900',
            'remarks' => 'Veteran driver on the Baneshwor shuttle.',
        ],
        [
            'first_name' => 'Pramod',
            'last_name' => 'Maharjan',
            'gender' => 'Male',
            'date_of_birth' => '1992-05-08',
            'phone' => '+977-9866001122',
            'address' => 'Lagankhel, Ward 11',
            'city' => 'Lalitpur',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44700',
            'license_type' => 'Bus',
            'license_issue_date' => '2020-04-18',
            'license_expiry_date' => '2030-04-18',
            'experience_years' => 6,
            'joining_date' => '2023-01-02',
            'status' => 'Active',
            'emergency_contact_name' => 'Nisha Maharjan',
            'emergency_contact_phone' => '+977-9866112233',
            'remarks' => 'Handles evening drop runs.',
        ],
        [
            'first_name' => 'Sunita',
            'last_name' => 'Karki',
            'gender' => 'Female',
            'date_of_birth' => '1994-09-17',
            'phone' => '+977-9822334455',
            'address' => 'Balaju, Ward 16',
            'city' => 'Kathmandu',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44600',
            'license_type' => 'Bus',
            'license_issue_date' => '2021-07-30',
            'license_expiry_date' => '2031-07-30',
            'experience_years' => 5,
            'joining_date' => '2023-06-15',
            'status' => 'Active',
            'emergency_contact_name' => 'Raj Karki',
            'emergency_contact_phone' => '+977-9822445566',
            'remarks' => 'Newer member of the transport team.',
        ],
        [
            'first_name' => 'Dipesh',
            'last_name' => 'Rai',
            'gender' => 'Male',
            'date_of_birth' => '1990-02-28',
            'phone' => '+977-9844556677',
            'address' => 'Suryabinayak, Ward 3',
            'city' => 'Bhaktapur',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44800',
            'license_type' => 'Bus',
            'license_issue_date' => '2019-10-05',
            'license_expiry_date' => '2029-10-05',
            'experience_years' => 9,
            'joining_date' => '2022-03-10',
            'status' => 'Active',
            'emergency_contact_name' => 'Puja Rai',
            'emergency_contact_phone' => '+977-9844667788',
            'remarks' => 'Assigned to the airport pickups.',
        ],
        [
            'first_name' => 'Anil',
            'last_name' => 'KC',
            'gender' => 'Male',
            'date_of_birth' => '1987-06-14',
            'phone' => '+977-9801223345',
            'address' => 'Pulchowk, Ward 9',
            'city' => 'Lalitpur',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'postal_code' => '44700',
            'license_type' => 'Bus',
            'license_issue_date' => '2018-02-20',
            'license_expiry_date' => '2028-02-20',
            'experience_years' => 11,
            'joining_date' => '2021-09-01',
            'status' => 'Suspended',
            'emergency_contact_name' => 'Rita KC',
            'emergency_contact_phone' => '+977-9801334455',
            'remarks' => 'Pending license renewal.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::where('email', 'superadmin@example.com')->first() ?? User::first();

        if (! $creator) {
            $this->command->error('No user found. Run UserSeeder before DriverSeeder.');

            return;
        }

        $driverRole = Role::where('name', 'Driver')->first();

        $schools = School::orderBy('id')->get();

        if ($schools->isEmpty()) {
            $this->command->error('No school found. Run SchoolSeeder before DriverSeeder.');

            return;
        }

        DB::transaction(function () use ($creator, $driverRole, $schools) {
            foreach (self::DRIVERS as $index => $data) {
                $employeeId = 'DRV-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);

                $licenseNumber = 'DL-'.str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT);

                $email = Str::slug($data['first_name'].'-'.$data['last_name']).'@gpsbustrack.com';

                $school = $schools->get($index % $schools->count());

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $data['first_name'].' '.$data['last_name'],
                        'password' => Hash::make('password'),
                        'school_id' => $school->id,
                    ]
                );

                if ($driverRole) {
                    $user->syncRoles([$driverRole]);
                }

                Driver::updateOrCreate(
                    ['employee_id' => $employeeId],
                    [
                        'school_id' => $school->id,
                        'user_id' => $user->id,
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'gender' => $data['gender'],
                        'date_of_birth' => $data['date_of_birth'],
                        'phone' => $data['phone'],
                        'email' => $email,
                        'address' => $data['address'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'country' => $data['country'],
                        'postal_code' => $data['postal_code'],
                        'license_number' => $licenseNumber,
                        'license_type' => $data['license_type'],
                        'license_issue_date' => $data['license_issue_date'],
                        'license_expiry_date' => $data['license_expiry_date'],
                        'experience_years' => $data['experience_years'],
                        'joining_date' => $data['joining_date'],
                        'status' => $data['status'],
                        'emergency_contact_name' => $data['emergency_contact_name'],
                        'emergency_contact_phone' => $data['emergency_contact_phone'],
                        'remarks' => $data['remarks'],
                        'created_by' => $creator->id,
                    ]
                );
            }
        });

        $this->command->info('Drivers seeded successfully.');
    }
}
