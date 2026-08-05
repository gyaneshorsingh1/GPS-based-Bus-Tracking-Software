<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="mb-3 flex items-start justify-between gap-3">
        <div>
            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $bus->bus_number }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bus->registration_number }}</p>
        </div>
        @if ($bus->status === 'Active')
            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Active</span>
        @else
            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $bus->status }}</span>
        @endif
    </div>

    <dl class="mb-4 space-y-1.5 text-sm">
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">Driver</dt>
            <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->driver?->full_name ?? '—' }}</dd>
        </div>
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">Route</dt>
            <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->route?->name ?? '—' }}</dd>
        </div>
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">Capacity</dt>
            <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->capacity }}</dd>
        </div>
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">Assigned Students</dt>
            <dd class="font-medium text-gray-900 dark:text-white">{{ $bus->students_count }}</dd>
        </div>
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">Checked In ({{ $today }})</dt>
            <dd class="font-medium text-gray-900 dark:text-white">{{ $checkedIn[$bus->id] ?? 0 }} / {{ $bus->students_count }}</dd>
        </div>
    </dl>

    <a
        href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today]) }}"
        class="block w-full rounded-lg bg-brand-500 px-4 py-2 text-center text-sm font-medium text-white hover:bg-brand-600"
    >
        View Attendance
    </a>
</div>
