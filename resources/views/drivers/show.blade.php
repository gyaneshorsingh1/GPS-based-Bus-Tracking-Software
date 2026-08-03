@php($page = 'drivers')

<x-app-layout page="drivers">

```
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Driver Details
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                View complete information about {{ $driver->full_name }}
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a
                href="{{ route('drivers.edit', $driver) }}"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
            >
                Edit Driver
            </a>

            <a
                href="{{ route('drivers.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                Back
            </a>

        </div>

    </div>


    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- LEFT PROFILE CARD --}}
        <div class="lg:col-span-1">

            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="p-6 text-center">

                    {{-- PROFILE PHOTO --}}
                    <div class="mb-5 flex justify-center">

                        @if($driver->profile_photo)

                            <img
                                src="{{ asset('storage/' . $driver->profile_photo) }}"
                                alt="{{ $driver->full_name }}"
                                class="h-32 w-32 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-800"
                            >

                        @else

                            <div
                                class="flex h-32 w-32 items-center justify-center rounded-full bg-brand-100 text-4xl font-semibold text-brand-600 ring-4 ring-gray-100 dark:bg-brand-500/10 dark:text-brand-400 dark:ring-gray-800"
                            >
                                {{ strtoupper(substr($driver->first_name, 0, 1)) }}
                            </div>

                        @endif

                    </div>


                    {{-- NAME --}}
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $driver->full_name }}
                    </h2>

                    {{-- EMPLOYEE ID --}}
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $driver->employee_id }}
                    </p>


                    {{-- STATUS --}}
                    <div class="mt-4">

                        @if($driver->status === 'Active')

                            <span
                                class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
                            >
                                Active
                            </span>

                        @elseif($driver->status === 'Suspended')

                            <span
                                class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400"
                            >
                                Suspended
                            </span>

                        @else

                            <span
                                class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400"
                            >
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>


                {{-- QUICK INFORMATION --}}
                <div class="border-t border-gray-200 px-6 py-5 dark:border-gray-800">

                    <div class="space-y-4">

                        {{-- PHONE --}}
                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Phone
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $driver->phone }}
                            </p>

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Email
                            </p>

                            <p class="mt-1 break-all text-sm font-medium text-gray-900 dark:text-white">
                                {{ $driver->email ?? '—' }}
                            </p>

                        </div>


                        {{-- SCHOOL --}}
                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                School
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $driver->school->name ?? '—' }}
                            </p>

                        </div>


                        {{-- JOINING DATE --}}
                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Joining Date
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $driver->joining_date?->format('M d, Y') ?? '—' }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- RIGHT CONTENT --}}
        <div class="space-y-6 lg:col-span-2">


            {{-- PERSONAL INFORMATION --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Personal Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Basic information about the driver
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">

                    {{-- FIRST NAME --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            First Name
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->first_name }}
                        </p>

                    </div>


                    {{-- LAST NAME --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Last Name
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->last_name }}
                        </p>

                    </div>


                    {{-- GENDER --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Gender
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->gender }}
                        </p>

                    </div>


                    {{-- DATE OF BIRTH --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Date of Birth
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->date_of_birth?->format('M d, Y') ?? '—' }}
                        </p>

                    </div>


                    {{-- PHONE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Phone Number
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->phone }}
                        </p>

                    </div>


                    {{-- EMAIL --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Email Address
                        </p>

                        <p class="mt-1 break-all text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->email ?? '—' }}
                        </p>

                    </div>


                    {{-- ADDRESS --}}
                    <div class="sm:col-span-2">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Address
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->address }}
                        </p>

                    </div>


                    {{-- CITY --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            City
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->city ?? '—' }}
                        </p>

                    </div>


                    {{-- STATE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            State
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->state ?? '—' }}
                        </p>

                    </div>


                    {{-- COUNTRY --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Country
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->country ?? '—' }}
                        </p>

                    </div>


                    {{-- POSTAL CODE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Postal Code
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->postal_code ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- LICENSE INFORMATION --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        License Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Driver's driving license and experience details
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- LICENSE NUMBER --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            License Number
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $driver->license_number }}
                        </p>

                    </div>


                    {{-- LICENSE TYPE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            License Type
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->license_type }}
                        </p>

                    </div>


                    {{-- EXPERIENCE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Experience
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->experience_years ?? 0 }} years
                        </p>

                    </div>


                    {{-- ISSUE DATE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Issue Date
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->license_issue_date?->format('M d, Y') ?? '—' }}
                        </p>

                    </div>


                    {{-- EXPIRY DATE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Expiry Date
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->license_expiry_date?->format('M d, Y') ?? '—' }}
                        </p>

                    </div>


                    {{-- LICENSE STATUS --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            License Status
                        </p>

                        @if($driver->license_expiry_date && $driver->license_expiry_date->isPast())

                            <span
                                class="mt-1 inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400"
                            >
                                Expired
                            </span>

                        @elseif(
                            $driver->license_expiry_date &&
                            $driver->license_expiry_date->diffInDays(now()) <= 30
                        )

                            <span
                                class="mt-1 inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400"
                            >
                                Expiring Soon
                            </span>

                        @else

                            <span
                                class="mt-1 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
                            >
                                Valid
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- EMPLOYMENT INFORMATION --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Employment Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        School and employment details
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">

                    {{-- SCHOOL --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            School
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $driver->school->name ?? '—' }}
                        </p>

                    </div>


                    {{-- EMPLOYEE ID --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Employee ID
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $driver->employee_id }}
                        </p>

                    </div>


                    {{-- JOINING DATE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Joining Date
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->joining_date?->format('M d, Y') ?? '—' }}
                        </p>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Employment Status
                        </p>

                        @if($driver->status === 'Active')

                            <span
                                class="mt-1 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
                            >
                                Active
                            </span>

                        @elseif($driver->status === 'Suspended')

                            <span
                                class="mt-1 inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400"
                            >
                                Suspended
                            </span>

                        @else

                            <span
                                class="mt-1 inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400"
                            >
                                Inactive
                            </span>

                        @endif

                    </div>


                    {{-- REMARKS --}}
                    <div class="sm:col-span-2">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Remarks
                        </p>

                        <p class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            {{ $driver->remarks ?? 'No remarks available.' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- EMERGENCY CONTACT --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Emergency Contact
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Emergency contact information
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">

                    {{-- CONTACT NAME --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Contact Name
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->emergency_contact_name ?? '—' }}
                        </p>

                    </div>


                    {{-- CONTACT PHONE --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Contact Number
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->emergency_contact_phone ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- RECORD INFORMATION --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Record Information
                    </h2>

                </div>


                <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Created By
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->creator->name ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Created On
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->created_at?->format('M d, Y h:i A') ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Last Updated
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $driver->updated_at?->format('M d, Y h:i A') ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
```

</x-app-layout>
