<?php

namespace App\Http\Controllers\Api\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\NazarTrackService;
use Illuminate\Http\Request;

class ParentLiveTrackingController extends Controller
{
    public function __construct(private readonly NazarTrackService $gps) {}

    public function index(Request $request)
    {
        $parent = $request->user()->parent;

        if (! $parent) {
            return response()->json([
                'message' => 'Parent profile not found.',
            ], 404);
        }

        $children = $parent->children()
            ->with('bus')
            ->orderBy('grade')
            ->orderBy('roll_no')
            ->get();

        return response()->json([
            'message' => 'Parent live tracking data.',
            'data' => [
                'children_count' => $children->count(),
                'children' => $children->map(fn ($student) => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'grade' => $student->grade,
                    'section' => $student->section,
                    'photo' => $student->photo ? asset('storage/'.$student->photo) : null,
                    'bus' => $student->bus ? [
                        'id' => $student->bus->id,
                        'bus_number' => $student->bus->bus_number,
                        'registration_number' => $student->bus->registration_number,
                        'status' => $student->bus->status,
                    ] : null,
                    'live_location' => $student->bus ? $this->gps->locationPayload($student->bus) : null,
                ]),
            ],
        ]);
    }

    public function show(Request $request, Student $student)
    {
        $parent = $request->user()->parent;

        if (! $parent) {
            return response()->json([
                'message' => 'Parent profile not found.',
            ], 404);
        }

        if ($parent->children()->whereKey($student->id)->doesntExist()) {
            return response()->json([
                'message' => 'You are not authorized to view this student.',
            ], 403);
        }

        $student->load('bus');

        return response()->json([
            'message' => 'Parent child live tracking data.',
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'full_name' => $student->full_name,
                    'grade' => $student->grade,
                    'section' => $student->section,
                    'photo' => $student->photo ? asset('storage/'.$student->photo) : null,
                ],
                'bus' => $student->bus ? [
                    'id' => $student->bus->id,
                    'bus_number' => $student->bus->bus_number,
                    'registration_number' => $student->bus->registration_number,
                    'status' => $student->bus->status,
                ] : null,
                'live_location' => $student->bus ? $this->gps->locationPayload($student->bus) : null,
            ],
        ]);
    }
}
