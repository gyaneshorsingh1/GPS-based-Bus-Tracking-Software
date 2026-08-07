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

class BusLocationController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

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

            $latestLocation = null;
            if ($bus && $bus->gps_device_id) {
                $latestLocation = BusLocation::where('gps_device_id', $bus->gps_device_id)
                    ->latest('recorded_at')
                    ->first();
            }

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
