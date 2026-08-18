<?php

namespace App\Http\Controllers\Api\V1\Principal;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\School;
use App\Models\SchoolAdmin;
use Illuminate\Http\Request;

class PrincipalDriverController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        $query = Driver::query()
            ->with(['user', 'school', 'buses'])
            ->where('school_id', $schoolId)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($query) => $query
                ->where('employee_id', 'like', '%'.$request->string('q').'%')
                ->orWhere('first_name', 'like', '%'.$request->string('q').'%')
                ->orWhere('last_name', 'like', '%'.$request->string('q').'%')
                ->orWhere('phone', 'like', '%'.$request->string('q').'%')
                ->orWhere('license_number', 'like', '%'.$request->string('q').'%')))

            ->latest()
            ->paginate($this->perPage($request));

        return response()->json([
            'message' => 'Drivers list.',
            'data' => [
                'drivers' => $query->map(fn (Driver $driver) => $this->driverPayload($driver)),
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'per_page' => $query->perPage(),
                    'total' => $query->total(),
                    'last_page' => $query->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, Driver $driver)
    {
        $schoolId = $this->resolveSchoolId($request->user());

        if (! $schoolId) {
            return response()->json([
                'message' => 'Principal profile not found.',
            ], 404);
        }

        if ($driver->school_id !== $schoolId) {
            return response()->json([
                'message' => 'You are not authorized to access this driver.',
            ], 403);
        }

        $driver->load(['user', 'school', 'buses.route']);

        return response()->json([
            'message' => 'Driver details.',
            'data' => [
                'driver' => $this->driverPayload($driver, true),
            ],
        ]);
    }

    private function driverPayload(Driver $driver, bool $detailed = false): array
    {
        $payload = [
            'id' => $driver->id,
            'employee_id' => $driver->employee_id,
            'first_name' => $driver->first_name,
            'last_name' => $driver->last_name,
            'full_name' => $driver->full_name,
            'gender' => $driver->gender,
            'date_of_birth' => $driver->date_of_birth?->toDateString(),
            'phone' => $driver->phone,
            'email' => $driver->email,
            'address' => $driver->address,
            'city' => $driver->city,
            'state' => $driver->state,
            'country' => $driver->country,
            'postal_code' => $driver->postal_code,
            'license_number' => $driver->license_number,
            'license_type' => $driver->license_type,
            'license_issue_date' => $driver->license_issue_date?->toDateString(),
            'license_expiry_date' => $driver->license_expiry_date?->toDateString(),
            'experience_years' => $driver->experience_years,
            'joining_date' => $driver->joining_date?->toDateString(),
            'status' => $driver->status,
            'profile_photo' => $driver->profile_photo ? asset('storage/'.$driver->profile_photo) : null,
            'emergency_contact_name' => $driver->emergency_contact_name,
            'emergency_contact_phone' => $driver->emergency_contact_phone,
            'remarks' => $driver->remarks,
            'buses' => $driver->buses->map(fn ($bus) => [
                'id' => $bus->id,
                'bus_number' => $bus->bus_number,
                'registration_number' => $bus->registration_number,
                'status' => $bus->status,
                'route' => $bus->route ? [
                    'id' => $bus->route->id,
                    'name' => $bus->route->name,
                ] : null,
            ]),
        ];

        if ($detailed) {
            $payload['user'] = $driver->user ? [
                'id' => $driver->user->id,
                'name' => $driver->user->name,
                'email' => $driver->user->email,
                'status' => $driver->user->status,
            ] : null;
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
