<!-- Add / Edit Route Stop Modal -->
<div id="stopModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-4xl max-h-[90vh] transform overflow-y-auto rounded-2xl bg-white p-6 text-left shadow-2xl transition-all dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
            <!-- Modal Header -->
            <div class="mb-5 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">
                    Add Route Stop
                </h3>
                <button
                    type="button"
                    onclick="closeStopModal()"
                    class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="stopForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="stopFormMethod" value="POST">

                <div class="space-y-4">
                    <!-- Stop Name -->
                    <div>
                        <label for="stop_name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                            Stop Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="stop_name"
                            required
                            placeholder="e.g. Central Square / Green Park"
                            class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >
                    </div>

                    <!-- Stop Order & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stop_order" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Stop Sequence (#) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="stop_order"
                                id="stop_order"
                                min="1"
                                required
                                value="{{ ($route->stops->max('stop_order') ?? 0) + 1 }}"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                        </div>

                        <div>
                            <label for="stop_is_active" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Status
                            </label>
                            <select
                                name="is_active"
                                id="stop_is_active"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pickup & Drop Times -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stop_pickup_time" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Pickup Time
                            </label>
                            <input
                                type="time"
                                name="pickup_time"
                                id="stop_pickup_time"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                        </div>

                        <div>
                            <label for="stop_drop_time" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Arrival / Drop Time
                            </label>
                            <input
                                type="time"
                                name="drop_time"
                                id="stop_drop_time"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                        </div>
                    </div>

                    <!-- Optional Coordinates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stop_latitude" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Latitude (Optional)
                            </label>
                            <input
                                type="number"
                                step="any"
                                name="latitude"
                                id="stop_latitude"
                                placeholder="e.g. 27.7172"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                        </div>

                        <div>
                            <label for="stop_longitude" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">
                                Longitude (Optional)
                            </label>
                            <input
                                type="number"
                                step="any"
                                name="longitude"
                                id="stop_longitude"
                                placeholder="e.g. 85.3240"
                                class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                        </div>
                    </div>

                    <!-- Map Coordinate Picker -->
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Pick on Map</span>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    onclick="useMyLocationForStop()"
                                    title="Show your current location on the map, then pick by clicking"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-600 transition hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Use My Location
                                </button>
                                <button
                                    type="button"
                                    onclick="clearStopPicker()"
                                    title="Clear the picked location"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 flex gap-2">
                            <input
                                type="text"
                                id="stopPickerSearch"
                                placeholder="Search for a place, e.g. Biratnagar Airport"
                                autocomplete="off"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                            <button
                                type="button"
                                onclick="searchStopPickerLocation()"
                                title="Search for a location on the map"
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </div>
                        <div id="stopPickerMap" class="mt-2 h-96 w-full overflow-hidden rounded-xl border border-gray-300 bg-gray-900 z-0 dark:border-gray-700"></div>
                        <p id="stopPickerReadout" class="mt-2 space-y-0.5 text-xs">
                            <span id="stopPickerName" class="hidden font-medium text-gray-900 dark:text-white"></span>
                            <span id="stopPickerStatus" class="font-medium text-gray-500 dark:text-gray-400">Click the map to pick a location</span>
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                    <button
                        type="button"
                        onclick="closeStopModal()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        id="submitStopBtn"
                        class="rounded-xl bg-brand-500 px-5 py-2 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50"
                    >
                        Save Stop
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddStopModal() {
        document.getElementById('modalTitle').innerText = 'Add Route Stop';
        document.getElementById('stopForm').action = "{{ route('routes.stops.store', $route) }}";
        document.getElementById('stopFormMethod').value = 'POST';
        
        document.getElementById('stop_name').value = '';
        document.getElementById('stop_order').value = "{{ ($route->stops->max('stop_order') ?? 0) + 1 }}";
        document.getElementById('stop_pickup_time').value = '';
        document.getElementById('stop_drop_time').value = '';
        document.getElementById('stop_latitude').value = '';
        document.getElementById('stop_longitude').value = '';
        document.getElementById('stop_is_active').value = '1';
        document.getElementById('submitStopBtn').innerText = 'Add Stop';
        
        document.getElementById('stopModal').classList.remove('hidden');

        resetStopPicker();
    }

    function openEditStopModal(stop) {
        document.getElementById('modalTitle').innerText = 'Edit Route Stop';
        document.getElementById('stopForm').action = "/route-stops/" + stop.id;
        document.getElementById('stopFormMethod').value = 'PUT';
        
        document.getElementById('stop_name').value = stop.name || '';
        document.getElementById('stop_order').value = stop.stop_order || 1;
        
        // Format times to HH:MM for time input if present
        let pickupTime = stop.pickup_time ? stop.pickup_time.substring(0, 5) : '';
        let dropTime = stop.drop_time ? stop.drop_time.substring(0, 5) : '';
        
        document.getElementById('stop_pickup_time').value = pickupTime;
        document.getElementById('stop_drop_time').value = dropTime;
        document.getElementById('stop_latitude').value = stop.latitude || '';
        document.getElementById('stop_longitude').value = stop.longitude || '';
        document.getElementById('stop_is_active').value = (stop.is_active === false || stop.is_active === 0) ? '0' : '1';
        document.getElementById('submitStopBtn').innerText = 'Update Stop';
        
        document.getElementById('stopModal').classList.remove('hidden');

        if (stop.latitude && stop.longitude && stop.latitude != 0 && stop.longitude != 0) {
            seedStopPicker(parseFloat(stop.latitude), parseFloat(stop.longitude));
        } else {
            resetStopPicker();
        }
    }

    function closeStopModal() {
        document.getElementById('stopModal').classList.add('hidden');
    }

    // Close on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeStopModal();
        }
    });
