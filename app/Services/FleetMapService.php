<?php

namespace App\Services;

use App\Models\Bus;
use App\Models\BusLocation;
use App\Models\Route;
use App\Models\RouteStop;
use App\Models\School;
use Illuminate\Support\Collection;

/**
 * Builds the data payload used by the fleet monitoring map.
 *
 * The payload is intentionally plain PHP arrays (no model instances) so it can be
 * embedded directly into a Blade view with @json and re-serialized by a JSON
 * endpoint without any mapping differences.
 *
 * When a real GPS provider API is connected later, only the location source in
 * this service needs to change (e.g. an API client instead of the BusLocation
 * table) — the payload shape and the map view stay the same.
 */
class FleetMapService
{
    /** A location is considered live when recorded within this many minutes. */
    public const STALE_MINUTES = 10;

    /** A live bus is considered stopped when speed is at or below this (km/h). */
    public const STOPPED_SPEED_KPH = 3;

    /** Distance (km) within which a stopped bus is treated as "Arrived" at a stop. */
    public const ARRIVED_RADIUS_KM = 0.25;

    /**
     * Latest BusLocation per GPS device, optionally restricted to a set of bus ids.
     *
     * This is the shared "latest location per device" query used by the bus
     * location page, the driver dashboard and the fleet map.
     *
     * @param  Collection<int, int>|null  $busIds  Restrict to these bus ids (null = all devices).
     * @param  array  $with  Relations to eager load on each BusLocation.
     */
    public function latestLocationsByDevice(?Collection $busIds = null, array $with = ['gpsDevice']): Collection
    {
        if ($busIds !== null && $busIds->isEmpty()) {
            return collect();
        }

        $latestPerDevice = BusLocation::select('gps_device_id')
            ->selectRaw('MAX(recorded_at) as last_recorded_at')
            ->groupBy('gps_device_id');

        return BusLocation::query()
            ->joinSub($latestPerDevice, 'latest', function ($join) {
                $join->on('bus_locations.gps_device_id', '=', 'latest.gps_device_id')
                    ->on('bus_locations.recorded_at', '=', 'latest.last_recorded_at');
            })
            ->with($with)
            ->when($busIds !== null, fn ($query) => $query
                ->whereHas('gpsDevice.bus', fn ($bus) => $bus->whereIn('id', $busIds)))
            ->orderByDesc('bus_locations.recorded_at')
            ->get();
    }

