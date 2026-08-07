<x-app-layout page="live-tracking">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6 space-y-6">

        <!-- Page Header & Selector -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 pb-5 dark:border-gray-800">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Live Tracking</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Real-time tracking, live telemetry, and stop schedule fed from the NazarTrack feed.
                        </p>
                    </div>
                </div>
            </div>

            @if ($isParent && $children->count() > 1)
                <!-- Multiple Children Selector Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 custom-scrollbar">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 shrink-0">Select Child:</span>
                    @foreach ($children as $child)
                        @php
                            $isSelected = $selectedChild && $selectedChild->id === $child->id;
                        @endphp
                        <a
                            href="{{ route('live-tracking', ['child_id' => $child->id]) }}"
                            class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold transition shrink-0 border {{ $isSelected ? 'bg-brand-500 text-white border-brand-500 shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-750' }}"
                        >
                            <span class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold {{ $isSelected ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ strtoupper(substr($child->first_name ?? $child->full_name, 0, 1)) }}
                            </span>
                            <span>{{ $child->full_name }}</span>
                            @if ($child->bus)
                                <span class="text-[10px] opacity-80 font-mono">({{ $child->bus->bus_number }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @elseif (!$isParent && $buses->count() > 1)
                <!-- Multiple Buses Selector Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 custom-scrollbar">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 shrink-0">Select Bus:</span>
                    @foreach ($buses as $busItem)
                        @php
                            $isSelected = $selectedBus && $selectedBus->id === $busItem->id;
                        @endphp
                        <a
                            href="{{ route('live-tracking', ['bus_id' => $busItem->id]) }}"
                            class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold transition shrink-0 border {{ $isSelected ? 'bg-brand-500 text-white border-brand-500 shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-750' }}"
                        >
                            <span class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold {{ $isSelected ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                🚍
                            </span>
                            <span>Bus #{{ $busItem->bus_number }}</span>
                            <span class="text-[10px] opacity-80 font-mono">{{ $busItem->route?->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if (!$bus || !$route)
            <!-- Empty / Unassigned State Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-8 md:p-12 text-center shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">No Bus Route Assigned</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    @if ($isParent)
                        @if ($selectedChild)
                            <strong>{{ $selectedChild->full_name }}</strong> is currently not assigned to a school bus route.
                        @else
                            No children linked to your parent account are assigned to a bus route.
                        @endif
                    @else
                        No bus in your scope is currently assigned to a route with configured stops.
                    @endif
                    Please contact your school administration to configure bus transport.
                </p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-600">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @else

            <!-- Telemetry & Information Cards Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">

                <!-- Card 1: Bus Status & Speed -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Bus Status</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span id="liveBusStatusBadge" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ $telemetry['is_moving'] ? 'Moving' : 'Stopped' }}
                        </span>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white font-mono">
                            <span id="liveBusSpeed">{{ round($telemetry['speed']) }}</span> <span class="text-xs font-normal text-gray-500 dark:text-gray-400">km/h</span>
                        </p>
                        <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500">
                            Last updated: <span id="liveLastUpdateText">{{ $telemetry['last_updated_ago'] ?? 'Just now' }}</span>
                        </p>
                        <p class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold font-mono {{ $telemetry['hasLive'] === 'live' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : ($telemetry['hasLive'] === 'db' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400') }}">
                            <span id="liveDataSourceBadge">
                                {{ $telemetry['hasLive'] === 'live' ? 'Live (NazarTrack)' : ($telemetry['hasLive'] === 'db' ? 'Last known (Database)' : 'No data') }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Card 2: Next Stop & ETA -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Next Stop & ETA</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p id="liveNextStopCardText" class="text-sm font-bold text-brand-600 dark:text-brand-400 truncate">
                            {{ $route->stops->skip(1)->first()->name ?? $route->end_location }}
                        </p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white font-mono">
                            <span id="liveEtaCardText">Computing…</span>
                        </p>
                        <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500 flex items-center justify-between">
                            <span>Distance:</span>
                            <span id="liveDistCardText" class="font-mono font-semibold text-gray-700 dark:text-gray-300">—</span>
                        </p>
                    </div>
                </div>

                <!-- Card 3: Route Details -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Assigned Route</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                            {{ $route->name }}
                        </p>
                        <p class="mt-1 text-xs font-mono font-semibold text-purple-600 dark:text-purple-400">
                            Code: {{ $route->route_code }}
                        </p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400 truncate">
                            Start: {{ $route->start_location }} → End: {{ $route->end_location }}
                        </p>
                    </div>
                </div>

                <!-- Card 4: Bus & Driver Details -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Bus & Driver</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Bus #{{ $bus->bus_number }}</span>
                            @if ($bus->registration_number)
                                <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-mono text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    {{ $bus->registration_number }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                            Driver: {{ $bus->driver?->full_name ?? 'Assigned Driver' }}
                        </p>
                        @if ($bus->driver?->phone)
                            <a
                                href="tel:{{ $bus->driver->phone }}"
                                class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Call {{ $bus->driver->phone }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Card 5: Child Transport Info (Parent) / School Transport (Others) -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $isParent ? 'Child Info' : 'School Transport' }}</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if ($isParent)
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $selectedChild->full_name }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ trim($selectedChild->grade.' '.$selectedChild->section) }} • Roll #{{ $selectedChild->roll_no ?? '—' }}
                            </p>
                            <p class="mt-2 text-[11px] text-gray-600 dark:text-gray-300 truncate">
                                Stop: <strong>{{ $selectedChild->pickup_location ?? ($route->stops->first()->name ?? 'Assigned Stop') }}</strong>
                            </p>
                        @else
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $bus->school?->name ?? '—' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $route->stops->count() }} stops configured on this route
                            </p>
                            <p class="mt-2 text-[11px] text-gray-600 dark:text-gray-300 truncate">
                                Route Code: <strong>{{ $route->route_code }}</strong>
                            </p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Main Content: Route Map (Left) & Bus Stops Timeline (Right) -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Live Bus Route Map -->
                <div class="xl:col-span-7 rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Live Route Map</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Road navigation path, live position marker & stops</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live Tracking Active
                            </span>
                        </div>
                    </div>

                    <!-- Map Container with Floating Controls -->
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-900" style="min-height: 480px;">
                        <!-- Map Canvas -->
                        <div id="routeMapCanvas" class="h-[480px] w-full z-0"></div>

                        <!-- Floating Controls -->
                        <div class="absolute top-4 right-4 z-10 flex flex-col gap-2">
                            <!-- Recenter Camera -->
                            <button
                                type="button"
                                id="recenterBusBtn"
                                onclick="recenterCameraOnBus()"
                                title="Recenter Camera on Bus"
                                class="flex items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-xs font-semibold text-gray-800 shadow-md backdrop-blur-md transition hover:bg-brand-500 hover:text-white dark:bg-gray-900/95 dark:text-gray-200 dark:hover:bg-brand-500 dark:hover:text-white border border-gray-200 dark:border-gray-700"
                            >
                                <svg class="h-4 w-4 text-brand-500 dark:text-brand-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span>Recenter Bus</span>
                            </button>

                            <!-- Fit Route Bounds -->
                            <button
                                type="button"
                                onclick="fitFullRouteBounds()"
                                title="Fit Full Route"
                                class="flex items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-xs font-semibold text-gray-800 shadow-md backdrop-blur-md transition hover:bg-gray-100 dark:bg-gray-900/95 dark:text-gray-200 dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-700"
                            >
                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                                <span>Fit Full Route</span>
                            </button>
                        </div>

                        <!-- Legend Overlay -->
                        <div class="absolute bottom-4 left-4 z-10 hidden sm:flex items-center gap-3 rounded-xl bg-white/90 p-3 text-xs shadow-lg backdrop-blur-md dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800">
                            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                                <span class="h-3 w-3 rounded-full bg-emerald-500 inline-block"></span>
                                Start: <strong class="text-gray-900 dark:text-white">{{ $route->start_location }}</strong>
                            </div>
                            <div class="h-3 w-px bg-gray-300 dark:bg-gray-700"></div>
                            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                                <span class="h-3 w-3 rounded-full bg-brand-500 inline-block"></span>
                                Stops: <strong class="text-gray-900 dark:text-white">{{ $route->stops->count() }} Configured</strong>
                            </div>
                            <div class="h-3 w-px bg-gray-300 dark:bg-gray-700"></div>
                            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                                <span class="h-3 w-3 rounded-full bg-rose-500 inline-block"></span>
                                End: <strong class="text-gray-900 dark:text-white">{{ $route->end_location }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Bus Stops Vertical Timeline ("Where Is My Bus") -->
                <div class="xl:col-span-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">

                    <!-- Timeline Section Header -->
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bus Stops Timeline</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Real-time stop completion & ETAs</p>
                            </div>
                        </div>

                        <span id="journeyStatusPill" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            In Transit
                        </span>
                    </div>

                    <!-- Journey Progress Bar Card -->
                    <div class="mb-5 rounded-xl bg-gray-50/80 p-4 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/60">
                        <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                            <span class="text-gray-600 dark:text-gray-400">Overall Route Progress</span>
                            <span id="progressBarLabel" class="text-brand-600 dark:text-brand-400 font-mono">Waiting for GPS…</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div
                                id="journeyProgressBar"
                                class="h-full rounded-full bg-gradient-to-r from-brand-500 to-emerald-500 transition-all duration-500 ease-out"
                                style="width: 0%;"
                            ></div>
                        </div>
                    </div>

                    <!-- Vertical Journey Timeline List -->
                    <div class="relative pl-2 pr-1 py-1 max-h-[440px] overflow-y-auto custom-scrollbar">
                        <div class="space-y-5" id="journeyTimelineContainer">
                            @forelse ($route->stops as $index => $stop)
                                @php
                                    $isFirst = $index === 0;
                                    $isLast = $index === ($route->stops->count() - 1);
                                @endphp

                                <div
                                    id="journeyStopItem-{{ $index }}"
                                    class="journey-stop-row relative flex items-start gap-3.5 transition-all duration-300"
                                    data-stop-index="{{ $index }}"
                                >
                                    <!-- Node Marker & Connector Line -->
                                    <div class="relative flex flex-col items-center">
                                        <!-- Node Icon -->
                                        <div
                                            id="stopNodeIcon-{{ $index }}"
                                            class="node-icon flex h-8 w-8 items-center justify-center rounded-full border-2 transition-all duration-300 z-10 {{ $isFirst ? 'bg-brand-500 border-brand-500 text-white ring-4 ring-brand-500/20' : 'bg-white border-gray-300 text-gray-400 dark:bg-gray-900 dark:border-gray-700' }}"
                                        >
                                            @if ($isFirst)
                                                <span class="text-xs">🚌</span>
                                            @else
                                                <span class="text-xs font-mono font-bold">{{ $stop->stop_order }}</span>
                                            @endif
                                        </div>

                                        <!-- Vertical Connector Line -->
                                        @if (!$isLast)
                                            <div
                                                id="stopConnectorLine-{{ $index }}"
                                                class="connector-line w-0.5 h-12 bg-gray-200 dark:bg-gray-800 transition-colors duration-500 my-1"
                                            ></div>
                                        @endif
                                    </div>

                                    <!-- Stop Info Box -->
                                    <div
                                        id="stopCardBox-{{ $index }}"
                                        class="stop-card-box flex-1 rounded-xl p-3 border transition-all duration-300 {{ $isFirst ? 'bg-brand-50/60 border-brand-200 dark:bg-brand-500/10 dark:border-brand-500/30' : 'bg-gray-50/50 border-gray-100 dark:bg-gray-800/30 dark:border-gray-800/60' }}"
                                    >
                                        <div class="flex flex-wrap items-center justify-between gap-1.5">
                                            <div class="flex items-center gap-2">
                                                <h3 id="stopTitleText-{{ $index }}" class="text-xs font-bold {{ $isFirst ? 'text-brand-700 dark:text-brand-300' : 'text-gray-900 dark:text-white' }}">
                                                    {{ $stop->name }}
                                                </h3>

                                                <span
                                                    id="stopBadge-{{ $index }}"
                                                    class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $isFirst ? 'bg-brand-500 text-white animate-pulse' : 'hidden' }}"
                                                >
                                                    @if ($isFirst)
                                                        <span>Current Stop</span>
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-[11px] font-mono font-semibold text-gray-700 dark:text-gray-300">
                                                    Sched: {{ $stop->pickup_time ? date('h:i A', strtotime($stop->pickup_time)) : ($stop->drop_time ? date('h:i A', strtotime($stop->drop_time)) : '07:00 AM') }}
                                                </div>
                                                <div id="stopEtaText-{{ $index }}" class="text-[11px] font-mono font-semibold text-brand-600 dark:text-brand-400">
                                                    {{ $isFirst ? 'Bus Arriving' : 'ETA: —' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                            <span>Stop #{{ $stop->stop_order }}</span>
                                            <span id="stopDistanceText-{{ $index }}" class="font-mono">
                                                {{ $isFirst ? 'Distance: —' : 'Approx — km away' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-6">
                                    No route stops configured for this bus route.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        @endif

    </div>
</x-app-layout>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Bus Marker Container */
    .bus-marker-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bus-marker-svg {
        width: 36px;
        height: 36px;
        filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.4));
    }

    /* Stop Marker Pin Styling */
    .stop-marker-pin {
        background: linear-gradient(135deg, #4F46E5 0%, #3730A3 100%);
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 11px;
        border: 2px solid #FFFFFF;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }
</style>

@if ($route)
<script>
    let map = null;
    let busMarker = null;
    let fullPolyline = null;
    let routeCoordinates = [];
    let fullRouteBounds = null;
    let autoFollowCamera = true;

    const liveJourneyStops = @json($route->stops);
    let liveJourneyLastIdx = null;

    const busNumber = @json($bus->bus_number);
    const assetUrl = @json(route('live-tracking.asset', ['bus_id' => $bus->id]));

    function parseCoord(value) {
        const n = parseFloat(value);
        return isFinite(n) ? n : null;
    }

    document.addEventListener('DOMContentLoaded', function () {
        initHighPerformanceMap();
    });

    async function initHighPerformanceMap() {
        const startName = @json($route->start_location);
        const endName = @json($route->end_location);
        const stopsData = @json($route->stops);

        // Extract key waypoints with fallback Kathmandu/Nepal defaults if missing
        let waypoints = stopsData
            .filter(s => s.latitude && s.longitude && parseFloat(s.latitude) !== 0 && parseFloat(s.longitude) !== 0)
            .map(s => [parseFloat(s.latitude), parseFloat(s.longitude), s.name, s.stop_order]);

        if (waypoints.length === 0) {
            waypoints = [
                [27.7172, 85.3240, startName || 'Start Station', 1],
                [27.7220, 85.3300, 'Stop 1 - City Center', 2],
                [27.7280, 85.3380, 'Stop 2 - Park Square', 3],
                [27.7340, 85.3450, endName || 'End Station', 4]
            ];
        }

        const boundsPoints = waypoints.map(w => [w[0], w[1]]);
        fullRouteBounds = L.latLngBounds(boundsPoints);

        // Initialize Leaflet Map
        map = L.map('routeMapCanvas', {
            preferCanvas: true,
            updateWhenIdle: true,
            keepBuffer: 1,
            zoomAnimation: true,
            maxBoundsViscosity: 0.8
        });

        map.fitBounds(fullRouteBounds, { padding: [50, 50], maxZoom: 16 });

        // CartoDB Voyager tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd',
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>'
        }).addTo(map);

        map.on('movestart dragstart zoomstart touchstart', function (e) {
            if (e.originalEvent) {
                autoFollowCamera = false;
                updateRecenterButtonState(false);
            }
        });

        // Add Stop Pin Markers
        waypoints.forEach(point => {
            const [lat, lng, name, order] = point;
            const stopIcon = L.divIcon({
                className: 'custom-stop-pin',
                html: `<div class="stop-marker-pin">${order}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            L.marker([lat, lng], { icon: stopIcon })
                .addTo(map)
                .bindPopup(`<b>Stop #${order}: ${name}</b>`);
        });

        // Fetch OSRM Road Polyline
        routeCoordinates = await fetchRoadPolyline(waypoints);

        if (routeCoordinates.length > 0) {
            // Background glow polyline
            L.polyline(routeCoordinates, {
                color: '#6366F1',
                weight: 8,
                opacity: 0.35,
                lineCap: 'round'
            }).addTo(map);

            // Foreground crisp polyline
            fullPolyline = L.polyline(routeCoordinates, {
                color: '#4F46E5',
                weight: 4,
                opacity: 0.9,
                lineCap: 'round'
            }).addTo(map);

            fullRouteBounds = fullPolyline.getBounds();
            map.fitBounds(fullRouteBounds, { padding: [50, 50] });
        }

        // Seed GPS from server telemetry (live API or DB fallback)
        const t = @json($telemetry);
        const lat = parseCoord(t.latitude);
        const lng = parseCoord(t.longitude);

        window.liveBusGps = {
            latitude: lat,
            longitude: lng,
            speed: t.speed || 0,
            heading: 0,
            timestamp: Date.now()
        };

        if (lat && lng) {
            initBusMarker([lat, lng]);
            map.panTo([lat, lng], { animate: true });
            updateLiveJourneyState(window.liveBusGps);
        }

        updateTelemetryCard(window.liveBusGps, t);

        // Start 5s polling loop
        pollLiveAsset();
    }

    async function fetchRoadPolyline(waypoints) {
        try {
            const coordStr = waypoints.map(w => `${w[1]},${w[0]}`).join(';');
            const url = `https://router.project-osrm.org/route/v1/driving/${coordStr}?overview=full&geometries=geojson`;

            const response = await fetch(url);
            const data = await response.json();

            if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                return data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
            }
        } catch (err) {
            console.warn('OSRM routing failed, falling back to interpolated waypoints:', err);
        }

        return interpolatePoints(waypoints.map(w => [w[0], w[1]]));
    }

    function interpolatePoints(points) {
        let result = [];
        for (let i = 0; i < points.length - 1; i++) {
            const p1 = points[i];
            const p2 = points[i + 1];
            const steps = 15;
            for (let s = 0; s < steps; s++) {
                const ratio = s / steps;
                result.push([
                    p1[0] + (p2[0] - p1[0]) * ratio,
                    p1[1] + (p2[1] - p1[1]) * ratio
                ]);
            }
        }
        result.push(points[points.length - 1]);
        return result;
    }

    function initBusMarker(latLng) {
        const busSvgHtml = `
            <div class="bus-marker-container">
                <svg class="bus-marker-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="30" fill="#4F46E5" fill-opacity="0.25" />
                    <circle cx="32" cy="32" r="24" fill="#312E81" stroke="#FFFFFF" stroke-width="3" />
                    <rect x="18" y="20" width="28" height="24" rx="4" fill="#F59E0B" />
                    <rect x="21" y="23" width="22" height="8" rx="2" fill="#1E293B" />
                    <circle cx="23" cy="40" r="3" fill="#000000" />
                    <circle cx="41" cy="40" r="3" fill="#000000" />
                    <polygon points="32,6 38,18 26,18" fill="#10B981" />
                </svg>
            </div>
        `;

        const busIcon = L.divIcon({
            className: 'bus-marker-wrap',
            html: busSvgHtml,
            iconSize: [44, 44],
            iconAnchor: [22, 22]
        });

        busMarker = L.marker(latLng, {
            icon: busIcon,
            zIndexOffset: 1000
        }).addTo(map);

        busMarker.bindPopup(`<b>School Bus #${busNumber}</b><br>Live position`);
    }

    function moveBusMarker(gps) {
        if (!gps.latitude || !gps.longitude) return;

        if (!busMarker) {
            initBusMarker([gps.latitude, gps.longitude]);
        }

        busMarker.setLatLng([gps.latitude, gps.longitude]);

        if (autoFollowCamera && map) {
            map.panTo([gps.latitude, gps.longitude], { animate: true, duration: 0.3 });
        }
    }

    function updateTelemetryCard(gps, t) {
        const speedEl = document.getElementById('liveBusSpeed');
        if (speedEl) speedEl.innerText = Math.round(gps.speed || 0);

        const statusBadge = document.getElementById('liveBusStatusBadge');
        if (statusBadge) {
            const moving = (t && t.is_moving) || (gps.speed > 0);
            statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold border ' + (moving
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-800/40'
                : 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-800/40');
            statusBadge.innerHTML = '<span class="h-1.5 w-1.5 rounded-full ' + (moving ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500') + '"></span> ' + (moving ? 'Moving' : 'Stopped');
        }

        const timeEl = document.getElementById('liveLastUpdateText');
        if (timeEl) timeEl.innerText = (t && t.last_updated_ago) ? t.last_updated_ago : 'Just now';

        const srcBadge = document.getElementById('liveDataSourceBadge');
        if (srcBadge && t) {
            srcBadge.textContent = t.hasLive === 'live' ? 'Live (NazarTrack)' : (t.hasLive === 'db' ? 'Last known (Database)' : 'No data');
        }
    }

    async function pollLiveAsset() {
        try {
            const response = await fetch(assetUrl);
            const data = await response.json();
            const t = data && data.telemetry;
            if (!t) return;

            const lat = parseCoord(t.latitude);
            const lng = parseCoord(t.longitude);

            window.liveBusGps = window.liveBusGps || {};
            window.liveBusGps.latitude = lat;
            window.liveBusGps.longitude = lng;
            window.liveBusGps.speed = t.speed || 0;
            window.liveBusGps.timestamp = Date.now();

            moveBusMarker(window.liveBusGps);
            updateTelemetryCard(window.liveBusGps, t);

            if (lat && lng) {
                updateLiveJourneyState(window.liveBusGps);
            }
        } catch (err) {
            console.warn('Live tracking poll failed:', err);
        } finally {
            setTimeout(pollLiveAsset, 5000);
        }
    }

    function liveGpsDistanceKm(lat1, lon1, lat2, lon2) {
        const toRad = (deg) => (deg * Math.PI) / 180;
        const earthRadiusKm = 6371;
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat / 2) ** 2
            + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
        return earthRadiusKm * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function liveCurrentStopIndex(gps) {
        let nearestIdx = -1;
        let nearestDist = Infinity;

        for (let i = 0; i < liveJourneyStops.length; i++) {
            const stop = liveJourneyStops[i];
            if (stop.latitude && stop.longitude && parseFloat(stop.latitude) !== 0 && parseFloat(stop.longitude) !== 0) {
                const dist = liveGpsDistanceKm(gps.latitude, gps.longitude, parseFloat(stop.latitude), parseFloat(stop.longitude));
                if (dist < nearestDist) {
                    nearestDist = dist;
                    nearestIdx = i;
                }
            }
        }

        return nearestIdx >= 0 ? nearestIdx : 0;
    }

    function updateLiveJourneyState(gps) {
        const totalStops = liveJourneyStops.length;
        if (totalStops === 0 || !gps || !gps.latitude || !gps.longitude) return;

        let currentIdx = liveCurrentStopIndex(gps);
        currentIdx = Math.min(currentIdx, totalStops - 1);

        if (liveJourneyLastIdx !== null && currentIdx < liveJourneyLastIdx) {
            currentIdx = liveJourneyLastIdx;
        }
        liveJourneyLastIdx = currentIdx;

        // Progress Bar
        const clampedPercent = Math.min(Math.max(Math.round(((currentIdx + 1) / totalStops) * 100), 5), 100);
        const progressBar = document.getElementById('journeyProgressBar');
        const progressLabel = document.getElementById('progressBarLabel');
        if (progressBar) progressBar.style.width = clampedPercent + '%';
        if (progressLabel) progressLabel.innerText = clampedPercent + '% Completed';

        // Next Stop & Card ETAs
        const nextStop = liveJourneyStops[currentIdx + 1] ? liveJourneyStops[currentIdx + 1].name : (liveJourneyStops[currentIdx] ? liveJourneyStops[currentIdx].name : 'Destination');

        const cardNextStop = document.getElementById('liveNextStopCardText');
        const cardEta = document.getElementById('liveEtaCardText');
        const cardDist = document.getElementById('liveDistCardText');

        if (cardNextStop) cardNextStop.innerText = nextStop;

        const minsLeft = Math.max(0, (totalStops - 1 - currentIdx) * 5);
        if (cardEta) cardEta.innerText = minsLeft === 0 ? 'Arrived' : 'In ' + minsLeft + ' mins';
        if (cardDist) {
            const approxKm = ((totalStops - 1 - currentIdx) * 1.5).toFixed(1);
            cardDist.innerText = approxKm + ' km away';
        }

        // Timeline Stops Loop
        for (let i = 0; i < totalStops; i++) {
            const iconNode = document.getElementById('stopNodeIcon-' + i);
            const connector = document.getElementById('stopConnectorLine-' + i);
            const cardBox = document.getElementById('stopCardBox-' + i);
            const titleText = document.getElementById('stopTitleText-' + i);
            const badge = document.getElementById('stopBadge-' + i);
            const etaText = document.getElementById('stopEtaText-' + i);
            const distText = document.getElementById('stopDistanceText-' + i);

            if (i < currentIdx) {
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 border-emerald-500 text-white z-10 shadow-xs';
                    iconNode.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
                }
                if (connector) connector.className = 'connector-line w-0.5 h-12 bg-emerald-500 transition-colors duration-500 my-1';
                if (cardBox) cardBox.className = 'stop-card-box flex-1 rounded-xl p-3 border bg-emerald-50/30 border-emerald-100 dark:bg-emerald-500/5 dark:border-emerald-800/30 opacity-75';
                if (titleText) titleText.className = 'text-xs font-semibold text-gray-500 dark:text-gray-400 line-through';
                if (badge) badge.className = 'hidden';
                if (etaText) {
                    etaText.className = 'text-[11px] font-mono font-semibold text-emerald-600 dark:text-emerald-400';
                    etaText.innerText = 'Passed';
                }
                if (distText) distText.innerText = 'Completed';

            } else if (i === currentIdx) {
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-8 w-8 items-center justify-center rounded-full bg-brand-500 border-2 border-white text-white z-10 ring-4 ring-brand-500/30 animate-bounce shadow-md';
                    iconNode.innerHTML = '<span class="text-xs">🚌</span>';
                }
                if (connector) connector.className = 'connector-line w-0.5 h-12 bg-brand-300 dark:bg-brand-800 transition-colors duration-500 my-1';
                if (cardBox) cardBox.className = 'stop-card-box flex-1 rounded-xl p-3 border bg-brand-50/80 border-brand-300 dark:bg-brand-500/15 dark:border-brand-500/40 shadow-sm ring-1 ring-brand-500/20';
                if (titleText) titleText.className = 'text-xs font-bold text-brand-700 dark:text-brand-300';
                if (badge) {
                    badge.className = 'inline-flex items-center gap-1 rounded-full bg-brand-600 px-2 py-0.5 text-[10px] font-semibold text-white animate-pulse';
                    badge.innerHTML = '<span>Current Stop</span>';
                }
                if (etaText) {
                    etaText.className = 'text-[11px] font-mono font-bold text-brand-600 dark:text-brand-400';
                    etaText.innerText = 'Bus Arriving';
                }
                if (distText) distText.innerText = 'Distance: 0.1 km';

            } else {
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-8 w-8 items-center justify-center rounded-full bg-white border-2 border-gray-300 text-gray-400 dark:bg-gray-900 dark:border-gray-700 z-10';
                    iconNode.innerHTML = '<span class="text-xs font-mono font-bold">' + (i + 1) + '</span>';
                }
                if (connector) connector.className = 'connector-line w-0.5 h-12 bg-gray-200 dark:bg-gray-800 transition-colors duration-500 my-1';
                if (cardBox) cardBox.className = 'stop-card-box flex-1 rounded-xl p-3 border bg-gray-50/40 border-gray-100 dark:bg-gray-800/20 dark:border-gray-800/50';
                if (titleText) titleText.className = 'text-xs font-semibold text-gray-900 dark:text-white';
                if (badge) badge.className = 'hidden';
                if (etaText) {
                    const upcomingMins = (i - currentIdx) * 5;
                    etaText.className = 'text-[11px] font-mono font-medium text-gray-600 dark:text-gray-400';
                    etaText.innerText = 'ETA: ' + upcomingMins + ' mins';
                }
                if (distText) {
                    const approxKm = ((i - currentIdx) * 1.8).toFixed(1);
                    distText.innerText = 'Approx ' + approxKm + ' km away';
                }
            }
        }
    }

    function recenterCameraOnBus() {
        if (busMarker && map) {
            autoFollowCamera = true;
            const currentPos = busMarker.getLatLng();
            map.flyTo(currentPos, Math.max(map.getZoom(), 15), { duration: 0.8 });
            updateRecenterButtonState(true);
        }
    }

    function fitFullRouteBounds() {
        if (fullRouteBounds && map) {
            autoFollowCamera = false;
            updateRecenterButtonState(false);
            map.fitBounds(fullRouteBounds, { padding: [50, 50], animate: true, duration: 0.8 });
        }
    }

    function updateRecenterButtonState(isActive) {
        const btn = document.getElementById('recenterBusBtn');
        if (btn) {
            if (isActive) {
                btn.classList.add('ring-2', 'ring-brand-500', 'bg-brand-50');
            } else {
                btn.classList.remove('ring-2', 'ring-brand-500', 'bg-brand-50');
            }
        }
    }
</script>
@endif

<!--
    ---------------------------------------------------------------------------
    DEBUG / RAW LIVE ASSETS (commented out for troubleshooting)
    ---------------------------------------------------------------------------
    @if (isset($liveData) && isset($buses))
    <h2>Live Tracking</h2>
    <p><strong>Success:</strong> {{ $liveData['success'] ? 'Yes' : 'No' }}</p>
    <p><strong>Company ID:</strong> {{ $liveData['company_id'] }}</p>
    <p><strong>Total Assets:</strong> {{ $liveData['count'] }}</p>
    <hr>
    @forelse ($liveData['data'] as $asset)
        @php($bus = $buses[$asset['imei']] ?? null)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <p><strong>Asset:</strong> {{ $asset['asset_name'] }}</p>
            @if ($bus)
                <p><strong>Bus:</strong> {{ $bus->bus_number }} <small>({{ $bus->registration_number }})</small></p>
                <p><strong>Route:</strong> {{ $bus->route?->name ?? '—' }}</p>
                <p><strong>Driver:</strong> {{ $bus->driver?->full_name ?? '—' }}</p>
                <p><strong>School:</strong> {{ $bus->school?->name ?? '—' }}</p>
            @endif
            <p><strong>Plate:</strong> {{ $asset['plate_number'] }}</p>
            <p><strong>Status:</strong> {{ $asset['status_label'] }}</p>
            <p><strong>Latitude:</strong> {{ $asset['latitude'] ?? 'N/A' }}</p>
            <p><strong>Longitude:</strong> {{ $asset['longitude'] ?? 'N/A' }}</p>
            <p><strong>Speed:</strong> {{ $asset['speed_kmh'] }} km/h</p>
            <p><strong>IMEI:</strong> {{ $asset['imei'] }}</p>
            <p><strong>Marker Color:</strong> {{ $asset['marker']['color'] }}</p>
            <p><strong>Moving:</strong> {{ $asset['is_moving'] ? 'Yes' : 'No' }}</p>
        </div>
    @empty
        <p>No live assets match the buses you have access to.</p>
    @endforelse
    @endif
-->
