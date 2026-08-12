<?php

namespace App\Http\Controllers\Api\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\NazarTrackService;
use Illuminate\Http\Request;

class ParentBusController extends Controller
{
    public function __construct(private readonly NazarTrackService $gps) {}

    public function show(Request $request, Student $student)
    {
        $parent = $request->user()->parent;

        if (! $parent) {
            return response()->json([
                'message' => 'Parent profile not found.',
            ], 404);
        }

        $hasAccess = $parent->children()
            ->whereKey($student->id)
            ->exists();

        if (! $hasAccess) {
            return response()->json([
                'message' => "You are not authorized to view this student's bus.",
            ], 403);
        }

        $bus = $student->bus()
            ->with(['route.stops', 'driver', 'school'])
            ->first();

        if (! $bus) {
            return response()->json([
                'message' => 'Bus not found for this child.',
            ], 404);
        }

        return response()->json([
            'message' => 'Parent child bus data.',
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'grade' => $student->grade,
                    'section' => $student->section,
                    'photo' => $student->photo ? asset('storage/'.$student->photo) : null,
                    'pickup_location' => $student->pickup_location,
                    'drop_location' => $student->drop_location,
                ],
                'bus' => [
                    'id' => $bus->id,
                    'bus_number' => $bus->bus_number,
                    'registration_number' => $bus->registration_number,
                    'make' => $bus->make,
                    'model' => $bus->model,
                    'year' => $bus->year,
                    'capacity' => $bus->capacity,
                    'fuel_type' => $bus->fuel_type,
                    'status' => $bus->status,
                    'driver' => $bus->driver ? [
                        'id' => $bus->driver->id,
                        'name' => $bus->driver->full_name,
                        'phone' => $bus->driver->phone,
                    ] : null,
                    'route' => $bus->route ? [
                        'id' => $bus->route->id,
                        'name' => $bus->route->name,
                        'route_code' => $bus->route->route_code,
                        'start_location' => $bus->route->start_location,
                        'end_location' => $bus->route->end_location,
                        'stops' => $bus->route->stops->map(fn ($stop) => [
                            'id' => $stop->id,
                            'name' => $stop->name,
                            'latitude' => $stop->latitude,
                            'longitude' => $stop->longitude,
                            'stop_order' => $stop->stop_order,
                            'pickup_time' => $stop->pickup_time,
                            'drop_time' => $stop->drop_time,
                        ])->values(),
                    ] : null,
                    'school' => $bus->school ? [
                        'id' => $bus->school->id,
                        'name' => $bus->school->name,
                        'address' => $bus->school->address,
                    ] : null,
                ],
                'live_location' => $this->gps->locationPayload($bus),
            ],
        ]);
    }
}
