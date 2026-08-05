<x-app-layout page="route-management">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $route->name }}</h1>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('routes.edit', $route) }}"
                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                >
                    Edit
                </a>
                <a
                    href="{{ route('routes.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Back to Routes
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $route->route_code }}</p>
                @if ($route->is_active)
                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Active</span>
                @else
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Inactive</span>
                @endif
            </div>

            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Route Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Route Code</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->route_code }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Location</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->start_location }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Location</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->end_location }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimated Distance</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->estimated_distance ? $route->estimated_distance . ' km' : '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimated Duration</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->estimated_duration ? $route->estimated_duration . ' min' : '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">School</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->school->name ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->driver->full_name ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Buses</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @forelse ($route->buses as $bus)
                            <span class="mr-2 inline-block rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-900/20 dark:text-brand-400">
                                {{ $bus->bus_number }}
                            </span>
                        @empty
                            —
                        @endforelse
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->created_at->format('M d, Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $route->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>
