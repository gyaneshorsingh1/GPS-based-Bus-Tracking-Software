<?php
namespace App\Http\Controllers\liveTracking;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusLocation;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Services\NazarTrackService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LiveTrackingController extends Controller
{
    public function index(Request $request, NazarTrackService $nazarTrack)
    {
        $user = Auth::user();

        $allowedBusIds = $this->scope();

        $buses = Bus::query()
            ->with(['driver', 'route.stops', 'school'])
            ->when($allowedBusIds !== null, fn ($query) => $query->whereIn('id', $allowedBusIds))
            ->get();

        $assetsByImei = $this->assetsByImei($nazarTrack);

        if ($user->hasRole('Parent')) {
            $parent = ParentProfile::where('user_id', $user->id)->first();

            $children = $parent
                ? $parent->children()->with(['bus.route.stops', 'bus.driver', 'bus.school'])->get()
                : collect();

            $selectedChildId = $request->query('child_id');
            $selectedChild = $children->firstWhere('id', (int) $selectedChildId)
                ?? $children->firstWhere('bus_id', '!=', null)
                ?? $children->first();

            $bus = $selectedChild?->bus;
            $route = $bus?->route;

            return view('liveTracking.view', [
                'isParent' => true,
                'children' => $children,
                'selectedChild' => $selectedChild,
                'buses' => collect(),
                'selectedBus' => $bus,
                'bus' => $bus,
                'route' => $route,
                'telemetry' => $bus ? $this->telemetry($bus, $assetsByImei) : null,
                'liveCount' => $assetsByImei->count(),
            ]);
        }

        $selectedBusId = $request->query('bus_id');
        $selectedBus = $buses->firstWhere('id', (int) $selectedBusId) ?? $buses->first();

        $bus = $selectedBus;
        $route = $bus?->route;

        $fleet = $this->fleetPayload($buses, $assetsByImei);

        return view('liveTracking.view', [
            'isParent' => false,
            'children' => collect(),
            'selectedChild' => null,
            'buses' => $buses,
            'selectedBus' => $bus,
            'bus' => $bus,
            'route' => $route,
            'telemetry' => $bus ? $this->telemetry($bus, $assetsByImei) : null,
            'fleet' => $fleet,
            'fleetStats' => $this->fleetStats($fleet),
            'liveCount' => $assetsByImei->count(),
        ]);
    }

