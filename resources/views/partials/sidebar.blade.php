{{--
    SETUP (one-time):
        composer require blade-ui-kit/blade-heroicons

    Icon names come from https://blade-ui-kit.com/blade-icons?set=1
    (outline set prefix: heroicon-o-, solid set prefix: heroicon-s-,
     mini set prefix: heroicon-m-)
--}}
@php
    /**
     * SIDEBAR CONFIG
     * ----------------------------------------------------------------
     * This is the ONLY place you need to touch to add/remove/reorder
     * sidebar items. Each group is a section (e.g. "MENU", "others").
     * Each item can optionally have a "dropdown" of sub-links.
     *
     * item keys:
     *   key      -> unique string, used for the Alpine `selected` state
     *   label    -> text shown to the user
     *   route    -> Laravel route name (used if no dropdown, or as the
     *               link on the parent item itself)
     *   active   -> string|array of `page` value(s) that should mark
     *               this item as active
     *   icon     -> Blade Heroicons component name (requires
     *               "composer require blade-ui-kit/blade-heroicons")
     *               e.g. 'heroicon-o-squares-2x2', 'heroicon-o-user'
     *               Browse names at https://blade-ui-kit.com/blade-icons?set=1
     *   dropdown -> optional array of ['label' => ..., 'route' => ..., 'page' => ...]
     */
    $superAdminMenu = [
        [
            'title' => 'Dashboard',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'dashboard', 'active' => ['overview', 'dashboard'], 'icon' => 'heroicon-o-home', 'permission' => null, 'dropdown' => null],
                ['key' => 'statistics', 'label' => 'Statistics', 'route' => 'dashboard', 'active' => 'statistics', 'icon' => 'heroicon-o-chart-bar', 'permission' => null, 'dropdown' => null],
                ['key' => 'live-summary', 'label' => 'Live Summary', 'route' => 'dashboard', 'active' => 'live-summary', 'icon' => 'heroicon-o-signal', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Management',
            'items' => [
                ['key' => 'user-management', 'label' => 'User Management', 'route' => 'dashboard', 'active' => 'user-management', 'icon' => 'heroicon-o-user-group', 'permission' => null, 'dropdown' => null],
                ['key' => 'school-management', 'label' => 'School Management', 'route' => 'schools.index', 'active' => 'school-management', 'icon' => 'heroicon-o-building-library', 'permission' => null, 'dropdown' => null],
                ['key' => 'parent-management', 'label' => 'Parent Management', 'route' => 'parents.index', 'active' => 'parent-management', 'icon' => 'heroicon-o-users', 'permission' => null, 'dropdown' => null],
                ['key' => 'fleet-management', 'label' => 'Fleet Management', 'route' => 'dashboard', 'active' => 'fleet-management', 'icon' => 'heroicon-o-truck', 'permission' => null, 'dropdown' => null],
                ['key' => 'route-management', 'label' => 'Route Management', 'route' => 'dashboard', 'active' => 'route-management', 'icon' => 'heroicon-o-map', 'permission' => null, 'dropdown' => null],
                ['key' => 'trip-management', 'label' => 'Trip Management', 'route' => 'dashboard', 'active' => 'trip-management', 'icon' => 'heroicon-o-clipboard-document-list', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Monitoring',
            'items' => [
                ['key' => 'live-tracking', 'label' => 'Live Tracking', 'route' => 'dashboard', 'active' => 'live-tracking', 'icon' => 'heroicon-o-eye', 'permission' => null, 'dropdown' => null],
                ['key' => 'notifications', 'label' => 'Notifications', 'route' => 'dashboard', 'active' => 'notifications', 'icon' => 'heroicon-o-bell', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Reports',
            'items' => [
                ['key' => 'attendance', 'label' => 'Attendance', 'route' => 'dashboard', 'active' => 'attendance', 'icon' => 'heroicon-o-document-check', 'permission' => null, 'dropdown' => null],
                ['key' => 'trips', 'label' => 'Trips', 'route' => 'dashboard', 'active' => 'trips', 'icon' => 'heroicon-o-document-text', 'permission' => null, 'dropdown' => null],
                ['key' => 'drivers', 'label' => 'Drivers', 'route' => 'dashboard', 'active' => 'drivers', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
                ['key' => 'bus-usage', 'label' => 'Bus Usage', 'route' => 'dashboard', 'active' => 'bus-usage', 'icon' => 'heroicon-o-truck', 'permission' => null, 'dropdown' => null],
                ['key' => 'gps-history', 'label' => 'GPS History', 'route' => 'dashboard', 'active' => 'gps-history', 'icon' => 'heroicon-o-map', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Administration',
            'items' => [
                ['key' => 'roles-permissions', 'label' => 'Roles & Permissions', 'route' => 'dashboard', 'active' => 'roles-permissions', 'icon' => 'heroicon-o-shield-check', 'permission' => null, 'dropdown' => null],
                ['key' => 'settings', 'label' => 'Settings', 'route' => 'dashboard', 'active' => 'settings', 'icon' => 'heroicon-o-cog-6-tooth', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.edit', 'active' => 'profile', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
    ];

    $principalMenu = [
        [
            'title' => 'Dashboard',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'dashboard', 'active' => ['overview', 'dashboard'], 'icon' => 'heroicon-o-home', 'permission' => null, 'dropdown' => null],
                ['key' => 'statistics', 'label' => 'Statistics', 'route' => 'dashboard', 'active' => 'statistics', 'icon' => 'heroicon-o-chart-bar', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Management',
            'items' => [
                ['key' => 'students', 'label' => 'Students', 'route' => 'dashboard', 'active' => 'students', 'icon' => 'heroicon-o-academic-cap', 'permission' => null, 'dropdown' => null],
                ['key' => 'parents', 'label' => 'Parents', 'route' => 'dashboard', 'active' => 'parents', 'icon' => 'heroicon-o-users', 'permission' => null, 'dropdown' => null],
                ['key' => 'drivers', 'label' => 'Drivers', 'route' => 'dashboard', 'active' => 'drivers', 'icon' => 'heroicon-o-user-group', 'permission' => null, 'dropdown' => null],
                ['key' => 'classes', 'label' => 'Classes', 'route' => 'dashboard', 'active' => 'classes', 'icon' => 'heroicon-o-academic-cap', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Trip Management',
            'items' => [
                ['key' => 'active-trips', 'label' => 'Active Trips', 'route' => 'dashboard', 'active' => 'active-trips', 'icon' => 'heroicon-o-play', 'permission' => null, 'dropdown' => null],
                ['key' => 'trip-history', 'label' => 'Trip History', 'route' => 'dashboard', 'active' => 'trip-history', 'icon' => 'heroicon-o-clock', 'permission' => null, 'dropdown' => null],
                ['key' => 'attendance', 'label' => 'Attendance', 'route' => 'dashboard', 'active' => 'attendance', 'icon' => 'heroicon-o-document-check', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Monitoring',
            'items' => [
                ['key' => 'live-tracking', 'label' => 'Live Tracking', 'route' => 'dashboard', 'active' => 'live-tracking', 'icon' => 'heroicon-o-eye', 'permission' => null, 'dropdown' => null],
                ['key' => 'notifications', 'label' => 'Notifications', 'route' => 'dashboard', 'active' => 'notifications', 'icon' => 'heroicon-o-bell', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Reports',
            'items' => [
                ['key' => 'student-attendance', 'label' => 'Student Attendance', 'route' => 'dashboard', 'active' => 'student-attendance', 'icon' => 'heroicon-o-document-check', 'permission' => null, 'dropdown' => null],
                ['key' => 'trip-reports', 'label' => 'Trip Reports', 'route' => 'dashboard', 'active' => 'trip-reports', 'icon' => 'heroicon-o-document-text', 'permission' => null, 'dropdown' => null],
                ['key' => 'driver-reports', 'label' => 'Driver Reports', 'route' => 'dashboard', 'active' => 'driver-reports', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.edit', 'active' => 'profile', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
    ];

    $driverMenu = [
        [
            'title' => 'Dashboard',
            'items' => [
                ['key' => 'overview', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['overview', 'dashboard'], 'icon' => 'heroicon-o-home', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Trip Management',
            'items' => [
                ['key' => 'today-trip', 'label' => "Today's Trip", 'route' => 'dashboard', 'active' => 'today-trip', 'icon' => 'heroicon-o-clipboard-document-list', 'permission' => null, 'dropdown' => null],
                ['key' => 'start-trip', 'label' => 'Start Trip', 'route' => 'dashboard', 'active' => 'start-trip', 'icon' => 'heroicon-o-play', 'permission' => null, 'dropdown' => null],
                ['key' => 'end-trip', 'label' => 'End Trip', 'route' => 'dashboard', 'active' => 'end-trip', 'icon' => 'heroicon-o-stop', 'permission' => null, 'dropdown' => null],
                ['key' => 'boarding', 'label' => 'Student Boarding', 'route' => 'dashboard', 'active' => 'boarding', 'icon' => 'heroicon-o-arrow-right', 'permission' => null, 'dropdown' => null],
                ['key' => 'drop-off', 'label' => 'Student Drop-off', 'route' => 'dashboard', 'active' => 'drop-off', 'icon' => 'heroicon-o-arrow-left', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Monitoring',
            'items' => [
                ['key' => 'live-tracking', 'label' => 'Live Tracking', 'route' => 'dashboard', 'active' => 'live-tracking', 'icon' => 'heroicon-o-eye', 'permission' => null, 'dropdown' => null],
                ['key' => 'notifications', 'label' => 'Notifications', 'route' => 'dashboard', 'active' => 'notifications', 'icon' => 'heroicon-o-bell', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.edit', 'active' => 'profile', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
    ];

    $parentMenu = [
        [
            'title' => 'Dashboard',
            'items' => [
                ['key' => 'overview', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['overview', 'dashboard'], 'icon' => 'heroicon-o-home', 'permission' => null, 'dropdown' => null],
                ['key' => 'my-children', 'label' => 'My Children', 'route' => 'dashboard', 'active' => 'my-children', 'icon' => 'heroicon-o-users', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Live Tracking',
            'items' => [
                ['key' => 'track-bus', 'label' => 'Track Bus', 'route' => 'dashboard', 'active' => 'track-bus', 'icon' => 'heroicon-o-map', 'permission' => null, 'dropdown' => null],
                ['key' => 'bus-location', 'label' => 'Bus Location', 'route' => 'dashboard', 'active' => 'bus-location', 'icon' => 'heroicon-o-map-pin', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Attendance',
            'items' => [
                ['key' => 'boarding-history', 'label' => 'Boarding History', 'route' => 'dashboard', 'active' => 'boarding-history', 'icon' => 'heroicon-o-arrow-right', 'permission' => null, 'dropdown' => null],
                ['key' => 'dropoff-history', 'label' => 'Drop-off History', 'route' => 'dashboard', 'active' => 'dropoff-history', 'icon' => 'heroicon-o-arrow-left', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Notifications',
            'items' => [
                ['key' => 'notifications', 'label' => 'Notifications', 'route' => 'dashboard', 'active' => 'notifications', 'icon' => 'heroicon-o-bell', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.edit', 'active' => 'profile', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
    ];

    $studentMenu = [
        [
            'title' => 'Dashboard',
            'items' => [
                ['key' => 'overview', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['overview', 'dashboard'], 'icon' => 'heroicon-o-home', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Tracking',
            'items' => [
                ['key' => 'bus-tracking', 'label' => 'Bus Tracking', 'route' => 'dashboard', 'active' => 'bus-tracking', 'icon' => 'heroicon-o-map', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Attendance',
            'items' => [
                ['key' => 'attendance', 'label' => 'Attendance', 'route' => 'dashboard', 'active' => 'attendance', 'icon' => 'heroicon-o-document-check', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Notifications',
            'items' => [
                ['key' => 'notifications', 'label' => 'Notifications', 'route' => 'dashboard', 'active' => 'notifications', 'icon' => 'heroicon-o-bell', 'permission' => null, 'dropdown' => null],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.edit', 'active' => 'profile', 'icon' => 'heroicon-o-user-circle', 'permission' => null, 'dropdown' => null],
            ],
        ],
    ];

    $menu = $superAdminMenu;
    $user = auth()->user();
    $roleNames = $user ? array_map('strtolower', $user->getRoleNames()->all()) : [];

    if ($user && (in_array('school admin', $roleNames, true) || in_array('principal', $roleNames, true))) {
        $menu = $principalMenu;
    } elseif ($user && in_array('driver', $roleNames, true)) {
        $menu = $driverMenu;
    } elseif ($user && in_array('parent', $roleNames, true)) {
        $menu = $parentMenu;
    } elseif ($user && in_array('student', $roleNames, true)) {
        $menu = $studentMenu;
    }

    /**
     * PERMISSION-AWARE FILTERING
     * ----------------------------------------------------------------
     * Each menu item can declare a `permission` key. Items (and dropdown
     * sub-items) the current user is not allowed to view are hidden.
     * Leave the key off for items that require no permission.
     */
    $can = function ($permission) {
        if (empty($permission)) {
            return true;
        }
        return auth()->check() && auth()->user()->can($permission);
    };

    $menu = array_values(array_filter(array_map(function ($group) use ($can) {
        $items = array_values(array_filter($group['items'], function ($item) use ($can) {
            if (! $can($item['permission'] ?? null)) {
                return false;
            }

            if (! empty($item['dropdown'])) {
                $item['dropdown'] = array_values(array_filter(
                    $item['dropdown'],
                    fn ($sub) => $can($sub['permission'] ?? null)
                ));

                return count($item['dropdown']) > 0;
            }

            return true;
        }));

        $group['items'] = $items;

        return $group;
    }, $menu), fn ($group) => count($group['items']) > 0));

    // Fallback link for the logo when the user cannot view the dashboard
    $homeRoute = auth()->check() && auth()->user()->can('dashboard.view')
        ? route('dashboard')
        : route('profile.edit');

    // Helper: is this item "active" given the current $page variable?
    $isActive = function ($item) use ($page) {
        $active = $item['active'] ?? null;
        if (is_array($active)) {
            return in_array($page, $active, true);
        }
        return $active !== null && $page === $active;
    };
@endphp

<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
>
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="sidebar-header flex items-center gap-2 pb-7 pt-8"
    >
        <a href="{{ $homeRoute }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" />
                <img class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" />
            </span>
            <img
                class="logo-icon"
                :class="sidebarToggle ? 'lg:block' : 'hidden'"
                src="/images/logo/logo-icon.svg"
                alt="Logo"
            />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav x-data="{selected: $persist('Dashboard')}">

            @foreach ($menu as $group)
                <div>
                    <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                        <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                            {{ $group['title'] }}
                        </span>

                        <svg
                            :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                            class="menu-group-icon mx-auto fill-current"
                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z" fill="" />
                        </svg>
                    </h3>

                    <ul class="mb-6 flex flex-col gap-4">
                        @foreach ($group['items'] as $item)
                            <li>
                                <a
                                    href="{{ !empty($item['dropdown']) ? '#' : route($item['route']) }}"
                                    @if(!empty($item['dropdown']))
                                        @click.prevent="selected = (selected === '{{ $item['key'] }}' ? '' : '{{ $item['key'] }}')"
                                    @else
                                        @click="selected = (selected === '{{ $item['key'] }}' ? '' : '{{ $item['key'] }}')"
                                    @endif
                                    class="menu-item group"
                                    :class="(selected === '{{ $item['key'] }}') || {{ $isActive($item) ? 'true' : 'false' }} ? 'menu-item-active' : 'menu-item-inactive'"
                                >
                                    <span
                                        :class="(selected === '{{ $item['key'] }}') || {{ $isActive($item) ? 'true' : 'false' }} ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                    >
                                        <x-dynamic-component
                                            :component="$item['icon']"
                                            class="w-6 h-6 shrink-0"
                                        />
                                    </span>

                                    <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        {{ $item['label'] }}
                                    </span>

                                    @if (!empty($item['dropdown']))
                                        <span
                                            :class="[(selected === '{{ $item['key'] }}') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '']"
                                        >
                                            <x-heroicon-o-chevron-down class="menu-item-arrow w-4 h-4" />
                                        </span>
                                    @endif
                                </a>

                                @if (!empty($item['dropdown']))
                                    <div class="transform translate overflow-hidden" :class="(selected === '{{ $item['key'] }}') ? 'block' : 'hidden'">
                                        <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                            @foreach ($item['dropdown'] as $sub)
                                                <li>
                                                    <a
                                                        href="{{ route($sub['route']) }}"
                                                        class="menu-dropdown-item group"
                                                        :class="{{ $page === $sub['page'] ? 'true' : 'false' }} ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                                                    >
                                                        {{ $sub['label'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

        </nav>

        <!-- Promo Box -->
        <div
            :class="sidebarToggle ? 'lg:hidden' : ''"
            class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]"
        >
            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">
                #1 Tailwind CSS Dashboard
            </h3>
            <p class="text-theme-sm mb-4 text-gray-500 dark:text-gray-400">
                Leading Tailwind CSS Admin Template with 400+ UI Component and Pages.
            </p>
            <a
                href="https://tailadmin.com/pricing"
                target="_blank"
                rel="nofollow"
                class="text-theme-sm flex items-center justify-center rounded-lg bg-brand-500 p-3 font-medium text-white hover:bg-brand-600"
            >
                Purchase Plan
            </a>
        </div>
        <!-- Promo Box -->
    </div>
</aside>