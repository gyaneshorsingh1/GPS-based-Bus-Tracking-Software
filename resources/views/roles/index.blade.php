<x-app-layout page="roles-permissions">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Roles &amp; Permissions</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage roles and the permissions granted to each role.
                </p>
            </div>
            <a
                href="{{ route('roles.create') }}"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
            >
                Create Role
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('roles.index') }}" method="GET" class="mb-4">
            <div class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search roles..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >

                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                >
                    Search
                </button>

                <a
                    href="{{ route('roles.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-gray-800">
                    <tr class="text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">Role</th>
                        <th class="px-5 py-3 font-medium">Users</th>
                        <th class="px-5 py-3 font-medium">Permissions</th>
                        <th class="px-5 py-3 font-medium">Guard</th>
                        <th class="px-5 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($roles as $role)
                        @php $locked = $role->name === 'Super Admin'; @endphp
                        <tr class="text-gray-700 dark:text-gray-200">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $role->name }}</span>
                                    @if ($locked)
                                        <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                            Locked
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3">{{ $role->users_count }}</td>
                            <td class="px-5 py-3">{{ $role->permissions_count }}</td>
                            <td class="px-5 py-3">{{ $role->guard_name }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('roles.show', $role) }}"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                    >
                                        View
                                    </a>
                                    @if (! $locked)
                                        <a
                                            href="{{ route('roles.edit', $role) }}"
                                            class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('roles.destroy', $role) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $role->name }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                                No roles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $roles->links() }}
        </div>
    </div>
</x-app-layout>