    /**
     * Build the full fleet map payload for a school.
     *
     * @param  int|null  $schoolId  Null = all schools (used by super admin contexts).
     */
    public function forSchool(?int $schoolId): array
    {
        $busQuery = Bus::query()->with(['driver', 'route.stops', 'gpsDevice']);

        if ($schoolId) {
            $busQuery->where('school_id', $schoolId);
        }

        $buses = $busQuery->get();

        $locations = $this->latestLocationsByDevice($buses->pluck('id'), ['gpsDevice.bus']);

        $locationsByBus = $locations
            ->filter(fn (BusLocation $location) => $location->gpsDevice?->bus_id)
            ->keyBy(fn (BusLocation $location) => $location->gpsDevice->bus_id);

        $busArrays = $buses->map(
            fn (Bus $bus) => $this->busToArray($bus, $locationsByBus->get($bus->id))
        )->values();

        $routeQuery = Route::query()->with('stops');

        if ($schoolId) {
            $routeQuery->where('school_id', $schoolId);
        }

        $routes = $routeQuery->orderBy('name')->get();

        return [
            'buses' => $busArrays->all(),
            'routes' => $routes->map(fn (Route $route) => [
                'id' => $route->id,
                'name' => $route->name,
                'route_code' => $route->route_code,
                'start_location' => $route->start_location,
                'end_location' => $route->end_location,
                'stops' => $route->stops->map(fn (RouteStop $stop) => [
                    'id' => $stop->id,
                    'name' => $stop->name,
                    'latitude' => $stop->latitude,
                    'longitude' => $stop->longitude,
                    'stop_order' => $stop->stop_order,
                ])->values()->all(),
            ])->values()->all(),
            'summary' => [
                'total' => $buses->count(),
                'active' => $buses->where('status', 'Active')->count(),
                'maintenance' => $buses->where('status', 'Maintenance')->count(),
                'inactive' => $buses->where('status', 'Inactive')->count(),
                'moving' => $busArrays->where('tracking_status', 'Moving')->count(),
                'stopped' => $busArrays->whereIn('tracking_status', ['Stopped', 'Arrived'])->count(),
                'routes_running' => $routes->where('is_active', true)->count(),
            ],
            'school' => $schoolId
                ? School::find($schoolId)?->only(['name', 'latitude', 'longitude'])
                : null,
            'updated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Serialize a bus (with its latest location) into the map payload shape.
     */
    private function busToArray(Bus $bus, ?BusLocation $location): array
    {
        $nearestStop = $location ? $this->nearestStop($bus, $location) : null;
        $status = $this->trackingStatus($bus, $location, $nearestStop);

        return [
            'id' => $bus->id,
            'bus_number' => $bus->bus_number,
            'registration_number' => $bus->registration_number,
            'status' => $bus->status,
            'driver_name' => $bus->driver?->full_name,
            'route_id' => $bus->route_id,
            'route_name' => $bus->route?->name,
            'latitude' => $location?->latitude,
            'longitude' => $location?->longitude,
            'speed' => $location?->speed ?? 0,
            'heading' => $location?->heading,
            'recorded_at' => $location?->recorded_at?->toIso8601String(),
            'tracking_status' => $status,
            'next_stop' => $nearestStop['stop']?->name ?? null,
            'eta_minutes' => $this->etaMinutes($status, $location, $nearestStop),
        ];
    }

    /**
     * Resolve the per-bus tracking status.
     *
     * - Offline: no fresh location.
     * - Arrived: stopped within ARRIVED_RADIUS_KM of a configured stop.
     * - Moving:  fresh location with speed above the stopped threshold.
     * - Stopped: fresh location, but effectively stationary.
     */
    private function trackingStatus(Bus $bus, ?BusLocation $location, ?array $nearestStop): string
    {
        if (! $this->isLive($location)) {
            return 'Offline';
        }

        $stopped = ($location->speed ?? 0) <= self::STOPPED_SPEED_KPH;

        if ($stopped && $nearestStop && $nearestStop['distance_km'] <= self::ARRIVED_RADIUS_KM) {
            return 'Arrived';
        }

        return $stopped ? 'Stopped' : 'Moving';
    }

    /**
     * Find the nearest configured stop to the current bus position.
     *
     * @return array{stop: RouteStop|null, distance_km: float|null}|null
     */
    private function nearestStop(Bus $bus, BusLocation $location): ?array
    {
        if (! $bus->route?->stops || $bus->route->stops->isEmpty()) {
            return null;
        }

        $nearest = null;
        $nearestDistance = PHP_FLOAT_MAX;

        foreach ($bus->route->stops as $stop) {
            $distance = $this->haversineKm(
                $location->latitude,
                $location->longitude,
                (float) $stop->latitude,
                (float) $stop->longitude,
            );

            if ($distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearest = $stop;
            }
        }

        if (! $nearest) {
            return null;
        }

        return [
            'stop' => $nearest,
            'distance_km' => $nearestDistance,
        ];
    }

    /**
     * Estimated minutes to reach the next stop (based on current speed).
     */
    private function etaMinutes(string $status, ?BusLocation $location, ?array $nearestStop): ?float
    {
        if ($status !== 'Moving' || ! $nearestStop || ! $location || $location->speed <= 0) {
            return null;
        }

        return round(($nearestStop['distance_km'] / $location->speed) * 60, 1);
    }

    /**
     * Whether the latest location is fresh enough to be treated as live.
     */
    private function isLive(?BusLocation $location): bool
    {
        return $location
            && $location->latitude
            && $location->longitude
            && $location->recorded_at?->gt(now()->subMinutes(self::STALE_MINUTES));
    }

    /**
     * Haversine distance between two coordinates (km).
     */
    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
