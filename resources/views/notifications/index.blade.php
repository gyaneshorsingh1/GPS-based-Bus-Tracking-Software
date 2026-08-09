<x-app-layout>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-6 shadow-sm">

                <div class="mb-6 flex items-center justify-between">

                    <h2 class="text-xl font-semibold text-gray-900">
                        Notifications
                    </h2>

                    <form method="POST"
                          action="{{ route('notifications.read-all') }}">
                        @csrf

                        <button
                            type="submit"
                            class="text-sm text-blue-600 hover:underline">
                            Mark all as read
                        </button>
                    </form>

                </div>

                <div class="space-y-3">

                    @forelse($notifications as $notification)

                        <div class="rounded-lg border p-4
                            {{ $notification->read_at
                                ? 'bg-white'
                                : 'bg-blue-50 border-blue-200' }}">

                            <div class="flex items-start justify-between">

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    <p class="mt-2 text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                </div>

                                @if(!$notification->read_at)

                                    <form method="POST"
                                          action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="text-xs text-blue-600 hover:underline">
                                            Mark as read
                                        </button>
                                    </form>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="py-10 text-center text-gray-500">
                            No notifications yet.
                        </div>

                    @endforelse

                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>