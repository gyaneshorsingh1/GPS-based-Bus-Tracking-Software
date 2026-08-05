<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Header -->
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Route Preview & Live Bus Tracking</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Road-following navigation path, stop markers, and real-time telemetry</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Telemetry Pill -->
            <div id="telemetryPill" class="hidden sm:flex items-center gap-2 rounded-xl bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Speed: <strong id="busSpeed" class="text-gray-900 dark:text-white font-mono">32 km/h</strong></span>
                <span class="text-gray-300 dark:text-gray-600">•</span>
                <span>Next: <strong id="nextStopName" class="text-brand-600 dark:text-brand-400">Loading...</strong></span>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Road Navigation Active
            </span>
        </div>
    </div>

    <!-- Map Canvas Container with Floating Action Overlay -->
    <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-900" style="min-height: 420px;">
        
        <!-- Map Element -->
        <div id="routeMapCanvas" class="h-[440px] w-full z-0"></div>

        <!-- Floating Map Action Controls -->
        <div class="absolute top-4 right-4 z-10 flex flex-col gap-2">
            <!-- Recenter on Bus Button -->
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
                <span>Recenter on Bus</span>
            </button>

            <!-- Fit Full Route Button -->
            <button
                type="button"
                onclick="fitFullRouteBounds()"
                title="Fit Route to Screen"
                class="flex items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-xs font-semibold text-gray-800 shadow-md backdrop-blur-md transition hover:bg-gray-100 dark:bg-gray-900/95 dark:text-gray-200 dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-700"
            >
                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span>Fit Full Route</span>
            </button>

            <!-- Toggle Live Tracking Motion -->
            <button
                type="button"
                id="toggleTrackingBtn"
                onclick="toggleTrackingSimulation()"
                title="Toggle Live Tracking Simulation"
                class="flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-md transition hover:bg-emerald-700 border border-emerald-500/30"
            >
                <svg id="trackingIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="trackingBtnText">Pause Live Tracking</span>
            </button>
        </div>

        <!-- Floating Route Details Legend -->
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

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Custom Bus Marker Container */
    .bus-marker-container {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease-out;
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

<script>
    let map = null;
    let busMarker = null;
    let fullPolyline = null;
    let routeCoordinates = []; // Array of [lat, lng] along road
    let currentPathIndex = 0;
    let isTrackingActive = true;
    let autoFollowCamera = true;
    let animRequest = null;
    let fullRouteBounds = null;

    document.addEventListener('DOMContentLoaded', function () {
        initHighPerformanceMap();
    });

    async function initHighPerformanceMap() {
        const startName = @json($route->start_location);
        const endName = @json($route->end_location);
        const stopsData = @json($route->stops);

        // Extract key waypoints with fallback defaults (e.g. Kathmandu/Morang coordinates)
        let waypoints = stopsData
            .filter(s => s.latitude && s.longitude && s.latitude != 0 && s.longitude != 0)
            .map(s => [parseFloat(s.latitude), parseFloat(s.longitude), s.name, s.stop_order]);

        // Default fallback waypoints if no coordinates in DB
        if (waypoints.length === 0) {
            waypoints = [
                [27.7172, 85.3240, startName || 'Start Station', 1],
                [27.7220, 85.3300, 'Stop 1 - City Center', 2],
                [27.7280, 85.3380, 'Stop 2 - Park Square', 3],
                [27.7340, 85.3450, endName || 'End Station', 4]
            ];
        }

        // Compute tight LatLng bounds strictly scoped to route points
        const boundsPoints = waypoints.map(w => [w[0], w[1]]);
        fullRouteBounds = L.latLngBounds(boundsPoints);

        // Lightweight, performance-optimized Leaflet map initialization
        map = L.map('routeMapCanvas', {
            preferCanvas: true,        // GPU rendering for lines & markers
            updateWhenIdle: true,       // Lazy tile loading on scroll/pan
            keepBuffer: 1,              // Don't preload offscreen tiles
            zoomAnimation: true,
            maxBoundsViscosity: 0.8
        });

        // Fit map bounds strictly to the visible area right away to load only local tiles
        map.fitBounds(fullRouteBounds, { padding: [50, 50], maxZoom: 16 });

        // Add CartoDB Voyager tiles (Fast CDN, clean aesthetic, lazy loaded)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd',
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>'
        }).addTo(map);

        // Detect user manual map interaction to pause auto-camera tracking smoothly
        map.on('movestart dragstart zoomstart touchstart', function (e) {
            if (e.originalEvent) {
                autoFollowCamera = false;
                updateRecenterButtonState(false);
            }
        });

        // Add Stop Pins
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

        // Fetch Road-Following Geometry via OSRM Routing API
        routeCoordinates = await fetchRoadPolyline(waypoints);

        // Render sleek polyline along actual roads
        if (routeCoordinates.length > 0) {
            // Background glow line
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

            // Re-fit bounds including full road geometry
            fullRouteBounds = fullPolyline.getBounds();
            map.fitBounds(fullRouteBounds, { padding: [50, 50] });

            // Initialize Live Bus Marker at first route point
            initBusMarker(routeCoordinates[0]);

            // Start Live Tracking Motion
            startBusTrackingLoop();
        }
    }

    // Fetch actual road network polyline from OSRM Routing Engine
    async function fetchRoadPolyline(waypoints) {
        try {
            // Build OSRM coordinates query string: lng,lat;lng,lat
            const coordStr = waypoints.map(w => `${w[1]},${w[0]}`).join(';');
            const url = `https://router.project-osrm.org/route/v1/driving/${coordStr}?overview=full&geometries=geojson`;

            const response = await fetch(url);
            const data = await response.json();

            if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                // Convert GeoJSON [lng, lat] coordinates to Leaflet [lat, lng]
                return data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
            }
        } catch (err) {
            console.warn('OSRM routing request failed, falling back to direct waypoints:', err);
        }

        // Direct interpolation fallback if routing API is offline
        return interpolatePoints(waypoints.map(w => [w[0], w[1]]));
    }

    // Fallback point interpolator for smooth curves
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

    // Initialize Bus SVG Marker with pulse effect
    function initBusMarker(startLatLng) {
        const busSvgHtml = `
            <div id="busMarkerInner" class="bus-marker-container">
                <svg class="bus-marker-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Outer Pulse Aura -->
                    <circle cx="32" cy="32" r="30" fill="#4F46E5" fill-opacity="0.25" />
                    <!-- Outer Ring -->
                    <circle cx="32" cy="32" r="24" fill="#312E81" stroke="#FFFFFF" stroke-width="3" />
                    <!-- Bus Body SVG -->
                    <rect x="18" y="20" width="28" height="24" rx="4" fill="#F59E0B" />
                    <rect x="21" y="23" width="22" height="8" rx="2" fill="#1E293B" />
                    <circle cx="23" cy="40" r="3" fill="#000000" />
                    <circle cx="41" cy="40" r="3" fill="#000000" />
                    <!-- Heading Direction Indicator Arrow -->
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

        busMarker = L.marker(startLatLng, {
            icon: busIcon,
            zIndexOffset: 1000 // Keep bus on top of route & stops
        }).addTo(map);

        busMarker.bindPopup('<b>Live School Bus #104</b><br>Speed: 32 km/h • On Route');
    }

    // Continuous Live Tracking Loop with Smooth Interpolation & Heading Rotation
    function startBusTrackingLoop() {
        if (!isTrackingActive || routeCoordinates.length < 2) return;

        let p1 = routeCoordinates[currentPathIndex];
        let p2 = routeCoordinates[(currentPathIndex + 1) % routeCoordinates.length];

        // Compute angle/heading between points
        let angle = Math.atan2(p2[1] - p1[1], p2[0] - p1[0]) * 180 / Math.PI;

        // Move marker
        if (busMarker) {
            busMarker.setLatLng(p1);

            // Rotate bus marker smoothly
            const innerEl = document.getElementById('busMarkerInner');
            if (innerEl) {
                innerEl.style.transform = `rotate(${angle + 90}deg)`;
            }

            // Auto-follow camera if enabled
            if (autoFollowCamera && map) {
                map.panTo(p1, { animate: true, duration: 0.3 });
            }
        }

        // Increment index
        currentPathIndex = (currentPathIndex + 1) % routeCoordinates.length;

        // Calculate progress percentage and current stop index for live journey timeline sync
        const totalStops = @json($route->stops->count()) || 4;
        const progressPercent = (currentPathIndex / routeCoordinates.length) * 100;
        const currentStopIdx = Math.min(Math.floor((currentPathIndex / routeCoordinates.length) * totalStops), totalStops - 1);
        const currentSpeed = 28 + Math.floor(Math.random() * 8);

        // Update map telemetry bar
        const speedEl = document.getElementById('busSpeed');
        if (speedEl) speedEl.innerText = currentSpeed + ' km/h';

        // Notify Live Journey timeline component
        if (typeof window.updateLiveJourneyState === 'function') {
            window.updateLiveJourneyState(currentStopIdx, progressPercent, currentSpeed);
        }

        // Schedule next frame step smoothly
        setTimeout(() => {
            if (isTrackingActive) {
                animRequest = requestAnimationFrame(startBusTrackingLoop);
            }
        }, 350);
    }

    // Recenter camera on moving bus and resume auto-following
    function recenterCameraOnBus() {
        if (busMarker && map) {
            autoFollowCamera = true;
            const currentPos = busMarker.getLatLng();
            map.flyTo(currentPos, Math.max(map.getZoom(), 15), { duration: 0.8 });
            updateRecenterButtonState(true);
        }
    }

    // Fit screen bounds to full route
    function fitFullRouteBounds() {
        if (fullRouteBounds && map) {
            autoFollowCamera = false;
            updateRecenterButtonState(false);
            map.fitBounds(fullRouteBounds, { padding: [50, 50], animate: true, duration: 0.8 });
        }
    }

    // Toggle Tracking Simulation
    function toggleTrackingSimulation() {
        isTrackingActive = !isTrackingActive;
        const btnText = document.getElementById('trackingBtnText');
        const btnBtn = document.getElementById('toggleTrackingBtn');

        if (isTrackingActive) {
            btnText.innerText = 'Pause Live Tracking';
            btnBtn.className = 'flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-md transition hover:bg-emerald-700 border border-emerald-500/30';
            startBusTrackingLoop();
        } else {
            btnText.innerText = 'Resume Tracking';
            btnBtn.className = 'flex items-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white shadow-md transition hover:bg-amber-700 border border-amber-500/30';
            if (animRequest) cancelAnimationFrame(animRequest);
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
