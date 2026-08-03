@php($page = 'drivers')

<x-app-layout page="drivers">

<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Drivers
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage school bus drivers
            </p>

        </div>

        <a
            href="{{ route('drivers.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
        >
            + Add Driver
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div
            class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400"
        >
            {{ session('success') }}
        </div>

    @endif


    {{-- SEARCH / FILTER --}}
    <div
        class="mb-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div class="p-5">

            <form
                method="GET"
                action="{{ route('drivers.index') }}"
            >

                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

                    {{-- SEARCH --}}
                    <div class="md:col-span-6">

                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Search Drivers
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search employee ID, name, phone or license..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        >

                    </div>


                    {{-- STATUS --}}
                    <div class="md:col-span-3">

                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Active"
                                {{ request('status') == 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ request('status') == 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                            <option
                                value="Suspended"
                                {{ request('status') == 'Suspended' ? 'selected' : '' }}
                            >
                                Suspended
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="flex items-end gap-2 md:col-span-3">

                        <button
                            type="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('drivers.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- DRIVER TABLE --}}
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b border-gray-200 dark:border-gray-800">

                    <tr class="text-gray-500 dark:text-gray-400">

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Photo
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Employee ID
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Driver Name
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            School
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Phone
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            License No.
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Status
                        </th>

                        <th class="whitespace-nowrap px-5 py-3 font-medium">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse($drivers as $driver)

                        <tr class="text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.02]">

                            {{-- PHOTO --}}
                            <td class="px-5 py-3">

                                @if($driver->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . $driver->profile_photo) }}"
                                        alt="{{ $driver->full_name }}"
                                        class="h-11 w-11 rounded-full object-cover"
                                    >

                                @else

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400"
                                    >
                                        {{ strtoupper(substr($driver->first_name, 0, 1)) }}
                                    </div>

                                @endif

                            </td>


                            {{-- EMPLOYEE ID --}}
                            <td class="whitespace-nowrap px-5 py-3">

                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $driver->employee_id }}
                                </span>

                            </td>


                            {{-- DRIVER NAME --}}
                            <td class="whitespace-nowrap px-5 py-3">

                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $driver->full_name }}
                                </div>

                                <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $driver->gender }}
                                </div>

                            </td>


                            {{-- SCHOOL --}}
                            <td class="whitespace-nowrap px-5 py-3">

                                {{ $driver->school->name ?? '—' }}

                            </td>


                            {{-- PHONE --}}
                            <td class="whitespace-nowrap px-5 py-3">

                                {{ $driver->phone }}

                            </td>


                            {{-- LICENSE --}}
                            <td class="whitespace-nowrap px-5 py-3">

                                {{ $driver->license_number }}

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-3">

                                @if($driver->status === 'Active')

                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
                                    >
                                        Active
                                    </span>

                                @elseif($driver->status === 'Suspended')

                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400"
                                    >
                                        Suspended
                                    </span>

                                @else

                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400"
                                    >
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td class="px-5 py-3">

                                <div class="flex items-center gap-2">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route('drivers.show', $driver) }}"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                    >
                                        View
                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('drivers.edit', $driver) }}"
                                        class="rounded-lg bg-yellow-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-yellow-600"
                                    >
                                        Edit
                                    </a>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('drivers.destroy', $driver) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this driver?');"
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

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td
                                colspan="8"
                                class="px-5 py-12 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div
                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400"
                                    >
                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                    </div>

                                    <p class="mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                        No drivers found
                                    </p>

                                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                        There are currently no drivers matching your search.
                                    </p>

                                    <a
                                        href="{{ route('drivers.create') }}"
                                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                                    >
                                        Add First Driver
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($drivers->hasPages())

            <div
                class="border-t border-gray-200 px-5 py-4 dark:border-gray-800"
            >
                {{ $drivers->links() }}
            </div>

        @endif

    </div>

</div>


</x-app-layout>
