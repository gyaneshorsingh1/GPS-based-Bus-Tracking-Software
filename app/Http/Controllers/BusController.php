<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display all buses.
     */
    public function index(Request $request)
    {
        $query = Bus::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('bus_number', 'like', "%{$search}%")
                    ->orWhere('vehicle_number', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhere('route', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $buses = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Dashboard statistics
        $totalBuses = Bus::count();

        $activeBuses = Bus::where('status', 'active')->count();

        $maintenanceBuses = Bus::where('status', 'maintenance')->count();

        $inactiveBuses = Bus::where('status', 'inactive')->count();

        $totalCapacity = Bus::where('status', 'active')
            ->sum('capacity');

        return view('buses.index', compact(
            'buses',
            'totalBuses',
            'activeBuses',
            'maintenanceBuses',
            'inactiveBuses',
            'totalCapacity'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('buses.create');
    }

    /**
     * Store a new bus.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:50',
                'unique:buses,bus_number',
            ],

            'vehicle_number' => [
                'required',
                'string',
                'max:50',
                'unique:buses,vehicle_number',
            ],

            'driver_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'driver_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:200',
            ],

            'route' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'drop_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:active,maintenance,inactive',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        Bus::create($validated);

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus added successfully.');
    }

    /**
     * Display a single bus.
     */
    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    /**
     * Show edit form.
     */
    public function edit(Bus $bus)
    {
        return view('buses.edit', compact('bus'));
    }

    /**
     * Update bus.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:50',
                'unique:buses,bus_number,' . $bus->id,
            ],

            'vehicle_number' => [
                'required',
                'string',
                'max:50',
                'unique:buses,vehicle_number,' . $bus->id,
            ],

            'driver_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'driver_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:200',
            ],

            'route' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'drop_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:active,maintenance,inactive',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $bus->update($validated);

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus updated successfully.');
    }

    /**
     * Delete bus.
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();

        return redirect()
            ->route('buses.index')
            ->with('success', 'Bus deleted successfully.');
    }
}