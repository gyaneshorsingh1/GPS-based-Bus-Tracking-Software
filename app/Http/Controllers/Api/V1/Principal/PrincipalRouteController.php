<?php

namespace App\Http\Controllers\Api\V1\Principal;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\School;
use App\Models\SchoolAdmin;
use Illuminate\Http\Request;

class PrincipalRouteController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        $query = Route::query()
            ->with(['stops', 'buses'])
            ->where('school_id', $schoolId)
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('route_code', 'like', '%'.$request->string('q').'%')
                ->orWhere('start_location', 'like', '%'.$request->string('q').'%')
                ->orWhere('end_location', 'like', '%'.$request->string('q').'%')))

            ->latest()
            ->paginate($this->perPage($request));

        return response()->json([
            'message' => 'Routes list.',
            'data' => [
                'routes' => $query->map(fn (Route $route) => $this->routePayload($route)),
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'per_page' => $query->perPage(),
                    'total' => $query->total(),
                    'last_page' => $query->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, Route $route)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        if ($route->school_id !== $schoolId) {
            return response()->json([
                'message' => 'You are not authorized to access this route.',
            ], 403);
        }

        $route->load(['stops', 'buses.driver', 'students']);

        return response()->json([
            'message' => 'Route details.',
            'data' => [
                'route' => $this->routePayload($route, true),
            ],
        ]);
    }

    private function routePayload(Route $route, bool $detailed = false): array
    {
        $payload = [
            'id' => $route->id,
            'name' => $route->name,
            'route_code' => $route->route_code,
            'start_location' => $route->start_location,
            'end_location' => $route->end_location,
            'estimated_distance' => $route->estimated_distance,
            'estimated_duration' => $route->estimated_duration,
            'is_active' => $route->is_active,
            'stops' => $route->stops->map(fn ($stop) => [
                'id' => $stop->id,
                'name' => $stop->name,
                'latitude' => $stop->latitude,
                'longitude' => $stop->longitude,
                'stop_order' => $stop->stop_order,
                'pickup_time' => $stop->pickup_time,
                'drop_time' => $stop->drop_time,
                'is_active' => $stop->is_active,
            ])->values(),
            'buses_count' => $route->buses->count(),
            'buses' => $route->buses->map(fn ($bus) => [
                'id' => $bus->id,
                'bus_number' => $bus->bus_number,
                'registration_number' => $bus->registration_number,
                'status' => $bus->status,
                'driver' => $bus->driver ? [
                    'id' => $bus->driver->id,
                    'full_name' => $bus->driver->full_name,
                    'phone' => $bus->driver->phone,
                ] : null,
            ]),
        ];

        if ($detailed) {
            $payload['students_count'] = $route->students->count();
            $payload['students'] = $route->students->map(fn ($student) => [
                'id' => $student->id,
                'admission_no' => $student->admission_no,
                'full_name' => $student->full_name,
                'grade' => $student->grade,
                'section' => $student->section,
            ]);
        }

        return $payload;
    }

    private function perPage(Request $request): int
    {
        return max(1, min(50, $request->integer('per_page', 15)));
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
