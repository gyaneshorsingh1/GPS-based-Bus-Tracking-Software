<?php

namespace Database\Seeders;

use App\Models\BusLocation;
use App\Models\GpsDevice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusLocationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Distinct Biratnagar, Nepal routes. Each entry is a [startLat, startLng, endLat, endLng]
     * pair, and each device gets its own route (cycling when there are more devices than routes).
     */
    private const ROUTES = [
        [26.4525, 87.2718, 26.4815, 87.2640], // City Center <-> Airport
        [26.4117, 87.2705, 26.4525, 87.2718], // Jogbani Border <-> City Center
        [26.4395, 87.3120, 26.4525, 87.2718], // Rangeli <-> City Center
        [26.4815, 87.2640, 26.5060, 87.2330], // Airport <-> Biratnagar West
        [26.4865, 87.3000, 26.4395, 87.3120], // Eastside <-> Rangeli
        [26.4520, 87.2310, 26.4525, 87.2718], // Biratnagar Southwest <-> City Center
        [26.5210, 87.2780, 26.4815, 87.2640], // Biratnagar North <-> Airport
        [26.4560, 87.2900, 26.4400, 87.2600], // East <-> Jogbani Road
    ];

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
        $devices = GpsDevice::get();

        if ($devices->isEmpty()) {
            $this->command->error('No GPS device found. Run GpsDeviceSeeder before BusLocationSeeder.');

            return;
        }

        DB::transaction(function () use ($devices) {
            foreach ($devices as $index => $device) {
                BusLocation::where('gps_device_id', $device->id)->delete();

                $route = self::ROUTES[$index % count(self::ROUTES)];

                $pointsPerLeg = intdiv(self::POINTS_PER_DEVICE, 2);
                $step = intdiv(self::SPAN_MINUTES, self::POINTS_PER_DEVICE);

                $rows = [];

                for ($i = 0; $i < self::POINTS_PER_DEVICE; $i++) {
                    $isReturnLeg = $i >= $pointsPerLeg;

                    if ($isReturnLeg) {
                        $from = [$route[2], $route[3]];
                        $to = [$route[0], $route[1]];
                        $t = ($i - $pointsPerLeg) / ($pointsPerLeg - 1);
                    } else {
                        $from = [$route[0], $route[1]];
                        $to = [$route[2], $route[3]];
                        $t = $i / ($pointsPerLeg - 1);
                    }

                    $latitude = $from[0] + ($to[0] - $from[0]) * $t;
                    $longitude = $from[1] + ($to[1] - $from[1]) * $t;
                    $heading = rad2deg(atan2($to[1] - $from[1], $to[0] - $from[0]));

                    $minutesAgo = (self::POINTS_PER_DEVICE - $i) * $step;

                    $rows[] = [
                        'gps_device_id' => $device->id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'speed' => 15 + (($i * 7) % 31),
                        'heading' => $heading,
                        'altitude' => 70 + (($i % 3) * 5),
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
