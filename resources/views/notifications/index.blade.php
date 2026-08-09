<x-app-layout page="notifications">
    <div class="mx-auto max-w-(--breakpoint-2xl) space-y-6 p-4 md:p-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Stay updated on your buses, routes and trips.</p>
                </div>
            </div>

            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-md transition hover:bg-brand-600 dark:bg-brand-500 dark:hover:bg-brand-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Unread</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-brand-600 dark:text-brand-400">
                    {{ $unreadCount }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $notifications->total() }}
                </p>
            </div>
            <div class="col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm lg:col-span-1 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Latest notification</p>
                <p class="mt-1 truncate text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $notifications->first()?->data['title'] ?? 'No notifications yet' }}
                </p>
            </div>
        </div>

        {{-- Notification list --}}
        <div class="space-y-3">
            @forelse ($notifications as $notification)
                @php
                    $unread = is_null($notification->read_at);
                @endphp
                <div
                    class="relative flex items-start gap-4 rounded-2xl border p-5 transition
                        {{ $unread
                            ? 'border-brand-300 bg-brand-50/60 shadow-theme-sm dark:border-brand-500/40 dark:bg-brand-500/10'
                            : 'border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]' }}">

                    @if ($unread)
                        <span class="absolute -left-px top-6 bottom-6 w-1 rounded-r-full bg-brand-500"></span>
                    @endif

                    <x-notification-icon :notification="$notification" size="md" />

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-x-4 gap-y-1">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold {{ $unread ? 'text-gray-900 dark:text-white' : 'text-gray-800 dark:text-gray-300' }}">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    @if ($unread)
                                        <span class="inline-flex h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm leading-relaxed {{ $unread ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>

                            <time class="shrink-0 text-xs text-gray-400 dark:text-gray-500" datetime="{{ $notification->created_at->toIso8601String() }}">
                                {{ $notification->created_at->format('M d, Y g:i A') }}
                            </time>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            @if ($unread)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-brand-500/40 dark:hover:text-brand-400">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mark as read
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500">Read</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white py-16 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">No notifications yet</h3>
                    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                        Bus updates, alerts and reminders will appear here.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
