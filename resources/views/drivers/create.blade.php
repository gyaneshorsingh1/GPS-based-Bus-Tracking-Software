@php($page = 'drivers')

<x-app-layout page="drivers">

```
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Add Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Add a new school bus driver
            </p>
        </div>

        <a
            href="{{ route('drivers.index') }}"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
        >
            Back
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div
            class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-400"
        >

            <p class="font-semibold">
                Please fix the following errors:
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('drivers.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- PERSONAL INFORMATION --}}
        <div
            class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Personal Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Enter the driver's personal and contact information.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- FIRST NAME --}}
                <div>

                    <label
                        for="first_name"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        First Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- LAST NAME --}}
                <div>

                    <label
                        for="last_name"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Last Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- GENDER --}}
                <div>

                    <label
                        for="gender"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Gender <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                        <option value="">
                            Select Gender
                        </option>

                        <option
                            value="Male"
                            {{ old('gender') === 'Male' ? 'selected' : '' }}
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            {{ old('gender') === 'Female' ? 'selected' : '' }}
                        >
                            Female
                        </option>

                        <option
                            value="Other"
                            {{ old('gender') === 'Other' ? 'selected' : '' }}
                        >
                            Other
                        </option>

                    </select>

                </div>


                {{-- DATE OF BIRTH --}}
                <div>

                    <label
                        for="date_of_birth"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Date of Birth <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- PHONE --}}
                <div>

                    <label
                        for="phone"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Phone Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="98XXXXXXXX"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                    >

                </div>


                {{-- EMAIL --}}
                <div>

                    <label
                        for="email"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="driver@example.com"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                    >

                </div>


                {{-- PASSWORD --}}
                <div>

                    <label
                        for="password"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="8"
                        placeholder="Minimum 8 characters"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                    >

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Used to log in to the driver account.
                    </p>

                </div>


                {{-- ADDRESS --}}
                <div class="md:col-span-2 lg:col-span-2">

                    <label
                        for="address"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Address <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="2"
                        required
                        placeholder="Enter current address"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                    >{{ old('address') }}</textarea>

                </div>


                {{-- CITY --}}
                <div>

                    <label
                        for="city"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        City
                    </label>

                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- STATE --}}
                <div>

                    <label
                        for="state"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        State
                    </label>

                    <input
                        type="text"
                        id="state"
                        name="state"
                        value="{{ old('state') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- COUNTRY --}}
                <div>

                    <label
                        for="country"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Country
                    </label>

                    <input
                        type="text"
                        id="country"
                        name="country"
                        value="{{ old('country', 'Nepal') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- POSTAL CODE --}}
                <div>

                    <label
                        for="postal_code"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Postal Code
                    </label>

                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- LICENSE INFORMATION --}}
        <div
            class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    License Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Enter driving license and experience details.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- LICENSE NUMBER --}}
                <div>

                    <label
                        for="license_number"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        License Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="license_number"
                        name="license_number"
                        value="{{ old('license_number') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- LICENSE TYPE --}}
                <div>

                    <label
                        for="license_type"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        License Type <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="license_type"
                        name="license_type"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                        <option value="">
                            Select License Type
                        </option>

                        <option
                            value="Heavy Vehicle"
                            {{ old('license_type') === 'Heavy Vehicle' ? 'selected' : '' }}
                        >
                            Heavy Vehicle
                        </option>

                        <option
                            value="Bus"
                            {{ old('license_type') === 'Bus' ? 'selected' : '' }}
                        >
                            Bus
                        </option>

                        <option
                            value="Other"
                            {{ old('license_type') === 'Other' ? 'selected' : '' }}
                        >
                            Other
                        </option>

                    </select>

                </div>


                {{-- EXPERIENCE --}}
                <div>

                    <label
                        for="experience_years"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Driving Experience
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            id="experience_years"
                            name="experience_years"
                            value="{{ old('experience_years') }}"
                            min="0"
                            max="80"
                            placeholder="0"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-16 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >

                        <span
                            class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"
                        >
                            Years
                        </span>

                    </div>

                </div>


                {{-- ISSUE DATE --}}
                <div>

                    <label
                        for="license_issue_date"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        License Issue Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="license_issue_date"
                        name="license_issue_date"
                        value="{{ old('license_issue_date') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- EXPIRY DATE --}}
                <div>

                    <label
                        for="license_expiry_date"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        License Expiry Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="license_expiry_date"
                        name="license_expiry_date"
                        value="{{ old('license_expiry_date') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- EMPLOYMENT INFORMATION --}}
        <div
            class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Employment Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Configure school assignment and employment status.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- SCHOOL --}}
                @if(auth()->user()->hasAnyRole(['School Admin', 'Principal']))

                    <div class="md:col-span-2">

                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            School
                        </label>

                        <input
                            type="text"
                            value="{{ isset($school) && $school ? $school->name : 'School not assigned' }}"
                            readonly
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400"
                        >

                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                            The driver will automatically be assigned to your school.
                        </p>

                    </div>

                @else

                    <div class="md:col-span-2">

                        <label
                            for="school_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            School
                        </label>

                        <select
                            id="school_id"
                            name="school_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        >

                            <option value="">
                                Select School (optional)
                            </option>

                            @foreach($schools as $school)

                                <option
                                   value="{{ isset($school) && $school ? $school->name : 'School not assigned' }}"
                                >
                                    {{ $school->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif


                {{-- JOINING DATE --}}
                <div>

                    <label
                        for="joining_date"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Joining Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="joining_date"
                        name="joining_date"
                        value="{{ old('joining_date', now()->toDateString()) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- STATUS --}}
                <div>

                    <label
                        for="status"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >

                        <option
                            value="Active"
                            {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status') === 'Inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                        <option
                            value="Suspended"
                            {{ old('status') === 'Suspended' ? 'selected' : '' }}
                        >
                            Suspended
                        </option>

                    </select>

                </div>


                {{-- REMARKS --}}
                <div class="md:col-span-2 lg:col-span-3">

                    <label
                        for="remarks"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Remarks
                    </label>

                    <textarea
                        id="remarks"
                        name="remarks"
                        rows="3"
                        placeholder="Add any additional notes about the driver..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                    >{{ old('remarks') }}</textarea>

                </div>

            </div>

        </div>


        {{-- FORM ACTIONS --}}
        <div class="mb-8 flex items-center justify-end gap-3">

            <a
                href="{{ route('drivers.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
            >
                Save Driver
            </button>

        </div>

    </form>

</div>
```

</x-app-layout>
