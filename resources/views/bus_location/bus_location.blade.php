<x-app-layout page="bus-location">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Bus Location</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Live GPS positions and telemetry for every bus in the fleet.</p>
            </div>
            <span id="lastUpdateBadge" class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"></span>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Buses</p>
                <p id="totalBuses" class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $locations->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-sm text-gray-500 dark:text-gray-400">Buses On Map</p>
                <p id="onMapCount" class="mt-1 text-2xl font-semibold text-emerald-600 dark:text-emerald-400">0</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-sm text-gray-500 dark:text-gray-400">Last Update</p>
                <p id="lastUpdateStat" class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">—</p>
            </div>
        </div>

        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Live Fleet Map</h2>
            </div>
            <div class="relative">
                <div id="busLocationMap" class="h-[480px] w-full"></div>
                <div class="absolute bottom-4 left-4 z-10 hidden items-center gap-3 rounded-xl bg-white/90 p-3 text-xs shadow-lg backdrop-blur-md sm:flex dark:bg-gray-900/90 dark:border dark:border-gray-800">
                    <span class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                        <span class="inline-block h-3 w-3 rounded-full bg-brand-500"></span> Online Bus
                    </span>
                    <span class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                        <span class="inline-block h-3 w-3 rounded-full bg-gray-400"></span> Offline
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-gray-800">
                    <tr class="text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">Bus</th>
                        <th class="px-5 py-3 font-medium">Route</th>
                        <th class="px-5 py-3 font-medium">Driver</th>
                        <th class="px-5 py-3 font-medium">School</th>
                        <th class="px-5 py-3 font-medium">Speed</th>
                        <th class="px-5 py-3 font-medium">Coordinates</th>
                        <th class="px-5 py-3 font-medium">Recorded At</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($locations as $location)
                        @php
                            $bus = $location->gpsDevice?->bus;
                            $lat = $location->latitude;
                            $lng = $location->longitude;
                            $isOnline = $lat && $lng && $location->recorded_at?->gt(now()->subMinutes(10));
                        @endphp
                        <tr
                            class="cursor-pointer text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800/40"
                            x-on:click="focusBus({{ $lat ?? 'null' }}, {{ $lng ?? 'null' }})"
                        >
                            <td class="px-5 py-3 font-medium">{{ $bus?->bus_number ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $bus?->route?->name ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $bus?->driver?->full_name ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $bus?->school?->name ?? '—' }}</td>
                            <td class="px-5 py-3 font-mono">{{ $location->speed }} km/h</td>
                            <td class="px-5 py-3 font-mono">{{ $lat ?? '—' }}, {{ $lng ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $location->recorded_at?->format('M d, Y H:i:s') ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if ($isOnline)
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Online</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Offline</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                                No bus locations recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const busLocations = @json($locations);

    function busMarkerHtml(label) {
        return `
            <div class="bus-marker-wrap">
                <div style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;background:#4F46E5;border:2px solid #fff;box-shadow:0 4px 6px rgba(0,0,0,0.3);color:#fff;font-size:10px;font-weight:700;text-align:center;line-height:1.2;padding:2px;white-space:nowrap;overflow:hidden;">${label}</div>
            </div>
        `;
    }

    function focusBus(lat, lng) {
        if (typeof liveMap === 'undefined' || !lat || !lng) return;
        liveMap.flyTo([lat, lng], 15, { duration: 0.8 });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const hasLocations = busLocations.some(l => l.latitude && l.longitude);
        const defaultCenter = hasLocations ? [busLocations.find(l => l.latitude && l.longitude).latitude, busLocations.find(l => l.latitude && l.longitude).longitude] : [27.7172, 85.3240];

        window.liveMap = L.map('busLocationMap', {
            preferCanvas: true,
            updateWhenIdle: true,
        }).setView(defaultCenter, hasLocations ? 12 : 12);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd',
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>'
        }).addTo(window.liveMap);

        let onMap = 0;
        const markers = [];

        busLocations.forEach(l => {
            if (!l.latitude || !l.longitude) return;

            const bus = l.gps_device?.bus;
            const online = l.recorded_at && new Date(l.recorded_at) > new Date(Date.now() - 10 * 60 * 1000);
            if (online) onMap++;

            const label = (bus?.bus_number || 'BUS').substring(0, 4).toUpperCase();
            const icon = L.divIcon({
                className: '',
                html: busMarkerHtml(label),
                iconSize: [40, 40],
                iconAnchor: [20, 20],
            });

            const marker = L.marker([l.latitude, l.longitude], { icon, zIndexOffset: online ? 1000 : 500 }).addTo(window.liveMap);

            marker.bindPopup(`
                <div style="font-family:inherit;min-width:180px;">
                    <div style="font-weight:700;font-size:14px;">${bus?.bus_number || 'Unknown Bus'}</div>
                    <div style="font-size:12px;color:#666;margin-top:2px;">${bus?.route?.name || 'No route assigned'}</div>
                    <div style="font-size:12px;color:#666;">Driver: ${bus?.driver?.full_name || '—'}</div>
                    <div style="font-size:12px;color:#666;">Speed: ${l.speed} km/h</div>
                    <div style="font-size:12px;color:#666;">Recorded: ${l.recorded_at || '—'}</div>
                </div>
            `);
            markers.push(marker);
        });

        if (markers.length > 1) {
            window.liveMap.fitBounds(L.featureGroup(markers).getBounds(), { padding: [50, 50] });
        }

        document.getElementById('onMapCount').textContent = onMap;
        document.getElementById('lastUpdateStat').textContent = busLocations.length ? (busLocations[0].recorded_at || '—') : '—';
        document.getElementById('lastUpdateBadge').textContent = busLocations.length ? `Updated ${busLocations[0].recorded_at || ''}` : 'No data yet';
    });
</script>
