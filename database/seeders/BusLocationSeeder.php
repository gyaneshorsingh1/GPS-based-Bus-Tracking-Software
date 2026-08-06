<?php

namespace Database\Seeders;

use App\Models\BusLocation;
use App\Models\GpsDevice;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusLocationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Default coordinates used when a school has none set.
     */
    private const DEFAULT_LATITUDE = 27.7172;

    private const DEFAULT_LONGITUDE = 85.3240;

    /**
     * Number of location points to generate per device.
     */
    private const POINTS_PER_DEVICE = 30;

    /**
     * Time span (in minutes) the generated history should cover.
     */
    private const SPAN_MINUTES = 180;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = GpsDevice::with('school')->get();

        if ($devices->isEmpty()) {
            $this->command->error('No GPS device found. Run GpsDeviceSeeder before BusLocationSeeder.');

            return;
        }

        DB::transaction(function () use ($devices) {
            foreach ($devices as $device) {
                BusLocation::where('gps_device_id', $device->id)->delete();

                $baseLatitude = $device->school?->latitude ?? self::DEFAULT_LATITUDE;
                $baseLongitude = $device->school?->longitude ?? self::DEFAULT_LONGITUDE;

                $step = intdiv(self::SPAN_MINUTES, self::POINTS_PER_DEVICE);

                $rows = [];

                for ($i = 0; $i < self::POINTS_PER_DEVICE; $i++) {
                    $minutesAgo = (self::POINTS_PER_DEVICE - $i) * $step;

                    $rows[] = [
                        'gps_device_id' => $device->id,
                        'latitude' => $baseLatitude + sin($i / 5) * 0.004,
                        'longitude' => $baseLongitude + cos($i / 7) * 0.004,
                        'speed' => 20 + (($i * 7) % 31),
                        'heading' => ($i * 17) % 360,
                        'altitude' => 1300 + (($i % 3) * 5),
                        'recorded_at' => now()->subMinutes($minutesAgo),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                BusLocation::insert($rows);
            }
        });

        $this->command->info('Bus locations seeded successfully.');
    }
}
