<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Driver;
use App\Services\FleetMapService;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

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
            $locations = $this->fleetMap->latestLocationsByDevice($busIds, ['gpsDevice']);

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
