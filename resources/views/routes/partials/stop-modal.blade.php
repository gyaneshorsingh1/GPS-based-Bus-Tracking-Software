<!-- Add / Edit Route Stop Modal -->
<div id="stopModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-xs transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-2xl transition-all dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
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
