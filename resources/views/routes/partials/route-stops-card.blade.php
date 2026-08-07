<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Route Stops</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Ordered sequence of pickup and drop points</p>
            </div>
        </div>

        <button
            type="button"
            onclick="openAddStopModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Stop
        </button>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/80 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                <tr class="border-b border-gray-200 dark:border-gray-800">
                    <th scope="col" class="px-4 py-3 font-semibold text-center w-12">#</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Stop Name</th>
                    <th scope="col" class="px-4 py-3 font-semibold">Pickup</th>
                    <th scope="col" class="px-4 py-3 font-semibold">Arrival</th>
                    <th scope="col" class="px-4 py-3 font-semibold text-center">Status</th>
                    <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($route->stops as $stop)
                    <tr class="text-gray-700 transition hover:bg-gray-50/50 dark:text-gray-200 dark:hover:bg-white/[0.02]">
                        <td class="px-4 py-3.5 text-center font-bold text-gray-900 dark:text-white">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-50 text-xs font-semibold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                {{ $stop->stop_order }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span>{{ $stop->name }}</span>
                            </div>
                            @if ($stop->latitude && $stop->longitude)
                                <div class="mt-0.5 text-xs text-gray-400 font-mono">
                                    {{ number_format($stop->latitude, 4) }}, {{ number_format($stop->longitude, 4) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 font-medium">
                            @if ($stop->pickup_time)
                                <span class="inline-flex items-center gap-1.5 text-gray-900 dark:text-gray-200 font-mono text-xs">
                                    <svg class="h-3.5 w-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ date('h:i A', strtotime($stop->pickup_time)) }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 font-medium">
                            @if ($stop->drop_time)
                                <span class="inline-flex items-center gap-1.5 text-gray-900 dark:text-gray-200 font-mono text-xs">
                                    <svg class="h-3.5 w-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ date('h:i A', strtotime($stop->drop_time)) }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if ($stop->is_active ?? true)
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    onclick='openEditStopModal(@json($stop))'
                                    class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                >
                                    Edit
                                </button>
                                <form
                                    action="{{ route('route-stops.destroy', $stop) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete stop {{ $stop->name }}?');"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20"
                                    >
                                        Del
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <p class="text-sm font-medium">No route stops configured yet.</p>
                                <p class="text-xs text-gray-400">Click "+ Add Stop" above to add the first stop to this route.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
