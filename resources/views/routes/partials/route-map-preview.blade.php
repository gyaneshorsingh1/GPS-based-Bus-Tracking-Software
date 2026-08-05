<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Route Preview</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Map visualization of route path and stops</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Interactive Preview
            </span>
        </div>
    </div>

    <!-- Map Canvas Container -->
    <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-100 dark:bg-gray-900" style="min-height: 380px;">
        <div id="routeMapCanvas" class="h-[380px] w-full z-0"></div>

        <!-- Floating Route Details Legend -->
        <div class="absolute bottom-4 left-4 z-10 hidden sm:flex items-center gap-3 rounded-xl bg-white/90 p-3 text-xs shadow-lg backdrop-blur-md dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800">
            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-full bg-green-500 inline-block"></span>
                Start: <strong class="text-gray-900 dark:text-white">{{ $route->start_location }}</strong>
            </div>
            <div class="h-3 w-px bg-gray-300 dark:bg-gray-700"></div>
            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-full bg-brand-500 inline-block"></span>
                Stops: <strong class="text-gray-900 dark:text-white">{{ $route->stops->count() }} Configured</strong>
            </div>
            <div class="h-3 w-px bg-gray-300 dark:bg-gray-700"></div>
            <div class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-full bg-red-500 inline-block"></span>
                End: <strong class="text-gray-900 dark:text-white">{{ $route->end_location }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet.js Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Collect coordinates from PHP
        const startName = @json($route->start_location);
        const endName = @json($route->end_location);
        const stopsData = @json($route->stops);

        // Filter valid lat/lng stops or create fallback layout
        let validPoints = stopsData
            .filter(s => s.latitude && s.longitude && s.latitude != 0 && s.longitude != 0)
            .map(s => [parseFloat(s.latitude), parseFloat(s.longitude), s.name, s.stop_order]);

        // Default map center (Kathmandu / Central coordinate fallback if none specified)
        let defaultLat = 27.7172;
        let defaultLng = 85.3240;

        if (validPoints.length > 0) {
            defaultLat = validPoints[0][0];
            defaultLng = validPoints[0][1];
        }

        const map = L.map('routeMapCanvas').setView([defaultLat, defaultLng], 13);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const latLngs = [];

        if (validPoints.length > 0) {
            validPoints.forEach(point => {
                const [lat, lng, name, order] = point;
                latLngs.push([lat, lng]);

                // Create marker icon with stop sequence number
                const customIcon = L.divIcon({
                    className: 'custom-map-pin',
                    html: `<div style="background-color:#4F46E5; color:white; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; border:2px solid white; shadow:0 2px 4px rgba(0,0,0,0.3);">${order}</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                });

                L.marker([lat, lng], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(`<b>Stop #${order}: ${name}</b>`);
            });

            // Draw polyline connecting points
            if (latLngs.length > 1) {
                const polyline = L.polyline(latLngs, { color: '#4F46E5', weight: 4, opacity: 0.8, dashArray: '8, 8' }).addTo(map);
                map.fitBounds(polyline.getBounds(), { padding: [40, 40] });
            }
        } else {
            // Interactive fallback markers for preview when lat/long are default/empty
            const sampleLat = defaultLat;
            const sampleLng = defaultLng;

            const startIcon = L.divIcon({
                className: 'start-pin',
                html: `<div style="background-color:#10B981; color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:11px; border:2px solid white;">Start</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            const endIcon = L.divIcon({
                className: 'end-pin',
                html: `<div style="background-color:#EF4444; color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:11px; border:2px solid white;">End</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            L.marker([sampleLat, sampleLng], { icon: startIcon }).addTo(map).bindPopup(`<b>Start Location: ${startName}</b>`);
            L.marker([sampleLat + 0.015, sampleLng + 0.015], { icon: endIcon }).addTo(map).bindPopup(`<b>End Location: ${endName}</b>`);

            const line = L.polyline([[sampleLat, sampleLng], [sampleLat + 0.015, sampleLng + 0.015]], { color: '#4F46E5', weight: 4, dashArray: '6, 6' }).addTo(map);
            map.fitBounds(line.getBounds(), { padding: [50, 50] });
        }
    });
</script>
