<?php

namespace App\Console\Commands;

use App\Services\BusTrackingService;
use App\Services\NazarTrackService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessLiveTracking extends Command
{
    protected $signature = 'gps:process-live-tracking';

    protected $description = 'Poll the NazarTrack live-tracking API and process bus locations and notifications';

    public function handle(
        NazarTrackService $gps,
        BusTrackingService $tracking,
    ): int {
        try {
            $payload = $gps->getLiveTracking();
        } catch (Exception $e) {
            $this->error('GPS API error: '.$e->getMessage());
            Log::error('Failed to fetch live tracking', [
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }

        $devices = $payload['data'] ?? [];

        foreach ($devices as $device) {
            $tracking->processLocation($device);
        }

        $this->info('Processed '.count($devices).' devices.');

        return self::SUCCESS;
    }
}
