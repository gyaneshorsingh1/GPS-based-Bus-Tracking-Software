<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverBusController extends Controller
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

        $buses = $driver->buses()
            ->with('school')
            ->get();

        return response()->json([
            'message' => 'Driver buses data.',
            'data' => [
                'driver' => [
                    'id' => $driver->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'buses' => $buses,
            ],
        ]);
    }
}
