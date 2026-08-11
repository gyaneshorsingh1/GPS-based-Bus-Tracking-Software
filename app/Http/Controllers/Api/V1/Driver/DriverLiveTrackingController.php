<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Services\FleetMapService;
use Illuminate\Http\Request;

class DriverLiveTrackingController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

    public function index(Request $request)
    {
        $driver = $request->user()->driver;

        if (!$driver) {
            return response()->json([
                'message' => 'Driver profile not found.'
            ], 404);
        }

        $validated = $request->validate([
            'bus_id' => ['nullable', 'integer'],
        ]);

        $busIds = $driver->buses()->pluck('id');

        if (!empty($validated['bus_id'])) {
            $hasAccess = $driver->buses()
                ->whereKey($validated['bus_id'])
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'message' => 'Bus not found for this driver.'
                ], 404);
            }

            $busIds = collect([(int) $validated['bus_id']]);
        }

        $fleetMap = $this->fleetMap->forSchool(null, $busIds);

        return response()->json([
            'message' => 'Driver live tracking data.',
            'data' => $fleetMap,
        ]);
    }
}
