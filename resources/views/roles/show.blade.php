<x-app-layout page="roles-permissions">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $role->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Details and permissions for this role.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if ($role->name !== 'Super Admin')
                    <a
                        href="{{ route('roles.edit', $role) }}"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                    >
                        Edit
                    </a>
                @endif
                <a
                    href="{{ route('roles.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Back
                </a>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <span class="text-sm text-gray-500 dark:text-gray-400">Assigned Users</span>
                <h4 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $role->users()->count() }}</h4>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <span class="text-sm text-gray-500 dark:text-gray-400">Permissions</span>
                <h4 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $role->permissions()->count() }}</h4>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <span class="text-sm text-gray-500 dark:text-gray-400">Guard</span>
                <h4 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $role->guard_name }}</h4>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permissions</h2>
            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($permissionGroups as $module => $permissions)
                    @php
                        $assigned = $permissions->filter(fn ($permission) => $permission['checked']);
                    @endphp
                    @if ($assigned->isNotEmpty())
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-white/90">
                                {{ Str::title(str_replace('-', ' ', $module)) }}
                            </h3>
                            <ul class="mt-3 space-y-1.5">
                                @foreach ($assigned as $permission)
                                    <li class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                        {{ $permission['name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No permissions assigned to this role.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
