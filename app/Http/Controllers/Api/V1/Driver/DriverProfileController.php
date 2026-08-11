<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $driver = $user->driver;

        if (!$driver) {
            return response()->json([
                'message' => 'Driver profile not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Driver profile data.',
            'data' => [
                'id' => $driver->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $driver->phone,
                'license_number' => $driver->license_number,
                'address' => $driver->address,
                 'role' => $user->getRoleNames()->first(),
                 
                 'status' => $user->status,
                'school' => [
                    'id' => $driver->school->id,
                    'name' => $driver->school->name,
                    'address' => $driver->school->address
                ],
               
            ],
        ]);
    }
}
