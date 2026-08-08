<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ParentProfile;
use App\Services\FleetMapService;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

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
            $locations = $this->fleetMap->latestLocationsByDevice($busIds, ['gpsDevice']);

            $locationsByBus = $locations
                ->filter(fn ($location) => $location->gpsDevice?->bus_id)
                ->keyBy(fn ($location) => $location->gpsDevice->bus_id);
        }

        $attendanceByStudent = Attendance::whereIn('student_id', $children->pluck('id'))
            ->where('date', today())
            ->get()
            ->groupBy('student_id');

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
