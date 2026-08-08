@php
    $tripCounts = $checkedIn[$bus->id] ?? [
        App\Models\Attendance::TRIP_HOME_TO_SCHOOL => 0,
        App\Models\Attendance::TRIP_SCHOOL_TO_HOME => 0,
    ];

    $homeToSchoolIn = $tripCounts[App\Models\Attendance::TRIP_HOME_TO_SCHOOL] ?? 0;
    $schoolToHomeIn = $tripCounts[App\Models\Attendance::TRIP_SCHOOL_TO_HOME] ?? 0;

    $homeToSchoolCompleted = $bus->students_count > 0 && $homeToSchoolIn >= $bus->students_count;
    $schoolToHomeCompleted = $bus->students_count > 0 && $schoolToHomeIn >= $bus->students_count;
    $dayCompleted = $homeToSchoolCompleted && $schoolToHomeCompleted;
@endphp

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
            <dt class="text-gray-500 dark:text-gray-400">Home → School ({{ $today }})</dt>
            <dd class="font-medium text-gray-900 dark:text-white">
                {{ $homeToSchoolIn }} / {{ $bus->students_count }}
                @if ($homeToSchoolCompleted)
                    <span class="ml-1 text-green-600 dark:text-green-400">✓</span>
                @endif
            </dd>
        </div>
        <div class="flex justify-between gap-3">
            <dt class="text-gray-500 dark:text-gray-400">School → Home ({{ $today }})</dt>
            <dd class="font-medium text-gray-900 dark:text-white">
                {{ $schoolToHomeIn }} / {{ $bus->students_count }}
                @if ($schoolToHomeCompleted)
                    <span class="ml-1 text-green-600 dark:text-green-400">✓</span>
                @endif
            </dd>
        </div>
    </dl>

    @if ($bus->status === 'Active')
        @if ($dayCompleted)
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $nextDate ?? $today]) }}"
                class="block w-full rounded-lg bg-brand-500 px-4 py-2 text-center text-sm font-medium text-white hover:bg-brand-600"
            >
                Add Next Day Attendance
            </a>
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today]) }}"
                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                View Today's Attendance
            </a>
        @elseif ($homeToSchoolCompleted)
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today, 'trip' => App\Models\Attendance::TRIP_SCHOOL_TO_HOME]) }}"
                class="block w-full rounded-lg bg-brand-500 px-4 py-2 text-center text-sm font-medium text-white hover:bg-brand-600"
            >
                Add School to Home Attendance
            </a>
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today]) }}"
                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                View Attendance
            </a>
        @else
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today, 'trip' => App\Models\Attendance::TRIP_HOME_TO_SCHOOL]) }}"
                class="block w-full rounded-lg bg-brand-500 px-4 py-2 text-center text-sm font-medium text-white hover:bg-brand-600"
            >
                Add Today's Attendance
            </a>
            <a
                href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $today]) }}"
                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                View Attendance
            </a>
        @endif
    @else
        <button
            type="button"
            disabled
            title="Attendance can only be marked on active buses"
            class="block w-full cursor-not-allowed rounded-lg bg-gray-100 px-4 py-2 text-center text-sm font-medium text-gray-400 dark:bg-gray-800 dark:text-gray-500"
        >
            View Attendance
        </button>
    @endif
</div>
