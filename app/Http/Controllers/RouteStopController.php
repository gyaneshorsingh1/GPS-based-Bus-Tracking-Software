<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteStop;
use Illuminate\Http\Request;

class RouteStopController extends Controller
{
    /**
     * Store a newly created stop for a route.
     */
    public function store(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stop_order' => 'required|integer|min:1',
            'pickup_time' => 'nullable|string',
            'drop_time' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['route_id'] = $route->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['latitude'] = $validated['latitude'] ?? 0;
        $validated['longitude'] = $validated['longitude'] ?? 0;

        RouteStop::create($validated);

        return redirect()
            ->route('routes.show', $route)
            ->with('success', 'Route stop added successfully.');
    }

    /**
     * Update the specified stop.
     */
    public function update(Request $request, RouteStop $stop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stop_order' => 'required|integer|min:1',
            'pickup_time' => 'nullable|string',
            'drop_time' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['latitude'] = $validated['latitude'] ?? $stop->latitude ?? 0;
        $validated['longitude'] = $validated['longitude'] ?? $stop->longitude ?? 0;

        $stop->update($validated);

        return redirect()
            ->route('routes.show', $stop->route_id)
            ->with('success', 'Route stop updated successfully.');
    }

    /**
     * Remove the specified stop.
     */
    public function destroy(RouteStop $stop)
    {
        $routeId = $stop->route_id;
        $stop->delete();

        return redirect()
            ->route('routes.show', $routeId)
            ->with('success', 'Route stop deleted successfully.');
    }
}