</script>

<script>
    // ---- Map Coordinate Picker (Leaflet is loaded earlier on the routes.show page) ----
    let stopPickerMap = null;
    let stopPickerMarker = null;
    let stopPickerHereMarker = null;
    let stopPickerHereAccuracy = null;
    let stopPickerInitialized = false;
    const routeStopsData = @json($route->stops);
    const STOP_PICKER_DEFAULT_CENTER = [27.7172, 85.3240];

    function initStopPicker() {
        if (stopPickerInitialized || typeof L === 'undefined') return;

        stopPickerMap = L.map('stopPickerMap', {
            scrollWheelZoom: false,
            preferCanvas: true
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd',
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>'
        }).addTo(stopPickerMap);

        stopPickerMap.on('click', function (e) {
            placeStopPickerMarker(e.latlng.lat, e.latlng.lng);
        });

        stopPickerInitialized = true;
    }

    function getStopPickerDefaultCenter() {
        const stops = routeStopsData.filter(s => s.latitude && s.longitude && s.latitude != 0 && s.longitude != 0);
        if (stops.length > 0) {
            const bounds = L.latLngBounds(stops.map(s => [parseFloat(s.latitude), parseFloat(s.longitude)]));
            return { center: bounds.getCenter(), zoom: 14 };
        }
        return { center: STOP_PICKER_DEFAULT_CENTER, zoom: 13 };
    }

    function openStopPicker() {
        initStopPicker();
        if (!stopPickerMap) return;
        setTimeout(function () {
            stopPickerMap.invalidateSize();
        }, 50);
    }

    function placeStopPickerMarker(lat, lng, name) {
        initStopPicker();
        if (!stopPickerMap) return;

        if (!stopPickerMarker) {
            stopPickerMarker = L.marker([lat, lng], { draggable: true }).addTo(stopPickerMap);
            stopPickerMarker.on('dragend', function () {
                const pos = stopPickerMarker.getLatLng();
                syncStopPickerInputs(pos.lat, pos.lng);
            });
        } else {
            stopPickerMarker.setLatLng([lat, lng]);
        }

        syncStopPickerInputs(lat, lng, name);
    }

    function syncStopPickerInputs(lat, lng, name) {
        document.getElementById('stop_latitude').value = lat.toFixed(6);
        document.getElementById('stop_longitude').value = lng.toFixed(6);
        updateStopPickerReadout(lat, lng, name);
        if (!name) {
            reverseGeocodeStop(lat, lng);
        }
    }

    function updateStopPickerReadout(lat, lng, name) {
        const nameEl = document.getElementById('stopPickerName');
        const statusEl = document.getElementById('stopPickerStatus');
        if (lat !== null && lng !== null) {
            if (name) {
                nameEl.textContent = name;
                nameEl.classList.remove('hidden');
            } else {
                nameEl.classList.add('hidden');
            }
            statusEl.textContent = 'Selected: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
            statusEl.className = 'font-mono font-medium text-brand-600 dark:text-brand-400';
        } else {
            nameEl.classList.add('hidden');
            statusEl.textContent = 'Click the map to pick a location';
            statusEl.className = 'font-medium text-gray-500 dark:text-gray-400';
        }
    }

    // Reverse geocode via Nominatim (debounced + cached per location)
    let reverseGeocodeTimer = null;
    const reverseGeocodeCache = {};

    function reverseGeocodeStop(lat, lng) {
        const key = lat.toFixed(4) + ',' + lng.toFixed(4);
        if (reverseGeocodeCache.hasOwnProperty(key)) {
            updateStopPickerReadout(lat, lng, reverseGeocodeCache[key]);
            return;
        }
        if (reverseGeocodeTimer) clearTimeout(reverseGeocodeTimer);

        reverseGeocodeTimer = setTimeout(async function () {
            try {
                const response = await fetch(
                    'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18'
                );
                const data = await response.json();
                const name = data.display_name || null;
                reverseGeocodeCache[key] = name;
                updateStopPickerReadout(lat, lng, name);
            } catch (err) {
                updateStopPickerReadout(lat, lng, null);
            }
        }, 400);
    }

    function seedStopPicker(lat, lng) {
        openStopPicker();
        if (!stopPickerMap) return;
        placeStopPickerMarker(lat, lng);
        stopPickerMap.setView([lat, lng], Math.max(stopPickerMap.getZoom(), 15));
    }

    function resetStopPicker() {
        if (stopPickerMarker) {
            stopPickerMap.removeLayer(stopPickerMarker);
            stopPickerMarker = null;
        }
        openStopPicker();
        if (!stopPickerMap) return;

        const defaults = getStopPickerDefaultCenter();
        centerOnCurrentLocation(defaults.center, defaults.zoom);
    }

    // "You are here" dot (informational, distinct from the selection marker)
    function clearCurrentLocationMarker() {
        if (stopPickerHereAccuracy) {
            stopPickerMap.removeLayer(stopPickerHereAccuracy);
            stopPickerHereAccuracy = null;
        }
        if (stopPickerHereMarker) {
            stopPickerMap.removeLayer(stopPickerHereMarker);
            stopPickerHereMarker = null;
        }
    }

    function showCurrentLocationMarker(lat, lng, accuracy) {
        if (!stopPickerMap) return;
        clearCurrentLocationMarker();

        if (accuracy > 0) {
            stopPickerHereAccuracy = L.circle([lat, lng], {
                radius: accuracy,
                color: '#3B82F6',
                weight: 1,
                fillColor: '#3B82F6',
                fillOpacity: 0.12
            }).addTo(stopPickerMap);
        }

        stopPickerHereMarker = L.circleMarker([lat, lng], {
            radius: 8,
            color: '#FFFFFF',
            weight: 2,
            fillColor: '#3B82F6',
            fillOpacity: 1
        }).addTo(stopPickerMap);
    }

    // Center the map on the browser's current location by default (does NOT auto-select)
    function centerOnCurrentLocation(fallbackCenter, fallbackZoom) {
        if (!stopPickerMap) return;

        if (!navigator.geolocation) {
            stopPickerMap.setView(fallbackCenter, fallbackZoom);
            updateStopPickerReadout(null, null);
            return;
        }

        setStopPickerStatus('Locating your position…');

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                if (!stopPickerMap) return;
                stopPickerMap.setView([pos.coords.latitude, pos.coords.longitude], 15);
                showCurrentLocationMarker(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy || 0);
                setStopPickerStatus('Showing your location — click the map to pick');
            },
            function () {
                if (!stopPickerMap) return;
                stopPickerMap.setView(fallbackCenter, fallbackZoom);
                updateStopPickerReadout(null, null);
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 30000 }
        );
    }

    function clearStopPicker() {
        if (stopPickerMarker) {
            stopPickerMap.removeLayer(stopPickerMarker);
            stopPickerMarker = null;
        }
        document.getElementById('stop_latitude').value = '';
        document.getElementById('stop_longitude').value = '';
        updateStopPickerReadout(null, null);
        if (stopPickerMap) {
            stopPickerMap.setView(getStopPickerDefaultCenter().center, 13);
        }
    }

    function setStopPickerStatus(text) {
        const statusEl = document.getElementById('stopPickerStatus');
        statusEl.textContent = text;
        statusEl.className = 'font-medium text-gray-500 dark:text-gray-400';
    }

    function useMyLocationForStop() {
        openStopPicker();
        if (!stopPickerMap) {
            setStopPickerStatus('Map unavailable');
            return;
        }
        if (!navigator.geolocation) {
            setStopPickerStatus('Location unavailable — click the map instead');
            return;
        }

        setStopPickerStatus('Locating…');

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                placeStopPickerMarker(pos.coords.latitude, pos.coords.longitude);
                stopPickerMap.setView([pos.coords.latitude, pos.coords.longitude], 15);
            },
            function () {
                setStopPickerStatus('Location unavailable — click the map instead');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
        );
    }

    // Search a location via OpenStreetMap Nominatim and fly to it
    async function searchStopPickerLocation() {
        const queryEl = document.getElementById('stopPickerSearch');
        const q = (queryEl ? queryEl.value : '').trim();
        if (!q) return;

        setStopPickerStatus('Searching…');

        try {
            const response = await fetch(
                'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(q)
            );
            const results = await response.json();

            if (results && results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lng = parseFloat(results[0].lon);

                openStopPicker();
                if (!stopPickerMap) return;

                placeStopPickerMarker(lat, lng, results[0].display_name);
                stopPickerMap.setView([lat, lng], 16);
            } else {
                setStopPickerStatus('No results found for "' + q + '"');
            }
        } catch (err) {
            setStopPickerStatus('Search failed — try again or click the map');
        }
    }

    // Typing coordinates manually moves the map marker (bidirectional sync)
    document.addEventListener('DOMContentLoaded', function () {
        const latInput = document.getElementById('stop_latitude');
        const lngInput = document.getElementById('stop_longitude');
        const searchInput = document.getElementById('stopPickerSearch');

        function syncFromManualInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (!isNaN(lat) && !isNaN(lng)) {
                placeStopPickerMarker(lat, lng);
            } else if (stopPickerMarker) {
                stopPickerMap.removeLayer(stopPickerMarker);
                stopPickerMarker = null;
                updateStopPickerReadout(null, null);
            }
        }

        latInput.addEventListener('input', syncFromManualInputs);
        lngInput.addEventListener('input', syncFromManualInputs);

        if (searchInput) {
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchStopPickerLocation();
                }
            });
        }
    });
</script>
