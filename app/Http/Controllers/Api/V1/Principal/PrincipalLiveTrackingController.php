<?php

namespace App\Http\Controllers\Api\V1\Principal;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Services\FleetMapService;
use Illuminate\Http\Request;

class PrincipalLiveTrackingController extends Controller
{
    public function __construct(private readonly FleetMapService $fleetMap) {}

    public function index(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        $validated = $request->validate([
            'bus_id' => ['nullable', 'integer'],
        ]);

        $busIds = null;

        if (! empty($validated['bus_id'])) {
            $bus = Bus::where('school_id', $schoolId)->where('id', $validated['bus_id'])->first();

            if (! $bus) {
                return response()->json([
                    'message' => 'Bus not found in your school.',
                ], 404);
            }

            $busIds = collect([$bus->id]);
        }

        $fleetMap = $this->fleetMap->forSchool($schoolId, $busIds);

        return response()->json([
            'message' => 'Live tracking data.',
            'data' => $fleetMap,
        ]);
    }

    private function resolveSchoolId($user): ?int
    {
        $schoolId = $user->school_id;

        if (! $schoolId) {
            $schoolId = SchoolAdmin::where('user_id', $user->id)->value('school_id');
        }

        if (! $schoolId) {
            $schoolId = School::where('principal_name', $user->name)
                ->orWhere('email', $user->email)
                ->value('id');
        }

        return $schoolId;
    }
}
