<div class="space-y-6">
    @foreach ($permissionGroups as $module => $permissions)
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-white/90">
                {{ Str::title(str_replace('-', ' ', $module)) }}
            </h3>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
                @foreach ($permissions as $permission)
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-white/[0.03]">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission['name'] }}"
                            @checked($permission['checked'])
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                        >
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                            {{ Str::title(str_replace('-', ' ', Str::after($permission['name'], '.'))) }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
