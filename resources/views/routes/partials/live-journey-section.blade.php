<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Section Header -->
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Live Route Journey</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Real-time "Where Is My Bus" journey tracking & stop ETAs</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Bus In Transit
        </span>
    </div>

    <!-- 1. Summary Card -->
    <div class="mb-6 rounded-xl bg-gray-50/80 p-4 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/60">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <!-- Current Stop -->
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Current Stop</span>
                <p id="summaryCurrentStop" class="mt-1 text-sm font-bold text-brand-600 dark:text-brand-400 truncate">
                    {{ $route->stops->first()->name ?? 'Depot' }}
                </p>
            </div>

            <!-- Next Stop -->
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Next Stop</span>
                <p id="summaryNextStop" class="mt-1 text-sm font-bold text-gray-900 dark:text-white truncate">
                    {{ $route->stops->skip(1)->first()->name ?? '—' }}
                </p>
            </div>

            <!-- Progress Count -->
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Stops Completed</span>
                <p id="summaryProgressCount" class="mt-1 text-sm font-bold text-gray-900 dark:text-white font-mono">
                    1 / {{ max($route->stops->count(), 1) }} Stops
                </p>
            </div>

            <!-- Destination ETA -->
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Destination ETA</span>
                <p id="summaryDestinationEta" class="mt-1 text-sm font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                    Calculating...
                </p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4 pt-3 border-t border-gray-200/60 dark:border-gray-700/60">
            <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                <span class="text-gray-600 dark:text-gray-400">Journey Progress</span>
                <span id="progressBarLabel" class="text-brand-600 dark:text-brand-400 font-mono">15% Completed</span>
            </div>
            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                <div
                    id="journeyProgressBar"
                    class="h-full rounded-full bg-gradient-to-r from-brand-500 to-emerald-500 transition-all duration-500 ease-out"
                    style="width: 15%;"
                ></div>
            </div>
        </div>
    </div>

    <!-- 2. Vertical Journey Timeline ("Where Is My Train" Style) -->
    <div class="relative pl-3 pr-2 py-2">
        <div class="space-y-6" id="journeyTimelineContainer">
            @forelse ($route->stops as $index => $stop)
                @php
                    $isFirst = $index === 0;
                    $isLast = $index === ($route->stops->count() - 1);
                @endphp

                <div
                    id="journeyStopItem-{{ $index }}"
                    class="journey-stop-row relative flex items-start gap-4 transition-all duration-300"
                    data-stop-index="{{ $index }}"
                >
                    <!-- Connector Line & Node Marker -->
                    <div class="relative flex flex-col items-center">
                        <!-- Node Icon -->
                        <div
                            id="stopNodeIcon-{{ $index }}"
                            class="node-icon flex h-8 w-8 items-center justify-center rounded-full border-2 transition-all duration-300 z-10 {{ $isFirst ? 'bg-brand-500 border-brand-500 text-white ring-4 ring-brand-500/20' : 'bg-white border-gray-300 text-gray-400 dark:bg-gray-900 dark:border-gray-700' }}"
                        >
                            @if ($isFirst)
                                <!-- Default initial bus position icon -->
                                <span class="text-xs">🚌</span>
                            @else
                                <span class="text-xs font-mono font-bold">{{ $stop->stop_order }}</span>
                            @endif
                        </div>

                        <!-- Vertical Connector Line -->
                        @if (!$isLast)
                            <div
                                id="stopConnectorLine-{{ $index }}"
                                class="connector-line w-0.5 h-14 bg-gray-200 dark:bg-gray-800 transition-colors duration-500 my-1"
                            ></div>
                        @endif
                    </div>

                    <!-- Stop Info Card -->
                    <div
                        id="stopCardBox-{{ $index }}"
                        class="stop-card-box flex-1 rounded-xl p-3.5 border transition-all duration-300 {{ $isFirst ? 'bg-brand-50/60 border-brand-200 dark:bg-brand-500/10 dark:border-brand-500/30' : 'bg-gray-50/50 border-gray-100 dark:bg-gray-800/30 dark:border-gray-800/60' }}"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <h3 id="stopTitleText-{{ $index }}" class="text-sm font-bold {{ $isFirst ? 'text-brand-700 dark:text-brand-300' : 'text-gray-900 dark:text-white' }}">
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

                            <!-- Schedule & ETA Readout -->
                            <div class="text-right">
                                <div class="text-xs font-mono font-semibold text-gray-700 dark:text-gray-300">
                                    Sched: {{ $stop->pickup_time ? date('h:i A', strtotime($stop->pickup_time)) : ($stop->drop_time ? date('h:i A', strtotime($stop->drop_time)) : '07:00 AM') }}
                                </div>
                                <div id="stopEtaText-{{ $index }}" class="text-xs font-mono font-semibold text-brand-600 dark:text-brand-400">
                                    {{ $isFirst ? 'Bus Arriving' : 'ETA: ' . (($index * 6) + 3) . ' mins' }}
                                </div>
                            </div>
                        </div>

                        <!-- Distance / Sub-detail -->
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-between">
                            <span>Stop #{{ $stop->stop_order }}</span>
                            <span id="stopDistanceText-{{ $index }}" class="font-mono text-[11px]">
                                {{ $isFirst ? 'Distance: 0.2 km' : 'Approx ' . ($index * 1.5) . ' km away' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">
                    No route stops configured to render timeline.
                </p>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Live Synchronizer called by map tracking loop
    window.updateLiveJourneyState = function(currentIdx, progressPercent, speedKm) {
        const totalStops = {{ $route->stops->count() }};
        if (totalStops === 0) return;

        currentIdx = Math.min(currentIdx, totalStops - 1);

        // 1. Update Progress Bar & Label
        const clampedPercent = Math.min(Math.max(Math.round(progressPercent), 5), 100);
        const progressBar = document.getElementById('journeyProgressBar');
        const progressLabel = document.getElementById('progressBarLabel');
        
        if (progressBar) progressBar.style.width = clampedPercent + '%';
        if (progressLabel) progressLabel.innerText = clampedPercent + '% Completed';

        // 2. Update Summary Card Text
        const stopsList = @json($route->stops);
        const currentStop = stopsList[currentIdx] ? stopsList[currentIdx].name : 'In Transit';
        const nextStop = stopsList[currentIdx + 1] ? stopsList[currentIdx + 1].name : (stopsList[currentIdx] ? stopsList[currentIdx].name : 'Destination');

        const summaryCurrent = document.getElementById('summaryCurrentStop');
        const summaryNext = document.getElementById('summaryNextStop');
        const summaryProgress = document.getElementById('summaryProgressCount');
        const summaryEta = document.getElementById('summaryDestinationEta');

        if (summaryCurrent) summaryCurrent.innerText = currentStop;
        if (summaryNext) summaryNext.innerText = nextStop;
        if (summaryProgress) summaryProgress.innerText = (currentIdx + 1) + ' / ' + totalStops + ' Stops';
        
        // Calculate dynamic destination ETA
        const minsLeft = Math.max(0, (totalStops - 1 - currentIdx) * 5);
        if (summaryEta) {
            if (minsLeft === 0) {
                summaryEta.innerText = 'Arrived';
            } else {
                summaryEta.innerText = 'In ' + minsLeft + ' mins';
            }
        }

        // 3. Update Stop States in Timeline (Completed, Current, Upcoming)
        for (let i = 0; i < totalStops; i++) {
            const iconNode = document.getElementById('stopNodeIcon-' + i);
            const connector = document.getElementById('stopConnectorLine-' + i);
            const cardBox = document.getElementById('stopCardBox-' + i);
            const titleText = document.getElementById('stopTitleText-' + i);
            const badge = document.getElementById('stopBadge-' + i);
            const etaText = document.getElementById('stopEtaText-' + i);
            const distText = document.getElementById('stopDistanceText-' + i);

            if (i < currentIdx) {
                // State A: COMPLETED STOP
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 border-emerald-500 text-white z-10 shadow-xs';
                    iconNode.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
                }
                if (connector) {
                    connector.className = 'connector-line w-0.5 h-14 bg-emerald-500 transition-colors duration-500 my-1';
                }
                if (cardBox) {
                    cardBox.className = 'stop-card-box flex-1 rounded-xl p-3.5 border bg-emerald-50/30 border-emerald-100 dark:bg-emerald-500/5 dark:border-emerald-800/30 opacity-75';
                }
                if (titleText) {
                    titleText.className = 'text-sm font-semibold text-gray-500 dark:text-gray-400 line-through';
                }
                if (badge) {
                    badge.className = 'hidden';
                }
                if (etaText) {
                    etaText.className = 'text-xs font-mono font-semibold text-emerald-600 dark:text-emerald-400';
                    etaText.innerText = 'Passed';
                }
                if (distText) {
                    distText.innerText = 'Completed';
                }

            } else if (i === currentIdx) {
                // State B: CURRENT STOP (Animated Bus & Pulse Aura)
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-9 w-9 items-center justify-center rounded-full bg-brand-500 border-2 border-white text-white z-10 ring-4 ring-brand-500/30 animate-bounce shadow-md';
                    iconNode.innerHTML = '<span class="text-sm">🚌</span>';
                }
                if (connector) {
                    connector.className = 'connector-line w-0.5 h-14 bg-brand-300 dark:bg-brand-800 transition-colors duration-500 my-1';
                }
                if (cardBox) {
                    cardBox.className = 'stop-card-box flex-1 rounded-xl p-4 border bg-brand-50/80 border-brand-300 dark:bg-brand-500/15 dark:border-brand-500/40 shadow-sm ring-1 ring-brand-500/20';
                }
                if (titleText) {
                    titleText.className = 'text-base font-bold text-brand-700 dark:text-brand-300';
                }
                if (badge) {
                    badge.className = 'inline-flex items-center gap-1 rounded-full bg-brand-600 px-2 py-0.5 text-[10px] font-semibold text-white animate-pulse';
                    badge.innerHTML = '<span>Current Stop</span>';
                }
                if (etaText) {
                    etaText.className = 'text-xs font-mono font-bold text-brand-600 dark:text-brand-400';
                    etaText.innerText = 'Bus Arriving';
                }
                if (distText) {
                    distText.innerText = 'Distance: 0.1 km';
                }

            } else {
                // State C: UPCOMING STOP
                if (iconNode) {
                    iconNode.className = 'node-icon flex h-8 w-8 items-center justify-center rounded-full bg-white border-2 border-gray-300 text-gray-400 dark:bg-gray-900 dark:border-gray-700 z-10';
                    iconNode.innerHTML = '<span class="text-xs font-mono font-bold">' + (i + 1) + '</span>';
                }
                if (connector) {
                    connector.className = 'connector-line w-0.5 h-14 bg-gray-200 dark:bg-gray-800 transition-colors duration-500 my-1';
                }
                if (cardBox) {
                    cardBox.className = 'stop-card-box flex-1 rounded-xl p-3.5 border bg-gray-50/40 border-gray-100 dark:bg-gray-800/20 dark:border-gray-800/50';
                }
                if (titleText) {
                    titleText.className = 'text-sm font-semibold text-gray-900 dark:text-white';
                }
                if (badge) {
                    badge.className = 'hidden';
                }
                if (etaText) {
                    const upcomingMins = (i - currentIdx) * 5;
                    etaText.className = 'text-xs font-mono font-medium text-gray-600 dark:text-gray-400';
                    etaText.innerText = 'ETA: ' + upcomingMins + ' mins';
                }
                if (distText) {
                    const approxKm = ((i - currentIdx) * 1.8).toFixed(1);
                    distText.innerText = 'Approx ' + approxKm + ' km away';
                }
            }
        }
    };
</script>
