<x-app-layout page="buses">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $bus->bus_number }}</h1>
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('buses.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Back to Buses
                </a>
                <a
                    href="{{ route('buses.edit', $bus) }}"
                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                >
                    Edit Bus
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center gap-3">
            @if ($bus->status === 'Active')
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Active</span>
            @elseif ($bus->status === 'Maintenance')
                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">Maintenance</span>
            @else
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Inactive</span>
            @endif

            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $bus->school->name ?? 'No school assigned' }}</span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="mb-4 border-b border-gray-200 pb-3 text-lg font-semibold text-gray-900 dark:border-gray-800 dark:text-white">Vehicle Details</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Bus Number</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->bus_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Registration Number</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->registration_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Make</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->make ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Model</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->model ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Year</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->year ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Capacity</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->capacity }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Fuel Type</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->fuel_type ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">GPS Device ID</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->gps_device_id ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Insurance Number</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->insurance_number ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Insurance Expiry Date</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->insurance_expiry_date?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Last Service Date</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->last_service_date?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                    @if ($bus->notes)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Notes</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h2 class="mb-4 border-b border-gray-200 pb-3 text-lg font-semibold text-gray-900 dark:border-gray-800 dark:text-white">Assigned Drivers</h2>

                    @forelse ($bus->drivers as $driver)
                        <div class="mb-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->full_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $driver->employee_id }} · {{ $driver->phone ?? 'No phone' }}</p>
                            </div>
                            <a
                                href="{{ route('drivers.show', $driver) }}"
                                class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                View
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No drivers assigned to this bus.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