    public function fleet(Request $request, NazarTrackService $nazarTrack)
    {
        $allowedBusIds = $this->scope();

        $buses = Bus::query()
            ->with(['driver', 'route.stops', 'school'])
            ->when($allowedBusIds !== null, fn ($query) => $query->whereIn('id', $allowedBusIds))
            ->get();

        $assetsByImei = $this->assetsByImei($nazarTrack);

        $fleet = $this->fleetPayload($buses, $assetsByImei);

        return response()->json([
            'fleet' => $fleet,
            'stats' => $this->fleetStats($fleet),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function asset(Request $request, NazarTrackService $nazarTrack)
    {
        $busId = (int) $request->query('bus_id');

        $allowedBusIds = $this->scope();

        $bus = Bus::query()
            ->when($allowedBusIds !== null, fn ($query) => $query->whereIn('id', $allowedBusIds))
            ->find($busId);

        if (! $bus) {
            return response()->json(['error' => 'Bus not found.'], 404);
        }

        $assetsByImei = $this->assetsByImei($nazarTrack);

        return response()->json([
            'bus_id' => $bus->id,
            'bus_number' => $bus->bus_number,
            'has_route' => (bool) $bus->route_id,
            'telemetry' => $this->telemetry($bus, $assetsByImei),
        ]);
    }

    /**
     * Bus ids the authenticated user is allowed to track, or null for all.
     */
    private function scope(): ?Collection
    {
        $user = Auth::user();

        if ($user->hasRole('Parent')) {
            $parent = ParentProfile::where('user_id', $user->id)->first();

            return $parent ? $parent->children()->pluck('bus_id')->filter() : collect();
        }

        if ($user->hasRole('Driver')) {
            $driver = Driver::where('user_id', $user->id)->first();

            return $driver ? $driver->buses()->pluck('id') : collect();
        }

        if ($user->hasRole('School Admin') || $user->hasRole('Principal')) {
            $schoolId = $user->school_id
                ?? SchoolAdmin::where('user_id', $user->id)->value('school_id')
                ?? School::where('principal_name', $user->name)->value('id');

            return $schoolId ? Bus::where('school_id', $schoolId)->pluck('id') : collect();
        }

        return null;
    }

    /**
     * Live assets keyed by IMEI, cached briefly to survive the 5s page polling.
     */
    private function assetsByImei(NazarTrackService $nazarTrack): Collection
    {
        try {
            $live = Cache::remember('nazartrack.live', 4, fn () => $nazarTrack->live());
        } catch (\Throwable) {
            $live = ['success' => false, 'count' => 0, 'data' => []];
        }

        return collect($live['data'] ?? [])
            ->filter(fn ($asset) => ! empty($asset['imei']))
            ->keyBy('imei');
    }

    /**
     * Fleet payload for the non-parent map: one plain array per bus, including
     * its route + stops and normalized telemetry (safe for @json).
     */
    private function fleetPayload(Collection $buses, Collection $assetsByImei): array
    {
        return $buses->map(function (Bus $bus) use ($assetsByImei) {
            return [
                'id' => $bus->id,
                'bus_number' => $bus->bus_number,
                'registration_number' => $bus->registration_number,
                'route_id' => $bus->route_id,
                'route_name' => $bus->route?->name,
                'driver_name' => $bus->driver?->full_name,
                'school_name' => $bus->school?->name,
                'route' => $bus->route ? [
                    'id' => $bus->route->id,
                    'name' => $bus->route->name,
                    'route_code' => $bus->route->route_code,
                    'start_location' => $bus->route->start_location,
                    'end_location' => $bus->route->end_location,
                    'stops' => $bus->route->stops->map(fn ($stop) => [
                        'name' => $stop->name,
                        'latitude' => $stop->latitude,
                        'longitude' => $stop->longitude,
                        'stop_order' => $stop->stop_order,
                        'pickup_time' => $stop->pickup_time,
                        'drop_time' => $stop->drop_time,
                    ])->values()->all(),
                ] : null,
                'telemetry' => $this->telemetry($bus, $assetsByImei),
            ];
        })->values()->all();
    }

    /**
     * Aggregate fleet statistics from a fleet payload.
     */
    private function fleetStats(array $fleet): array
    {
        $online = 0;
        $moving = 0;
        $latest = null;

        foreach ($fleet as $entry) {
            $t = $entry['telemetry'];

            if ($t['hasLive'] === 'live' && $t['latitude'] && $t['longitude']) {
                $online++;
            }

            if ($t['is_moving'] && $t['latitude'] && $t['longitude']) {
                $moving++;
            }

            if ($t['last_updated_at'] && ($latest === null || $t['last_updated_at'] > $latest)) {
                $latest = $t['last_updated_at'];
            }
        }

        if ($latest !== null) {
            try {
                $latest = \Carbon\Carbon::parse($latest)->toIso8601String();
            } catch (\Throwable) {
                $latest = null;
            }
        }

        return [
            'total' => count($fleet),
            'online' => $online,
            'moving' => $moving,
            'last_updated_at' => $latest,
        ];
    }

    /**
     * Normalized telemetry for a bus: live asset first, DB fallback, else none.
     */
    private function telemetry(Bus $bus, Collection $assetsByImei): array
    {
        $asset = $bus->gps_device_id ? $assetsByImei->get($bus->gps_device_id) : null;

        if ($asset) {
            return [
                'hasLive' => 'live',
                'latitude' => $asset['latitude'] ?? null,
                'longitude' => $asset['longitude'] ?? null,
                'speed' => (float) ($asset['speed_kmh'] ?? 0),
                'status_label' => $asset['status_label'] ?? null,
                'is_moving' => (bool) ($asset['is_moving'] ?? false),
                'marker_color' => $asset['marker']['color'] ?? null,
                'last_updated_at' => $asset['last_updated_at'] ?? null,
                'last_updated_ago' => $asset['last_updated_ago'] ?? null,
            ];
        }

        $location = $bus->gps_device_id
            ? BusLocation::where('gps_device_id', $bus->gps_device_id)->latest('recorded_at')->first()
            : null;

        if ($location) {
            return [
                'hasLive' => 'db',
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'speed' => (float) ($location->speed ?? 0),
                'status_label' => 'Last known position',
                'is_moving' => false,
                'marker_color' => null,
                'last_updated_at' => $location->recorded_at?->format('Y-m-d H:i:s'),
                'last_updated_ago' => $location->recorded_at?->diffForHumans(),
            ];
        }

        return [
            'hasLive' => 'none',
            'latitude' => null,
            'longitude' => null,
            'speed' => 0,
            'status_label' => 'No data',
            'is_moving' => false,
            'marker_color' => null,
            'last_updated_at' => null,
            'last_updated_ago' => null,
        ];
    }
}
