<x-app-layout page="my-children">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">My Children</h1>
        </div>

        @if ($children->isEmpty())
            <div
                class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-20 text-center dark:border-gray-700 dark:bg-white/[0.03]"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
                >
                    <x-heroicon-o-user-group class="h-8 w-8 text-gray-400" />
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">No children found</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No children are linked to your account yet. Please contact your school for assistance.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($children as $child)
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                    >
                        <div class="mb-5 flex items-center gap-4">
                            @if ($child->photo)
                                <img
                                    src="{{ asset('storage/' . $child->photo) }}"
                                    alt="{{ $child->full_name }}"
                                    class="h-14 w-14 rounded-full object-cover"
                                >
                            @else
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-200 text-xl font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300"
                                >
                                    {{ strtoupper(substr($child->first_name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <h2 class="truncate text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $child->full_name }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->admission_no }}</p>
                            </div>

                            @if ($child->is_active)
                                <span
                                    class="ml-auto rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
                                >
                                    Active
                                </span>
                            @else
                                <span
                                    class="ml-auto rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                >
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <dl class="space-y-3 text-sm">
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Grade</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->grade }}{{ $child->section ? ' - ' . $child->section : '' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Roll No</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->roll_no ?? '—' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Gender</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->gender }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Date of Birth</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->date_of_birth->format('M d, Y') }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">School</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->school->name ?? '—' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Pickup</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->pickup_location }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500 dark:text-gray-400">Drop</dt>
                                <dd class="text-right font-medium text-gray-900 dark:text-white">
                                    {{ $child->drop_location }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
