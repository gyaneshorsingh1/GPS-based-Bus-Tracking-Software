<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\GpsDevice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GpsDeviceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Map a bus status to the corresponding device status.
     */
    private const STATUS_MAP = [
        'Active' => 'active',
        'Maintenance' => 'maintenance',
        'Inactive' => 'inactive',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = Bus::orderBy('id')->get();

        if ($buses->isEmpty()) {
            $this->command->error('No bus found. Run BusSeeder before GpsDeviceSeeder.');

            return;
        }

        DB::transaction(function () use ($buses) {
            foreach ($buses as $bus) {
                $deviceImei = '3569'.str_pad((string) $bus->id, 11, '0', STR_PAD_LEFT);

                GpsDevice::updateOrCreate(
                    ['device_imei' => $deviceImei],
                    [
                        'school_id' => $bus->school_id,
                        'bus_id' => $bus->id,
                        'device_name' => $bus->gps_device_id ?? 'GPS-'.$bus->bus_number,
                        'sim_number' => '+977-98'.str_pad((string) (6000000 + $bus->id), 7, '0', STR_PAD_LEFT),
                        'status' => self::STATUS_MAP[$bus->status] ?? 'offline',
                        'installed_at' => $bus->created_at?->toDateString() ?? '2024-05-01',
                        'notes' => "GPS device installed on {$bus->bus_number}.",
                    ]
                );
            }
        });

        $this->command->info('GPS devices seeded successfully.');
    }
}
