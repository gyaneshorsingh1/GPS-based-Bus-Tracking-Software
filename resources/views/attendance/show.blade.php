<x-app-layout page="attendance">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $bus->bus_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $bus->school->name ?? '—' }} ·
                    Route: {{ $bus->route?->name ?? '—' }} ·
                    Driver: {{ $bus->driver?->full_name ?? '—' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('attendance.buses.show', $bus) }}" method="GET">
                    <div class="flex items-center gap-2">
                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ $date }}"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >
                        <button
                            type="submit"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            View
                        </button>
                    </div>
                </form>
                @if ($homeToSchoolCompleted && $schoolToHomeChecked >= $students->count())
                    <a
                        href="{{ route('attendance.buses.show', ['bus' => $bus, 'date' => $nextDate]) }}"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                    >
                        Add Next Day Attendance
                    </a>
                @endif
                <a
                    href="{{ route('attendance.buses.history', $bus) }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    History
                </a>
                <a
                    href="{{ route('attendance.index', ['date' => $date]) }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Back to Buses
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            @foreach (\App\Models\Attendance::trips() as $trip => $tripLabel)
                @php
                    $tripLocked = $trip === \App\Models\Attendance::TRIP_SCHOOL_TO_HOME && ! $homeToSchoolCompleted;
                @endphp
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] {{ $tripLocked ? 'opacity-75' : '' }} {{ $trip === ($activeTrip ?? null) ? 'ring-2 ring-brand-500' : '' }}">
                    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tripLabel }}</h2>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                @php
                                    $tripChecked = ($attendance[$trip] ?? collect())
                                        ->filter(fn ($a) => $a->isCheckedIn())
                                        ->count();
                                    $tripCompleted = $students->isNotEmpty() && $tripChecked >= $students->count();
                                @endphp
                                {{ $tripChecked }} / {{ $students->count() }} checked in
                                @if ($tripCompleted)
                                    <span class="ml-1 text-green-600 dark:text-green-400">· Completed ✓</span>
                                @endif
                            </p>
                        </div>
                        @if ($tripLocked)
                            <span
                                class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                title="Complete the Home to School attendance first"
                            >
                                Locked
                            </span>
                        @elseif ($tripCompleted)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Completed</span>
                        @endif
                    </div>

                    @if ($tripLocked)
                        <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 text-xs text-gray-500 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-400">
                            Complete the Home to School (Pickup) attendance first to unlock this trip.
                        </div>
                    @endif

                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-200 dark:border-gray-800">
                            <tr class="text-gray-500 dark:text-gray-400">
                                <th class="px-5 py-3 font-medium">Student</th>
                                <th class="px-5 py-3 font-medium">Check In</th>
                                <th class="px-5 py-3 font-medium">Check Out</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($students as $student)
                                @php
                                    $record = ($attendance[$trip] ?? collect())[$student->id] ?? null;
                                @endphp
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($student->photo)
                                                <img
                                                    src="{{ asset('storage/' . $student->photo) }}"
                                                    alt="{{ $student->full_name }}"
                                                    class="h-9 w-9 rounded-full object-cover"
                                                >
                                            @else
                                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-xs font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $student->full_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $student->grade }}{{ $student->section ? ' - ' . $student->section : '' }} · {{ $student->admission_no }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($record?->isCheckedIn())
                                            <span class="text-xs font-medium text-green-700 dark:text-green-400">
                                                {{ $record->check_in_at->format('H:i') }}
                                            </span>
                                        @elseif ($tripLocked)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                        @else
                                            <form action="{{ route('attendance.mark', ['bus' => $bus, 'student' => $student]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="check_in">
                                                <input type="hidden" name="trip" value="{{ $trip }}">
                                                <input type="hidden" name="date" value="{{ $date }}">
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700"
                                                >
                                                    Check In
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($tripLocked)
                                            <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                        @elseif ($record?->isCheckedIn() && ! $record?->isCheckedOut())
                                            <form action="{{ route('attendance.mark', ['bus' => $bus, 'student' => $student]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="check_out">
                                                <input type="hidden" name="trip" value="{{ $trip }}">
                                                <input type="hidden" name="date" value="{{ $date }}">
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600"
                                                >
                                                    Check Out
                                                </button>
                                            </form>
                                        @elseif ($record?->isCheckedOut())
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                {{ $record->check_out_at->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($record?->isCheckedOut())
                                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Completed</span>
                                        @elseif ($record?->isCheckedIn())
                                            <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">Checked In</span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Not Checked In</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                                        No students assigned to this bus yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
