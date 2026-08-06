<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BusLocation;
use App\Models\ParentProfile;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    /**
     * Show the parent dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $parent = ParentProfile::where('user_id', $user->id)->first();

        if (! $parent) {
            return view('parentDashboard', [
                'user' => $user,
                'parent' => null,
                'children' => collect(),
                'locationsByBus' => collect(),
                'attendanceByStudent' => collect(),
            ]);
        }

        $children = $parent->children()
            ->with(['bus.route', 'bus.driver', 'bus.school'])
            ->get();

        $busIds = $children->pluck('bus_id')->filter()->unique();

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

        $attendanceByStudent = Attendance::whereIn('student_id', $children->pluck('id'))
            ->where('date', today())
            ->get()
            ->keyBy('student_id');

        return view('parentDashboard', compact(
            'user',
            'parent',
            'children',
            'locationsByBus',
            'attendanceByStudent',
        ));
    }

    /**
     * Show the parent's children list.
     */
    public function children()
    {
        $user = Auth::user();

        $parent = ParentProfile::where('user_id', $user->id)->first();

        if (! $parent) {
            return view('parents.children', [
                'user' => $user,
                'children' => collect(),
            ]);
        }

        $children = $parent->children()
            ->with(['bus.route', 'bus.driver', 'bus.school'])
            ->get();

        return view('parents.children', compact('user', 'children'));
    }
}
