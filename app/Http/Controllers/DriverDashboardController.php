<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BusLocation;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    /**
     * Show the driver dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $driver = Driver::where('user_id', $user->id)->first();

        if (! $driver) {
            return view('driverDashboard', [
                'user' => $user,
                'driver' => null,
                'buses' => collect(),
                'locationsByBus' => collect(),
                'checkedInByBus' => collect(),
            ]);
        }

        $buses = $driver->buses()
            ->with(['route', 'school', 'students'])
            ->withCount('students')
            ->orderBy('bus_number')
            ->get();

        $busIds = $buses->pluck('id');

        $locationsByBus = collect();
        if ($busIds->isNotEmpty()) {
            $latestPerDevice = BusLocation::select('gps_device_id')
                ->selectRaw('MAX(recorded_at) as last_recorded_at')
                ->groupBy('gps_device_id');

            $locations = BusLocation::query()
                ->joinSub($latestPerDevice, 'latest', function ($join) {
                    $join->on('bus_locations.gps_device_id', '=', 'latest.gps_device_id')
                        ->on('bus_locations.recorded_at', '=', 'latest.last_recorded_at');
                })
                ->with('gpsDevice')
                ->whereHas('gpsDevice.bus', fn ($bus) => $bus->whereIn('id', $busIds))
                ->get();

            $locationsByBus = $locations
                ->filter(fn ($location) => $location->gpsDevice?->bus_id)
                ->keyBy(fn ($location) => $location->gpsDevice->bus_id);
        }

        $checkedInByBus = Attendance::query()
            ->whereDate('date', now()->toDateString())
            ->whereIn('bus_id', $busIds)
            ->whereNotNull('check_in_at')
            ->get()
            ->groupBy('bus_id')
            ->map
            ->count();

        return view('driverDashboard', compact(
            'user',
            'driver',
            'buses',
            'locationsByBus',
            'checkedInByBus',
        ));
    }
}
