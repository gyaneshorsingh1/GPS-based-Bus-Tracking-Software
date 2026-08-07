<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusLocation;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\SchoolAdmin;
use App\Services\FleetMapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NazarTrackService;

class BusLocationController extends Controller
{
    public function __construct(
        private readonly FleetMapService $fleetMap,
        private readonly NazarTrackService $gpsService,
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('Parent')) {
            $parent = ParentProfile::where('user_id', $user->id)->first();

            $children = $parent
                ? $parent->children()->with(['bus.route.stops', 'bus.driver', 'bus.school'])->get()
                : collect();

            $selectedChildId = $request->query('child_id');
            $selectedChild = $children->firstWhere('id', $selectedChildId)
                ?? $children->firstWhere('bus_id', '!=', null)
                ?? $children->first();

            $bus = $selectedChild?->bus;
            $route = $bus?->route;

            if ($route) {
                $route->load(['stops', 'school', 'buses.driver']);
            }

            $latestLocation = $this->latestLocationForBus($bus);

            return view('bus_location.parent_bus_location', compact(
                'parent',
                'children',
                'selectedChild',
                'bus',
                'route',
                'latestLocation'
            ));
        }

        $allowedBusIds = null;

        if ($user->hasRole('Driver')) {
            $driver = Driver::where('user_id', $user->id)->first();
            $allowedBusIds = $driver ? $driver->buses()->pluck('buses.id') : collect();
        } elseif ($user->hasRole('School Admin')) {
            $schoolId = $user->school_id
                ?? SchoolAdmin::where('user_id', $user->id)->value('school_id');
            $allowedBusIds = $schoolId
                ? Bus::where('school_id', $schoolId)->pluck('id')
                : collect();
        }

        $locations = $this->fleetMap->latestLocationsByDevice(
            $allowedBusIds,
            ['gpsDevice.bus.driver', 'gpsDevice.bus.route', 'gpsDevice.bus.school'],
        );

        return view('bus_location.bus_location', [
            'locations' => $locations,
        ]);
    }

    /**
     * JSON endpoint used by the live tracking page to poll the latest GPS fix.
     */
    public function latestJson(Request $request)
    {
        $user = Auth::user();

        if (! $user->hasRole('Parent')) {
            abort(403, 'Only parents can poll live GPS data.');
        }

        $parent = ParentProfile::where('user_id', $user->id)->first();

        $children = $parent
            ? $parent->children()->with('bus')->get()
            : collect();

        $selectedChildId = $request->query('child_id');
        $selectedChild = $children->firstWhere('id', $selectedChildId)
            ?? $children->firstWhere('bus_id', '!=', null)
            ?? $children->first();

        return response()->json($this->latestLocationForBus($selectedChild?->bus));
    }

    /**
     * Build the normalized "latest GPS" payload consumed by the live map page.
     */
    private function latestLocationForBus(?Bus $bus): ?array
    {
        if (! $bus || empty($bus->gps_device_id)) {
            return null;
        }

        $location = $this->gpsService->getBusLocation($bus);

        if (! $location) {
            return null;
        }

        $status = strtolower((string) ($location['status'] ?? 'offline'));
        $course = (float) ($location['course'] ?? $location['marker']['heading'] ?? 0);

        return [
            'latitude'        => $location['latitude'] ?? null,
            'longitude'       => $location['longitude'] ?? null,
            'speed_kmh'       => (float) ($location['speed_kmh'] ?? $location['speed'] ?? 0),
            'course'          => $course,
            'status'          => $status,
            'status_label'    => $location['status_label'] ?? $this->gpsStatusLabel($status),
            'status_color'    => $location['status_color'] ?? $this->gpsStatusColor($status),
            'is_moving'       => (bool) ($location['is_moving'] ?? ($status === 'moving')),
            'gps_time'        => $location['gps_time'] ?? null,
            'last_updated_at' => $location['last_updated_at'] ?? null,
            'last_updated_ago'=> $location['last_updated_ago'] ?? null,
            'asset_name'      => $location['asset_name'] ?? null,
            'imei'            => $location['imei'] ?? $bus->gps_device_id,
            'animate'         => (bool) ($location['animate'] ?? true),
            'marker'          => $location['marker'] ?? ['heading' => $course],
        ];
    }

    private function gpsStatusLabel(string $status): string
    {
        return match ($status) {
            'moving'  => 'Moving',
            'stopped' => 'Stopped',
            'idle'    => 'Idle',
            'offline' => 'Offline',
            default   => ucfirst($status),
        };
    }

    private function gpsStatusColor(string $status): string
    {
        return match ($status) {
            'moving'  => '#22c55e',
            'stopped' => '#f59e0b',
            'idle'    => '#eab308',
            default   => '#6b7280',
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BusLocation $busLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusLocation $busLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusLocation $busLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusLocation $busLocation)
    {
        //
    }
}
