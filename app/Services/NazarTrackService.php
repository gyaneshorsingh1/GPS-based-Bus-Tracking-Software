<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class NazarTrackService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('gps.base_url'), '/');
        $this->apiKey = config('gps.api_key');
        $this->timeout = config('gps.timeout', 10);
        $this->cacheTtl = config('gps.cache_ttl', 30);
    }

    /**
     * Get all live GPS devices.
     */
    public function getLiveTracking(): array
    {
        return Cache::remember(
            'gps_live_tracking',
            now()->addSeconds($this->cacheTtl),
            function () {

                $response = Http::timeout($this->timeout)
                    ->withToken($this->apiKey)
                    ->acceptJson()
                    ->withoutVerifying()
                    ->get($this->baseUrl . '/api/ext/live-tracking');

                if (! $response->successful()) {
                    throw new Exception(
                        'GPS API Error: ' .
                            $response->status() .
                            ' - ' .
                            $response->body()
                    );
                }

                return $response->json();
            }
        );
    }

    /**
     * Find a GPS device by its IMEI.
     */
    public function findDeviceByImei(string $imei): ?array
    {
        $response = $this->getLiveTracking();

        if (! isset($response['data'])) {
            return null;
        }

        foreach ($response['data'] as $device) {

            if (($device['imei'] ?? null) === $imei) {
                return $device;
            }
        }

        return null;
    }



    /**
     * Get live GPS data for a Bus model.
     */
    /**
 * Get live GPS data for a Bus model.
 */
public function getBusLocation(\App\Models\Bus $bus): ?array
{
    if (empty($bus->gps_device_id)) {
        return null;
    }

    return $this->findDeviceByImei($bus->gps_device_id);
}

}
