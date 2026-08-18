<?php

namespace App\Http\Controllers\Api\V1\Principal;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\School;
use App\Models\SchoolAdmin;
use Illuminate\Http\Request;

class PrincipalBusController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        $query = Bus::query()
            ->with(['driver', 'route', 'gpsDevice', 'school'])
            ->where('school_id', $schoolId)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($query) => $query
                ->where('bus_number', 'like', '%'.$request->string('q').'%')
                ->orWhere('registration_number', 'like', '%'.$request->string('q').'%')
                ->orWhere('make', 'like', '%'.$request->string('q').'%')
                ->orWhere('model', 'like', '%'.$request->string('q').'%')))

            ->latest()
            ->paginate($this->perPage($request));

        return response()->json([
            'message' => 'Buses list.',
            'data' => [
                'buses' => $query->map(fn (Bus $bus) => $this->busPayload($bus)),
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'per_page' => $query->perPage(),
                    'total' => $query->total(),
                    'last_page' => $query->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, Bus $bus)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        if ($bus->school_id !== $schoolId) {
            return response()->json([
                'message' => 'You are not authorized to access this bus.',
            ], 403);
        }

        $bus->load(['driver', 'route.stops', 'gpsDevice', 'school', 'students']);

        return response()->json([
            'message' => 'Bus details.',
            'data' => [
                'bus' => $this->busPayload($bus, true),
            ],
        ]);
    }

    private function busPayload(Bus $bus, bool $detailed = false): array
    {
        $payload = [
            'id' => $bus->id,
            'bus_number' => $bus->bus_number,
            'registration_number' => $bus->registration_number,
            'make' => $bus->make,
            'model' => $bus->model,
            'year' => $bus->year,
            'capacity' => $bus->capacity,
            'fuel_type' => $bus->fuel_type,
            'status' => $bus->status,
            'insurance_number' => $bus->insurance_number,
            'insurance_expiry_date' => $bus->insurance_expiry_date?->toDateString(),
            'last_service_date' => $bus->last_service_date?->toDateString(),
            'notes' => $bus->notes,
            'driver' => $bus->driver ? [
                'id' => $bus->driver->id,
                'full_name' => $bus->driver->full_name,
                'phone' => $bus->driver->phone,
                'email' => $bus->driver->email,
                'status' => $bus->driver->status,
            ] : null,
            'route' => $bus->route ? [
                'id' => $bus->route->id,
                'name' => $bus->route->name,
                'route_code' => $bus->route->route_code,
                'start_location' => $bus->route->start_location,
                'end_location' => $bus->route->end_location,
                'is_active' => $bus->route->is_active,
            ] : null,
            'gps_device' => $bus->gpsDevice ? [
                'id' => $bus->gpsDevice->id,
                'device_name' => $bus->gpsDevice->device_name,
                'device_imei' => $bus->gpsDevice->device_imei,
                'status' => $bus->gpsDevice->status,
            ] : null,
        ];

        if ($detailed) {
            $payload['students_count'] = $bus->students()->count();
            $payload['students'] = $bus->students->map(fn ($student) => [
                'id' => $student->id,
                'admission_no' => $student->admission_no,
                'full_name' => $student->full_name,
                'grade' => $student->grade,
                'section' => $student->section,
            ]);
            $payload['route_stops'] = $bus->route?->stops?->map(fn ($stop) => [
                'id' => $stop->id,
                'name' => $stop->name,
                'latitude' => $stop->latitude,
                'longitude' => $stop->longitude,
                'stop_order' => $stop->stop_order,
                'pickup_time' => $stop->pickup_time,
                'drop_time' => $stop->drop_time,
                'is_active' => $stop->is_active,
            ])->values();
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
