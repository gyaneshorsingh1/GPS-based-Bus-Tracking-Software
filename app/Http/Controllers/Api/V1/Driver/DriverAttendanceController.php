<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'message' => 'Driver profile not found.'
            ], 404);
        }

        $attendances = $driver->attendances()
            ->with(['bus.school', 'student', 'markedBy'])
            ->get();

        return response()->json([
            'message' => 'Driver attendance data.',
            'data' => [
                'driver' => [
                    'id' => $driver->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $driver->phone,
                    'school' => $driver->school ? [
                        'id' => $driver->school->id,
                        'name' => $driver->school->name,
                        'address' => $driver->school->address,
                    ] : null,
                ],
                'attendances' => $attendances,
            ],
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'message' => 'Driver profile not found.'
            ], 404);
        }

        $attendance = $driver->attendances()
            ->with(['bus.school', 'student', 'markedBy'])
            ->find($id);

        if (!$attendance) {
            return response()->json([
                'message' => 'Attendance record not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Driver attendance record.',
            'data' => [
                'id' => $attendance->id,
                'student_id' => $attendance->student_id,
                'bus_id' => $attendance->bus_id,
                'trip' => $attendance->trip,
                'date' => $attendance->date?->toDateString(),
                'check_in_at' => $attendance->check_in_at?->toIso8601String(),
                'check_out_at' => $attendance->check_out_at?->toIso8601String(),
                'status' => $attendance->isCheckedOut() ? 'checked_out' : ($attendance->isCheckedIn() ? 'checked_in' : 'not_checked_in'),
                'bus' => $attendance->bus ? [
                    'id' => $attendance->bus->id,
                    'bus_number' => $attendance->bus->bus_number,
                    'registration_number' => $attendance->bus->registration_number,
                ] : null,
                'student' => $attendance->student ? [
                    'id' => $attendance->student->id,
                    'name' => $attendance->student->full_name,
                ] : null,
            ],
        ]);
    }
}
