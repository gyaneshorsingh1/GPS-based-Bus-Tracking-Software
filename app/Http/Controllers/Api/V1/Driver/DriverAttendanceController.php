<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $driver = $request->user()->driver;

        if (!$driver) {
            return response()->json([
                'message' => 'Driver profile not found.'
            ], 404);
        }

        $validated = $request->validate([
            'bus_id' => ['required', 'integer'],
        ]);

        $bus = $driver->buses()
            ->with(['route', 'students.parent.user'])
            ->find($validated['bus_id']);

        if (!$bus) {
            return response()->json([
                'message' => 'Bus not found for this driver.'
            ], 404);
        }

        $students = $bus->students()
            ->orderBy('grade')
            ->orderBy('roll_no')
            ->get();

        return response()->json([
            'message' => 'Students for driver bus.',
            'data' => [
                'bus' => [
                    'id' => $bus->id,
                    'bus_number' => $bus->bus_number,
                    'registration_number' => $bus->registration_number,
                    'status' => $bus->status,
                    'route' => $bus->route ? [
                        'id' => $bus->route->id,
                        'name' => $bus->route->name,
                    ] : null,
                ],
                'total_students' => $students->count(),
                'students' => $students->map(fn ($student) => [
                    'id' => $student->id,
                    'admission_no' => $student->admission_no,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'full_name' => $student->full_name,
                    'gender' => $student->gender,
                    'grade' => $student->grade,
                    'section' => $student->section,
                    'roll_no' => $student->roll_no,
                    'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                    'pickup_location' => $student->pickup_location,
                    'drop_location' => $student->drop_location,
                    'parent' => $student->parent ? [
                        'id' => $student->parent->id,
                        'name' => $student->parent->user->name ?? $student->parent->father_name,
                        'phone' => $student->parent->phone,
                    ] : null,
                ]),
            ],
        ]);
    }

}
