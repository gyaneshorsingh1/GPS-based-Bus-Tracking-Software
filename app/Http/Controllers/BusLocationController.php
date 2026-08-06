<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusLocation;
use App\Models\Driver;
use App\Models\ParentProfile;
use App\Models\SchoolAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $allowedBusIds = null;

        if ($user->hasRole('Parent')) {
            $parent = ParentProfile::where('user_id', $user->id)->first();
            $allowedBusIds = $parent
                ? $parent->children()->whereNotNull('bus_id')->pluck('bus_id')
                : collect();
        } elseif ($user->hasRole('Driver')) {
            $driver = Driver::where('user_id', $user->id)->first();
            $allowedBusIds = $driver ? $driver->buses()->pluck('buses.id') : collect();
        } elseif ($user->hasRole('School Admin')) {
            $schoolId = $user->school_id
                ?? SchoolAdmin::where('user_id', $user->id)->value('school_id');
            $allowedBusIds = $schoolId
                ? Bus::where('school_id', $schoolId)->pluck('id')
                : collect();
        }

        $latestPerDevice = BusLocation::select('gps_device_id')
            ->selectRaw('MAX(recorded_at) as last_recorded_at')
            ->groupBy('gps_device_id');

        $locations = BusLocation::query()
            ->joinSub($latestPerDevice, 'latest', function ($join) {
                $join->on('bus_locations.gps_device_id', '=', 'latest.gps_device_id')
                    ->on('bus_locations.recorded_at', '=', 'latest.last_recorded_at');
            })
            ->with(['gpsDevice.bus.driver', 'gpsDevice.bus.route', 'gpsDevice.bus.school'])
            ->when($allowedBusIds !== null, fn ($query) => $query
                ->whereHas('gpsDevice.bus', fn ($bus) => $bus->whereIn('id', $allowedBusIds)))
            ->orderByDesc('bus_locations.recorded_at')
            ->get();

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
